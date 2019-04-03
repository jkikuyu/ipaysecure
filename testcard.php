<?php
	namespace IpaySecure;

	require_once('Secure3d.php');

	$cardInfo = '{
		"cardType":"001",
		"referenceId":"987654321",
		"OrderDetails":{
			"OrderNumber":"123456789",
			"Amount":"100",
			"CurrencyCode":"KES",
			"OrderChannel":"M",
			"TransactionId":"M123456789"
		},
		"Consumer":{
			"Email1":"abc@review.com",
			"BillingAddress":{
				"FirstName":"William",
				"LastName":"Paul",
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
