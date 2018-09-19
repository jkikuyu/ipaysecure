<?php
	namespace IpaySecure;
	use IpaySecure\JWTUtil;
	use IpaySecure\Utils;
	
	require_once ('classes/ClientRequest.php');
	require_once ('classes/JWTUtil.php');
	
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	session_start();
	// log request
	Utils::logger(array_merge(['request_time' => new DateTime(), 'request_type' => 'php://input'], ['request_data' => json_decode(file_get_contents('php://input'))]), $request_log_dir);	


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
	$recd_data = json_decode(file_get_contents('php://input'));

	$transaction->initInput($recd_data);


	$_SESSION['transactionId'] = uniqid();

	$_SESSION['order'] = array(
	    "OrderDetails" => array(
	        "OrderNumber" =>  '1234567890',
	        "Amount" => '1500',
	        "CurrencyCode" => '404'
	        )
	);
	$req = new ClientRequest();

     	$sample = '{
     		"order_id":"1234567890",
          	"first_Name":"John",
          	"last_Name":"Doe",
			"street":"Sifa Towers, Ring Rd",
          	"city":"Nairobi",
          	"email":"abc@test.com",
          	"account_Number":"4000000000000002",
          	"expiration_Month":12,
          	"expiration_Year":2019,
	        "currency":"KES",
          	"amount": 30000
	 
     	}';
     	//$cardDetails = json_decode($sample);

		//$req->makeRequest($cardDetails);


		$jwtUtil = new JWTUtil();
		$jwt = $jwtUtil->generateJwt($_SESSION['transactionId'], $_SESSION['order']);

        session_unset();
        session_destroy();
		//$success = $jwtUtil->validateJwt($jwt);
?>
<!--https://cardinaldocs.atlassian.net/wiki/spaces/CC/pages/557065/Songbird.js#Songbird.js-Events -->
<!--Production URL: https://songbirdstag.cardinalcommerce.com/edge/v1/songbird.js -->
<!--Staging URL: https://songbirdstag.cardinalcommerce.com/edge/v1/songbird.js -->

<!--Sandbox URL: https://utilsbox.cardinalcommerce.com/cardinalcruise/v1/songbird.js -->

	<script src="https://includestest.ccdc02.com/cardinalcruise/v1/songbird.js"></script>
	<script src="https://code.jquery.com/jquery-3.3.0.js"></script>
	<script>
		//https://developer.cardinalcommerce.com/cardinal-cruise-activation.shtml#validatingResponseJWT
		$(document).ready(function(){
			  initCCA();
		});		
		
		
					//Listen for Events
		    Cardinal.on('payments.setupComplete', function(setupCompleteData){
		    	console.log(JSON.stringify(setupCompleteData));

				alert ("Init done");
				card_start();
			});	
			Cardinal.on("payments.validated", function (data, jwt) {
				
				console.log(JSON.stringify(data,null, 2));
		    switch(data.ActionCode){
		      case "SUCCESS":
		      // Handle successful transaction, send JWT to backend to verify
		      	alert('success');
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
			alert ("starting");
		Cardinal.start("cca", { 
		  OrderDetails: {
		    OrderNumber: "1234567890",
		    Amount:"1500",
		    CurrencyCode: "404",
		    OrderDescription:"test description",
		    OrderChannel:"M"

		},
		Consumer: {
		    Email1: "joe@abc.com",

			Account:{
		    AccountNumber: "4000000000000002",
		    ExpirationMonth: "12",
		    ExpirationYear: "2019",
		    NameOnAccount:"John Doe",
		    CardCode:"366"
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
			// configuration of Cardinal Cruise
		//	Cardinal.configure({
		//	    logging: {
		//	        level: "on",
		//	        debug: "verbose"
		///	    }
		//	});
			// get jwt container
			console.log(JSON.stringify(document.getElementById("JWTContainer").value));
			Cardinal.setup("init", {
			    jwt: document.getElementById("JWTContainer").value
			});

	
		}
	</script>
<input type="hidden" id="JWTContainer" value= "<?php echo $jwt;?>" />
<input type="text" data-cardinal-field="AccountNumber" id="creditCardNumber" name="creditCardNumber" />

</body>

</html>