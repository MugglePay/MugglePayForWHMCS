<?php
require_once __DIR__ . '/../../../init.php';
use \Illuminate\Database\Capsule\Manager as Capsule;
$invoiceid = $_REQUEST['invoiceid'];
$ca = new WHMCS_ClientArea();
$userid = $ca->getUserID() ;
if($userid == 0){
	echo "FAIL_USER";
    exit;
}
//echo $userid;
$query = Capsule::table('tblinvoices')->where('id', $invoiceid)->where('userid', $userid)->first();
$status = '';
if( $query ) {
    $status 		= $query->status;
    $paymentmethod 	= $query->paymentmethod;
}
if($status === ""){
    echo "FAIL_NOT_FOUND";
} else if ($status === "Paid"){
    echo "SUCCESS";
} else {
    echo "FAIL_NOT_PAID";
}