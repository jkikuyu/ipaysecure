<?php
	namespace IpaySecure;
	use IpaySecure\JWTUtil;
	use IpaySecure\ClientRequest;
	use IpaySecure\Transaction;
	//use IpaySecure\Utils;
	require_once('classesAutoload.php');


	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	// log request
	$request_log_dir = 'request_logs';
	$tag = 'ipaysecure';
	Utils::getLogFile($tag);

/*
	Utils::logger(array_merge(['request_time' => new \DateTime(), 'request_type' => 'php://input'], ['request_data' => json_decode(file_get_contents('php://input'))]), $request_log_dir);	
*/


?>
<html>
<head>

</head>
<body>
<?php
/**
  * thin client architecture: https://cardinaldocs.atlassian.net/wiki/spaces/CCen/pages/56229960/Getting+Started
**/
/* Test Data*/
	//use IpaySecure\Secure3d\ClientRequest;


	$transaction = new Transaction();
	$jsonData = file_get_contents('php://input');
	$recd_data = '';
	//echo $jsonData;
	if(!isset($jsonData) || empty($jsonData)){
		//sample data
		$jsonData = '{
			"cardType":"001",
			"street":"Sifa Towers, Lenana Rd",
			"OrderDetails":{
				"OrderNumber":"1234567890",
				"OrderDescription":"test Description", 
				"Amount":"30000",
				"CurrencyCode":"404",
				"OrderChannel":"M",
				"TransactionId":"'.uniqid().'"
			},
			"Consumer":{
				"Email1":"abc@test.com",
				"BillingAddress":{
					"FirstName":"John",
					"MiddleName":"C",
					"LastName":"Doe",
					"Address1":"sdfdfdfddfddf",
					"City":"Nairobi",
					"Phone1":"3234455"
				}
			},
			"Account":{
				"AccountNumber":"4000000000000002",
				"CardCode":"366",
				"ExpirationMonth":"12",
				"ExpirationYear":"2019"
			}


		}';
		}
	
	$recd_data = json_decode($jsonData);
	
	$transaction->initInput($recd_data);
	$_SESSION['recd_data'] = $recd_data;
	$transactionInfo = $transaction->getTransactionInfo();
	$referenceId = uniqid();
	$aref = ["refenceId"=>$referenceId];
	$jsonData = json_encode(array_merge(json_decode($json_data,true),$aref));

	$jwtUtil = new JWTUtil();
	$jwt = $jwtUtil->generateJwt($transactionInfo->transactionId, $transactionInfo->orderNumber, $referenceId);
?>
<!--https://cardinaldocs.atlassian.net/wiki/spaces/CC/pages/557065/Songbird.js#Songbird.js-Events -->
<!--Production URL: https://songbirdstag.cardinalcommerce.com/edge/v1/songbird.js -->
<!--Staging URL: https://songbirdstag.cardinalcommerce.com/edge/v1/songbird.js -->

<!--Sandbox URL: https://utilsbox.cardinalcommerce.com/cardinalcruise/v1/songbird.js -->
	<script src="https://includestest.ccdc02.com/cardinalcruise/v1/songbird.js"></script>
	<!--script src="https://includestest.ccdc02.com/cardinalcruise/v1/songbird.js"></script>-->
	<script src="https://code.jquery.com/jquery-3.3.0.js"></script>
	<script>
			var purchase = <?php echo $jsonData; ?>;
			console.log(purchase)
			var orderObject = {
			  Consumer: {
				Account: {
				  AccountNumber: purchase.Account.AccountNumber
				}
			  }
			};

			$(document).ready(function(){
				  initCCA();
			});		
			Cardinal.trigger("bin.process", purchase.Account.AccountNumber)
				.then(function(results){
				if(results.Status) {
					// Bin profiling was successful. Some merchants may want to only move forward with CCA if profiling was successful
					//$.extend(result, purchase, vcard);
					fetch("CardAuthEnrollService.php", {
						method: "POST", // *GET, POST, PUT, DELETE, etc.

						body: JSON.stringify(purchase), // body data type must match "Content-Type" header
					})
				.then(response => {
					console.log(response.json())}); // parses response to JSON


				} else {
					// Bin profiling failed
				}

				// Bin profiling, if this is the card the end user is paying with you may start the CCA flow at this point
				//Cardinal.start('cca', myOrderObject);
			  })
			  .catch(function(error){
				// An error occurred during profiling
			  })			
		
					//Listen for Events
		    Cardinal.on('payments.setupComplete', function(setupCompleteData){
		    	console.log(JSON.stringify(setupCompleteData));

				//alert ("Init done");
				card_start();
			});	
			Cardinal.on("payments.validated", function (vcard, jwt) {
				
			//console.log(JSON.stringify(data,null, 2));
		    switch(vcard.ActionCode){
		      case "SUCCESS":
				var result={};
		      	console.log('validated result ....................');
					
		      	console.log ("dataxxxxxxxxxxxxxx :"+JSON.stringify(vcard));
				

					  break;

					  case "NOACTION":
						alert('NOACTION');

					  // Handle no actionable outcome
					  break;

					  case "FAILURE":
						 alert('FAILURE');

					  // Handle failed transaction attempt
					  break;

					  case "ERROR":
						 alert('ERROR:' +data.ErrorDescription);

					  // Handle service level error
					  break;
				  }
			});
		
		function card_start(){
			//alert ("starting");
			//alert (purchase.amount);
			//Order channel:     M – MOTO (Mail Order Telephone Order), R – Retail
    		//S – eCommerce ,P – Mobile Device,  T – Tablet

			Cardinal.start("cca", { 
			  OrderDetails: {
				OrderNumber: purchase.OrderDetails.OrderNumber,
				Amount:purchase.OrderDetails.Amount,
				CurrencyCode: purchase.Account.CardCode,
				OrderDescription:purchase.OrderDetails.OrderDescription,
				OrderChannel:purchase.OrderDetails.OrderChannel

			},
			Consumer: {
				Email1:purchase.Consumer.Email1,
				Account:{
				AccountNumber: purchase.Account.AccountNumber,
				ExpirationMonth: purchase.Account.ExpirationMonth,
				ExpirationYear: purchase.Account.ExpirationYear
				}
			}

			});
		}

	/*		Cardinal.start("cca", {
		Email1: "joe@abc.com",

		OrderDetails: {
		    OrderNumber: "1234567890",
		    Amount:"1500",
		    CurrencyCode: "404",
		    OrderDescription:"test description",
		    OrderChannel:"M"

		},
		Consumer: {
		    Email1: "joe@abc.com",
		    ShippingAddress:{
		    	FirstName:"John",
		    	MiddleName:"C",
		    	LastName:"Doe",
		    	Address1:"sdfdfdfddfddf",
		    	City:"Nairobi",
		    	Phone1:"3234455"
		    },
		    BillingAddress:{
				FirstName:"John",
		    	MiddleName:"C",
		    	LastName:"Doe",
		    	Address1:"sdfdfdfddfddf",
		    	City:"Nairobi",
		    	Phone1:"3234455"
			},
			Account:{
		    AccountNumber: "123456789",
		    ExpirationMonth: "12",
		    ExpirationYear: "2019",
			}
		},
OrderDetails

    OrderNumber
    Amount
    CurrencyCode
    OrderChannel
*/	


		function initCCA(){
			// get jwt container
			console.log(JSON.stringify(document.getElementById("JWTContainer").value));
			Cardinal.setup("init", {
			    jwt: document.getElementById("JWTContainer").value,
				order: orderObject
			});
/*
			var accountNumber = 
			Cardinal.trigger("bin.process", purchase.Account.AccountNumber);
*/
	
		}
	</script>
<input type="hidden" id="JWTContainer" value= "<?php echo $jwt;?>" />
<div id="accno"></div>
 
</body>

</html>