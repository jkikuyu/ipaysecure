<?php
namespace IpaySecure;
require_once ('classes/ClientRequest.php');


error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

$jsonData = file_get_contents('php://input');
$req = new ClientRequest();

if(isset($jsonData)){
	echo $jsonData;
	$recd_data = json_decode($jsonData);
	if($recd_data->Payment->Type =='CCA'){
		$req->payerAuthEnrollService($recd_data);
	}
	else{
	}
}

session_unset();
session_destroy();


?>