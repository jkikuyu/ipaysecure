<?php
	namespace IpaySecure;

	require_once('Secure3d.php');

	$cardInfo = '{
		"cardType":"001",
		"street":"Sifa Towers, Lenana Rd",
		"referenceId":"987654321",
		"OrderDetails":{
			"OrderNumber":"123456789",
			"OrderDescription":"test Description", 
			"Amount":"100",
			"CurrencyCode":"KES",
			"OrderChannel":"M",
			"TransactionId":"'.uniqid().'"
		},
		"Consumer":{
			"Email1":"abc@review.com",
			"BillingAddress":{
				"FirstName":"William",
				"MiddleName":"C",
				"LastName":"Paul",
				"Address1":"Argwings Kodhek Rd",
				"City":"Nairobi",
				"CountryCode":"KE",
				"Phone1":"722644550"
			}
		},

		"Account":{
			"AccountNumber":"4000000000000002",
			"CardCode":"366",
			"ExpirationMonth":"12",
			"ExpirationYear":"2019"
		}

	}';

$secure3d = new Secure3d($cardInfo);
$secure3d->processCard();

?>
