<?php
function bitpayxForAlipay_config() {
    $configarray = [
        "FriendlyName"         => ["Type" => "System", "Value"=>"支付宝（BitpayX）"],
        "appSecret"         => ["FriendlyName" => "AppSecret", "Type" => "text", "Size" => "32", ],
    ];
    return $configarray;
}

function bitpayxForAlipay_refund($params) {
    if(!class_exists('BitpayX')) {
        include("bitpayx/class.php");
    }
    return [
        'status' => 'success',
        'rawdata' => 'Please contact for refund. 请联系客服完成退款。',
        'transid' => $params['transid'],
        'fees' => $params['amount'],
    ];
    return [
          'status' => '400',
          'error' => 'Refund is not supported.'
    ];
}

function bitpayxForAlipay_link($params) {
    $systemurl = $params['systemurl'];
    if(substr($systemurl, -1) !== '/'){
        $systemurl = $systemurl . '/';
    }

    if (!stristr($_SERVER['PHP_SELF'], 'viewinvoice')) {
        return '<img style="width: 150px" src="'.$systemurl.'modules/gateways/bitpayx/alipay.png" alt="支付宝支付" />';
    }
    if(!class_exists('BitpayX')) {
        include("bitpayx/class.php");
    }

    $bitpayx = new BitpayX($params['appSecret']);
    $payData = [
        'merchant_order_id' => 'WHMCS_' . $params['invoiceid'],
        'price_amount' => $params['amount'],
        'price_currency' => 'CNY',
        'pay_currency' => 'ALIPAY',
        'title' => '支付单号：' . $params['invoiceid'],
        'description' => '充值：' . $params['amount'] . ' 元',
        'callback_url' => $systemurl."modules/gateways/bitpayxForAlipay/notify.php",
        'success_url' => $systemurl."viewinvoice.php?id=".$params['invoiceid'],
        'cancel_url' => $systemurl."viewinvoice.php?id=".$params['invoiceid'],
    ];

    $str_to_sign = $bitpayx->prepareSignId($payData['merchant_order_id']);
    $payData['token'] = $bitpayx->sign($str_to_sign);
    $result = $bitpayx->mprequest($payData);

    $code_ajax = '';
    $webpaylink = '';
    if (($result['status'] === 200 || $result['status'] === 201) && $result['payment_url']) {
        $webpaylink = $result['payment_url'] . '&lang=zh';
        $code_ajax = '<a href="'.$webpaylink.'" target="_blank" id="alipayBitpayX" class="btn btn-info btn-block">前往支付宝进行支付</a>';
        $code = $code . '<div class="alipay" style="max-width: 230px;margin: 0 auto">' . $code_ajax . '</div>';
        return $code.'<script>
            window.location = "' . $webpaylink . '";
        </script>';
    } else if ($result['status'] === 400 && $result['error_code'] === 'ORDER_MERCHANTID_EXIST' && $result['order']) {
        if ($result['order']['status'] === 'NEW') {
            $webpaylink = 'https://invoice.mugglepay.com/invoices/?id=' . $result['order']['order_id'] . '&lang=zh';
            $code_ajax = '<a href="'.$webpaylink.'" target="_blank" id="alipayBitpayX" class="btn btn-info btn-block">前往支付宝进行支付</a>';
        } else if ($result['order']['status'] === 'PAID') {
            $webpaylink = 'https://invoice.mugglepay.com/invoices/?id=' . $result['order']['order_id'] . '&lang=zh';
            $code_ajax = '<a href="'.$webpaylink.'" target="_blank" id="alipayBitpayX" class="btn btn-info btn-block">支付成功，等待商家确认</a>';
        }
    } else {
        $code_ajax = '<a href="#" id="alipayBitpayX" class="btn btn-info btn-block">支付确认中</a>';
    }

    $code = $code . '<div class="alipay" style="max-width: 230px;margin: 0 auto">' . $code_ajax . '</div>';
    return $code.'<script>
        //设置每隔 5000 毫秒执行一次 load() 方法
        setInterval(function(){loadBitpayX()}, 5000);
        function loadBitpayX(){
            var xmlhttp;
            if (window.XMLHttpRequest){
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp=new XMLHttpRequest();
            }else{
                // code for IE6, IE5
                xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange=function(){
                if (xmlhttp.readyState==4 && xmlhttp.status==200){
                    trade_state=xmlhttp.responseText;
                    if(trade_state=="SUCCESS"){
                        document.getElementById("alipayBitpayX").innerHTML="支付成功";
                        window.location.reload()
                    }
                }
            }
            //invoice_status.php 文件返回订单状态，通过订单状态确定支付状态
            xmlhttp.open("get","'.$systemurl.'modules/gateways/bitpayx/query.php?invoiceid='.$params['invoiceid'].'",true);
            //下面这句话必须有
            //把标签/值对添加到要发送的头文件。
            //xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
            //xmlhttp.send("out_trade_no=002111");
            xmlhttp.send();
        }
    </script>';
}
?>
