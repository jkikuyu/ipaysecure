<?php
	namespace IpaySecure;
	use JWTUtil;
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
	require_once ('classes/ClientRequest.php');
	//require_once ('classes/JWTUtil.php');
	$_SESSION['transactionId'] = uniqid();

	$_SESSION['order'] = array(
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
     	//$cardDetails = json_decode($sample);

		//$req->makeRequest($cardDetails);


		$jwtUtil = new JWTUtil();
		$jwt = $jwtUtil->generateJwt($_SESSION['transactionId'], $_SESSION['order']);
?>
<!--https://cardinaldocs.atlassian.net/wiki/spaces/CC/pages/557065/Songbird.js#Songbird.js-Events -->
<!--Production URL: https://songbirdstag.cardinalcommerce.com/edge/v1/songbird.js -->
<!--Staging URL: https://songbirdstag.cardinalcommerce.com/edge/v1/songbird.js -->

<!--Sandbox URL: https://utilsbox.cardinalcommerce.com/cardinalcruise/v1/songbird.js -->

	<script src="https://utilsbox.cardinalcommerce.com/cardinalcruise/v1/songbird.js"></script>
	<script src="https://code.jquery.com/jquery-3.3.0.js"></script>
	<script>
		//https://developer.cardinalcommerce.com/cardinal-cruise-activation.shtml#validatingResponseJWT
		$(document).ready(function(){
			  initCCA();
		});
		Cardinal.start("cca", {
		  OrderDetails: {
		    OrderNumber: "1234567890",
		    Amount:"1000",
		    CurrencyCode: "KES"

		  },
		  Consumer: {
		    Account: {
		    AccountNumber: "4111111111111111",
		    ExpirationMonth: "01",
		    ExpirationYear: "2099"
		  }
		}
		});

		
		Cardinal.on("payments.validated", function (data, jwt) {
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
		      	 alert('ERROR');

		      // Handle service level error
		      break;
		  }
		});
		

		function initCCA(){
			// configuration of Cardinal Cruise
			Cardinal.configure({
			    logging: {
			        level: "on"
			    }
			});
			// get jwt container
			Cardinal.setup("init", {
			    jwt: document.getElementById("JWTContainer").value
			});

			//Listen for Events
/*		    Cardinal.on('payments.setupComplete', function(setupCompleteData){
		    	alert('here');
		    	//alert(JSON.stringify(setupCompleteData));
			});	
*/	
		}
	</script>
<input type="hidden" id="JWTContainer" value= "<?php echo $jwt;?>" />

</body>

</html>