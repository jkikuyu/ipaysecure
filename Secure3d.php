<?php
/* Test Data*/
	$_SESSION['transactionId'] = uniqid();

	$_SESSION['Order'] = array(
	    "OrderDetails" => array(
	        "OrderNumber" =>  'ORDER-' . strval(mt_rand(1000, 10000)),
	        "Amount" => '1500',
	        "CurrencyCode" => '840'
	        )
	);
	$req = new ClientRequest();
     	$sample = '{
     		"order_id":"A12345678",
          	"first_Name":"John",
          	"last_Name":"Doe",
			"street":"Sifa Towers, Ring Rd",
          	"city":"Nairobi",
          	"email":"abc@test.com",
          	"account_Number":"4111111111111111",
          	"expiration_Month":12,
          	"expiration_Year":2019,
	        "currency":"KES",
          	"amount": 30000
	 
     	}';
     	$cardDetails = json_decode($sample);

		$req->makeRequest($cardDetails);


	$jwt = generateJwt($_SESSION['TransactionId'], $_SESSION['Order']);
?>