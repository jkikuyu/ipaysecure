<?php
namespace IpaySecure;


class ClientRequest{
	private $reference_Code;
	private $first_Name ;
	private $last_Name;
	private $street ;
	private $city;
	private $email;
	private $account_Number;
	private $expiration_Month;
	private $expiration_Year ;
	private $currency ;
	private $amount;

    function __construct()
      {
      	// Test credentials
    	$this->order_id = 'A12356789';
		$this->firstName = 'John';
		$this->lastName = 'Doe';
		$this->street  =  '';
		$this->city='Nairobi';
		$this->email = "abc@test.com";
		$this->accountNumber='4111111111111111';
		$this->expirationMonth='12';
		$this->expirationYear = '2019';
		$this->currency = 'KES';
		$this->amount = '290.00';
     }
     public function makeRequest($cardDetails, $cca){
     	setCardDeatils($cardDetails);
     //	$this->validCard($cardDetails);

		$client = new CybsNameValuePairClient();



		$request = array();
		$request['ccAuthService_run'] = 'true';
		$request['card_cardType']= $this->type;
		$request['merchantReferenceCode'] = $this->order_id;
		$request['billTo_firstName'] = $this->first_Name;
		$request['billTo_lastName']  = $this->last_Name;
		$request['billTo_street1']   = $this->street;
		$request['billTo_city'] 	=  $this->city;
		$request['billTo_state'] = '';
		$request['billTo_postalCode'] = '';
		$request['billTo_country'] = 'KE';
		$request['billTo_email'] = $this->email;
		$request['card_accountNumber'] = $this->account_Number;
		$request['card_expirationMonth'] = $this->expiration_Month;
		$request['card_expirationYear'] = $this->expiration_Year;
		$request['purchaseTotals_currency'] =$this->currency;
		$request['item_0_unitPrice'] = $this->amount;
		if($cca !== null){
			var_dump($cca);
			// include authentication request
			/**
				
				{
					  "Validated": true,
					  "ErrorNumber": 0,
					  "ErrorDescription": "Success",
					  "ActionCode": "SUCCESS",
					  "Payment": {
					    "Type": "CCA",
					    "ExtendedData": {
					      "CAVV": "jELUbgG+Tfj0AREACMLdCae+oIs=",
					      "ECIFlag": "02",
					      "XID": "TTdENWJjVngxVHF0YUJlQUcxbjA=",
					      "UCAFIndicator": "2",
					      "Enrolled": "Y",
					      "PAResStatus": "Y",
					      "SignatureVerification": "Y"
					    },
					    "ProcessorTransactionId": "M7D5bcVx1TqtaBeAG1n0"
					  }
} 
			**/
			$request['ccAuthService_cavv'] = $cca->CAVV;
			$request['ccAuthService_commerceIndicator']=$cca->ccAuthService_commerceIndicator;
			$request['ccAuthService_xid'] = $cca->XID;
			$request['ccAuthService_veresEnrolled'] = $cca->Enrolled;
			$request['ccAuthService_paresStatus']=$cca->PAResStatus;
		}


		}
		//$request['item_0_unitPrice'] = '12.34';
		//$request['item_1_unitPrice'] = '56.78';

		//$request['item_1_unitPrice'] = '56.78';
		$reply = $client->runTransaction($request);

		// This section will show all the reply fields.
		/**
		echo '<pre>';
		print("\nRESPONSE:\n" . $reply);
		preg_match_all("/ ([^:=]+) [:=]+ ([^\\n]+) /x",  $reply, $p);
		$arr = array_combine($p[1], $p[2]);
		//$arr = explode("=",  $reply);
		print_r($arr);
		echo '</pre>';
		**/
	}
	public function setCardDetails($cardDetails){

		$this->order_id = $cardDetails->order_id;
		$this->firstName = $cardDetails->first_Name;
		$this->lastName = $cardDetails->last_Name;
		$this->street  =  $cardDetails->street;
		$this->city=$cardDetails->city;
		$this->email = $cardDetails->email;
		$this->accountNumber=$cardDetails->account_number;
		$this->expirationMonth=$cardDetails->card_expiration_month;
		$this->expirationYear = $cardDetails->card_expiration_yea;
		
		$this->amount = $cardDetails->amount;
		$arr =include('iso_4217_currency_codes.php');
			foreach ($arr as $currency => $code) {
			 if ($code[1] === '404'){
			 	$this->currency = $cardDetails->$currency;
			 	break;
		 }

	}

	function validCard($required){
		foreach ($required as $key => $value) {
			if (empty($value)) {
                throw new Exception(strtolower(str_replace('_',' ',$key)) . ' is missing and is a required.');
            }
            else{
            	$this->$key = $value;

            }

        }
	}
}
?>
