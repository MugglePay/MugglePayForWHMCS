<?php
# 异步返回页面
# Required File Includes
include("../../../init.php");
include("../../../includes/functions.php");
include("../../../includes/gatewayfunctions.php");
include("../../../includes/invoicefunctions.php");

if(!class_exists('BitpayX')) {
    include("../bitpayx/class.php");
}

// function  log_result($word) {
//     $fp = fopen("./bitpayx_log.txt","a");    
//     flock($fp, LOCK_EX) ;
//     fwrite($fp,$word."：执行日期：".strftime("%Y%m%d%H%I%S",time())."\t\n");
//     flock($fp, LOCK_UN); 
//     fclose($fp);
// }
// log_result(http_build_query($_REQUEST));
$GATEWAY                    = getGatewayVariables('bitpayxForAliglobal');
$url                        = $GATEWAY['systemurl'];
$companyname                = $GATEWAY['companyname'];
$currency                   = $GATEWAY['currency'];

if (!$GATEWAY["type"]) die("Module Not Activated"); # Checks gateway module is active before accepting callback
if (!$GATEWAY['appSecret']) die("BitpayX Not Activated");

$appSecret                  = $GATEWAY['appSecret'];
$bitpayx                    = new BitpayX($appSecret);

$inputString = file_get_contents('php://input', 'r');
$inputStripped = str_replace(array("\r", "\n", "\t", "\v"), '', $inputString);
$inputJSON = json_decode($inputStripped, true); //convert JSON into array

$cbData = [
    'status'                 => $inputJSON['status'],
    'order_id'               => $inputJSON['order_id'],
    'merchant_order_id'      => $inputJSON['merchant_order_id'],
    'price_amount'           => $inputJSON['price_amount'],
    'price_currency'         => $inputJSON['price_currency'],
    'pay_amount'           => $inputJSON['pay_amount'],
    'pay_currency'           => $inputJSON['pay_currency'],
    'created_at_t'           => $inputJSON['created_at_t']
];

$strToSign = $bitpayx->prepareSignId($inputJSON['merchant_order_id']);
$verify_result = $bitpayx->verify($strToSign, $inputJSON['token']);

if(!$verify_result) { 
    logTransaction($GATEWAY["name"],$_POST,"Unsuccessful");
    $return = [];
    $return['status'] = 400;
    $return['error'] = "Error " . json_encode($inputJSON) . " " . json_encode($cbData) . "";
    echo json_encode($return);
    exit;
} else {
    $isPaid = $cbData['status'] !== null && $cbData['status'] === 'PAID';
    if($isPaid) {
        $merchantinvoiceId = $cbData['merchant_order_id'];
        $invoiceId = substr($merchantinvoiceId, 6); // WHMCS_{id}
        $transid = $cbData['order_id'];
        $paymentAmount = $cbData['pay_amount'];
        $feeAmount = 0;

        // TODO(duplicate)
        checkCbTransID($transid);
        $invoiceId = checkCbInvoiceID($invoiceId, $GATEWAY["name"]);

        //货币转换开始
        //获取支付货币种类
        $currencytype = \Illuminate\Database\Capsule\Manager::table('tblcurrencies')->where('id', $gatewayParams['convertto'])->first();
        
        //获取账单 用户ID
        $userinfo = \Illuminate\Database\Capsule\Manager::table('tblinvoices')->where('id', $invoiceId)->first();
        
        //得到用户 货币种类
        $currency = getCurrency( $userinfo->userid );
        
        // 转换货币
        $paymentAmount = convertCurrency( $paymentAmount, $currencytype->id, $currency['id'] );
        // 货币转换结束

        addInvoicePayment($invoiceId,$transid,$paymentAmount,$feeAmount,'bitpayxForAliglobal');
        logTransaction($GATEWAY["name"], $_POST, "Successful-A");
        // echo 'SUCCESS';exit;
        $return = [];
        $return['status'] = 200;
        echo json_encode($return);
        exit;
    } else {
        logTransaction($GATEWAY["name"],$_POST,"Unsuccessful");
        $return = [];
        $return['status'] = 400;
        $return['error'] = "Error: not paid ";
        echo json_encode($return);
        exit;
    }
}
?>