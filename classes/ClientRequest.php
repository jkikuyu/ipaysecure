<?php
/**
** @author :jude
**/

namespace IpaySecure;
	require_once('classesAutoload.php');

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
	private $cardType;

    function __construct(){
      	// Test credentials
    	$this->order_id = 'A12356789';
		$this->first_Name = 'John';
		$this->last_Name = 'Doe';
		$this->street  =  '';
		$this->city='Nairobi';
		$this->email = "abc@test.com";
		$this->account_Number='	4000000000000002';
		$this->expiration_Month='12';
		$this->expiration_Year = '2019';
		$this->currency = 'KES';
		$this->amount = '290.00';
     }
     public function makeRequest($cardDetails){
     	self::setCardDetails($cardDetails);
		 	  
     //	$this->validCard($cardDetails);
		 $tag = 'ipaysecure';
		Utils::getLogFile($tag);
		$merchantId = getenv('MERCHANT_ID');
		$transactionkey = getenv('TRANSACTION_KEY');
		$options = [$merchantId,$transactionkey];

		$client = new \CybsNameValuePairClient($options);


		 echo "bill to :". $this->last_Name;
		$request = array();
		$request['ccAuthService_run'] = 'true';
		$request['card_cardType']= $this->cardType;
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
			//var_dump($cca);
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
		
			$request['ccAuthService_cavv'] = $cca->CAVV;
			$request['ccAuthService_commerceIndicator']=$cca->ccAuthService_commerceIndicator;
			$request['ccAuthService_xid'] = $cca->XID;
			$request['ccAuthService_veresEnrolled'] = $cca->Enrolled;
			$request['ccAuthService_paresStatus']=$cca->PAResStatus;
		**/
		}


		//$request['item_0_unitPrice'] = '12.34';
		//$request['item_1_unitPrice'] = '56.78';

		//$request['item_1_unitPrice'] = '56.78';
		$reply = $client->runTransaction($request);

		// This section will show all the reply fields.
		
		echo '<pre>';
		print("\nRESPONSE:\n" . $reply);
		preg_match_all("/ ([^:=]+) [:=]+ ([^\\n]+) /x",  $reply, $p);
		$arr = array_combine($p[1], $p[2]);
		//$arr = explode("=",  $reply);
		print_r($arr);
		echo '</pre>';
	}
	public function setCardDetails($cardDetails){
		//echo "last name". $cardDetails->last_Name;
		
		$this->order_id = $cardDetails->orderNumber;
		$this->first_Name = $cardDetails->first_Name;
		$this->last_Name = $cardDetails->last_Name;
		$this->street  =  $cardDetails->street;
		$this->city=$cardDetails->city;
		$this->email = $cardDetails->email;
		$this->account_Number=$cardDetails->account_number;
		$this->expiration_Month=$cardDetails->card_expiration_month;
		$this->expiration_Year = $cardDetails->card_expiration_year;
		$this->cardType = $cardDetails->cardType;
		$this->amount = $cardDetails->amount;
		$arr =include('iso_4217_currency_codes.php');
			foreach ($arr as $currency => $code) {
			 if ($code === $cardDetails->currency_code){
			 	$this->currency =$currency;
			 	break;
			}
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
