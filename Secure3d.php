<?php
	namespace IpaySecure;
	use IpaySecure\JWTUtil;
	use IpaySecure\ClientRequest;
	use IpaySecure\Transaction;
	//use IpaySecure\Utils;
	require_once dirname(__DIR__) . '/ipaysecure/vendor/autoload.php';

	require_once ('classes/ClientRequest.php');
	require_once ('classes/JWTUtil.php');
	require_once ('classes/Utils.php');
	require_once ('classes/Transaction.php');

	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	session_start();
	// log request
	$request_log_dir = 'request_logs';
	Utils::logger(array_merge(['request_time' => new \DateTime(), 'request_type' => 'php://input'], ['request_data' => json_decode(file_get_contents('php://input'))]), $request_log_dir);	


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
	echo $jsonData;
	if(!isset($jsonData) || empty($jsonData)){
		//sample data
		$jsonData = '{"type":"001","transactionId":"'.uniqid().'","orderNumber":"1234567890","orderDescription":"test Description","currency_code":"404","first_Name":"John","last_Name":"Doe","email":"abc@test.com","city":"Nairobi","street":"Sifa Towers, Ring Rd","account_number":"4000000000000002","card_cvv":"366","card_expiration_month":12,"card_expiration_year":2019,"amount":30000}' ;
	}
	$recd_data = json_decode($jsonData);

	if($recd_data->type =='CCA'){
		$cardDetails = $_SESSION['$recd_data'];
		$req = new ClientRequest();
		$req->makeRequest($cardDetails,$recd_data);
	}
	else{

		$transaction->initInput($recd_data);
		$_SESSION['recd_data'] = $recd_data;

			/*	$_SESSION['transactionId'] = uniqid();

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
			*/     	//$cardDetails = json_decode($sample);

			//$req->makeRequest($cardDetails);
			$transactionInfo = $transaction->getTransactionInfo();


			$jwtUtil = new JWTUtil();
			$jwt = $jwtUtil->generateJwt($transactionInfo->transactionId, $transactionInfo->orderNumber);
		}
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
			var purchase = <?php echo $jsonData; ?>;
			//alert(val);

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

				$.ajax({
				  url: "http://localhost/ipaysecure/Secure3d.php",
				  method: "POST",
				  data: JSON.stringify(data),
				  success: function(result){
				  	console.log(result)
				  },
				  error:function(error){
				  	console.log('Error ${error}')
				  }
				})
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
			alert (purchase.amount);
			//Order channel:     M – MOTO (Mail Order Telephone Order), R – Retail
    		//S – eCommerce ,P – Mobile Device,  T – Tablet

		Cardinal.start("cca", { 
		  OrderDetails: {
		    OrderNumber: purchase.orderNumber,
		    Amount:purchase.amount,
		    CurrencyCode: purchase.currency_code,
		    OrderDescription:purchase.OrderDescription,
		    OrderChannel:"S"

		},
		Consumer: {
			email1:purchase.email,
			Account:{
		    AccountNumber: purchase.account_number,
		    ExpirationMonth: purchase.card_expiration_month,
		    ExpirationYear: purchase.card_expiration_year,
		    NameOnAccount:purchase.first_Name ,
		    CardCode:purchase.card_cvv
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
<!-- <input type="text" data-cardinal-field="AccountNumber" id="creditCardNumber" name="creditCardNumber" />
 -->
</body>

</html>