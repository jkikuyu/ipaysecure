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
		echo $jwt;
		$success = $jwtUtil->validateJwt($jwt);
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
		    ShippingAddress:{
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
		    NameOnAccount:"John Doe",
		    CardCode:"366"
			}
		}
	
		});
/*
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
		    NameOnAccount:"John Doe",
		    CardCode:"366"
			}
		},
		Cart:{
		  	Name:"Ptryc",
		  	SKU:"212334",	
			Quantity:"1",	
			DescriptioN:"sweet"
		},
		Options:{
			EnableCCA:"true"

		}
*/	


		function initCCA(){
			// configuration of Cardinal Cruise
			Cardinal.configure({
			    logging: {
			        level: "on",
			        debug: "verbose"
			    }
			});
			// get jwt container
				Cardinal.setup("init", {
			    jwt: document.getElementById("JWTContainer").value
			});

			//Listen for Events
		    Cardinal.on('payments.setupComplete', function(setupCompleteData){
		    	console.log(JSON.stringify(setupCompleteData));
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
		      	 alert('ERROR:' +data.ErrorDescription);

		      // Handle service level error
		      break;
		  }
		});
		
	
		}
	</script>
<input type="hidden" id="JWTContainer" value= "<?php echo $jwt;?>" />

</body>

</html>