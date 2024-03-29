<?php
	namespace IpaySecure;
	use IpaySecure\JWTUtil;

	use IpaySecure\Utils;
	ini_set('display_errors', 1);
	error_reporting(E_ALL);

	require_once('vendor/autoload.php');
	require_once('classes/JWTUtil.php');
	require_once('classes/Utils.php');


	class Secure3d{
		// log request
		//$request_log_dir = get;

		private $jsonData;
		private $recd_data = '';
		function __construct($cardInfo){
			$tag = 'ipaysecure';
			Utils::getLogFile($tag);

			$this->jsonData = $cardInfo;
		}

		public function processCard(){
				//echo $this->jsonData;
				$recd_data = json_decode($this->jsonData);
				//$referenceId = uniqid();
		/*		$aref = ["referenceId"=>$referenceId];
				$this->jsonData = json_encode(array_merge(json_decode($jsonData,true),$aref));
				$xid = "";
		*/
				$jwtUtil = new JWTUtil();
				$jwt = $jwtUtil->generateJwt($recd_data->OrderDetails->TransactionId, $recd_data->OrderDetails,  
				$recd_data->referenceId);
?>
				<html>
				<head>
				</head>
				<body>
					<script src="https://songbirdstag.cardinalcommerce.com/cardinalcruise/v1/songbird.js"></script>

					<!--script src="https://includestest.ccdc02.com/cardinalcruise/v1/songbird.js"></script>-->
					<script src="https://code.jquery.com/jquery-3.3.0.js"></script>
					<script>
						var purchase = <?php echo $this->jsonData; ?>;
						//console.log(purchase);
						var enrollobj = "";
						var transactionId = "";

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
						fetch("CardAuthEnrollService.php", {
							method: "POST", // *GET, POST, PUT, DELETE, etc.

							body: JSON.stringify(purchase), // body data type must match "Content-Type" header
						})
						.then(r =>  r.json())
						.then(data => 	bin_process(data));
						//.catch(error => console.log(error));

						Cardinal.on('payments.setupComplete', function(setupCompleteData){
							//console.log(JSON.stringify(setupCompleteData));

						});	
						Cardinal.on("payments.validated", function (vcard, jwt) {
								//console.log("here at payment validated............");
						//Listen for Events
					    switch(vcard.ErrorNumber){

					      case 0:
					      		console.log ("dataxxxxxxxxxxxxxx :"+JSON.stringify(vcard));
					      		//console.log(transactionId);
					      		xid = {"xid":transactionId};
					      		//console.log(xid);
								var result = {...purchase,
				                              ...vcard,
				                              ...xid

				                             };
								//console.log("result:" + JSON.stringify(result));
								fetch("CardValidateService.php", {
									method: "POST", // *GET, POST, PUT, DELETE, etc.

									body: JSON.stringify(result), // body data type must match "Content-Type" header
								})
								.then(r =>  r.json())
								.then(data => validComplete(data));


						  break;

						  case 1:
							alert('NOACTION');

						  // Handle no actionable outcome
						  break;

						  case 2:
							 alert('FAILURE');

						  // Handle failed transaction attempt
						  break;

						  case 3:
							 alert('ERROR:' +data.ErrorDescription);

						  // Handle service level error
						  break;

					  }
						});

								
						function bin_process(data){
							transactionId = data.payerAuthEnrollReply_xid;
							Cardinal.trigger("bin.process", purchase.Account.AccountNumber)
								.then(function(results){

								if(results.Status) {
									// Bin profiling was successful. Some merchants may want to only move forward with CCA if profiling was successful

								} else {
									// Bin profiling failed
								}
							//	console.log(results.Status);
								card_continue(data);
							// Bin profiling, if this is the card the end user is paying with you may start the CCA flow at this point
								//Cardinal.start('cca', myOrderObject);
							  })
							  .catch(function(error){
							  	//console.log(error);
								// An error occurred during profiling
							  })			

						}
						function valid_complete(validobj){

						}
						function card_continue(enrollobj){
							Cardinal.continue("cca", { 
								"AcsUrl":enrollobj.payerAuthEnrollReply_acsURL,
								"Payload":enrollobj.payerAuthEnrollReply_paReq,

							},
							{
							 "OrderDetails":{
								"TransactionId":enrollobj.payerAuthEnrollReply_authenticationTransactionID
							}
							});
						}


						function initCCA(){
							// get jwt container
							//console.log(JSON.stringify(document.getElementById("JWTContainer").value));
							Cardinal.setup("init", {
							    jwt: document.getElementById("JWTContainer").value,
								order: orderObject
							});

						}
					</script>
				<input type="hidden" id="JWTContainer" value= "<?php echo $jwt;?>" />
				<div id="accno"></div>
				</body>
				</html>
	<?php
		}

	}
?>