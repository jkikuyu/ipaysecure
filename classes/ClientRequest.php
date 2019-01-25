<?php
/**
** @author :jude
**/

namespace IpaySecure;
	require_once('classesAutoload.php');

	class ClientRequest{
	private $referenceID;
	private $firstName ;
	private $lastName;
	private $street ;
	private $city;
	private $email;
	private $accountNumber;
	private $expirationMonth;
	private $expirationYear ;
	private $currency ;
	private $amount;
	private $cardType;

     public function payerAuthEnrollService($cardDetails){
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
		$request['referenceID'] = $this->referenceID;
		$request['purchaseTotals_currency'] = $this->currency;
		$request['purchaseTotals_grandTotalAmount']=$this->amount;
		$request['card_accountNumber'] = $this->accountNumber;
		$request['card_expirationMonth'] = $this->expirationMonth;
		$request['card_expirationYear'] = $this->expirationYear;
		$request['purchaseTotals_currency'] =$this->currency;
		$request['card_cardType']= $this->cardType;
		$request['merchantID'] = $this->referenceID;
		$request['ccAuthService_run'] = 'true';
		$request['merchantReferenceCode'] = $this->order_id;
/*
		$request['item_0_unitPrice'] = $this->amount;

		$request['billTo_firstName'] = $this->firstName;
		$request['billTo_lastName']  = $this->lastName;
		$request['billTo_street1']   = $this->street;
		$request['billTo_city'] 	=  $this->city;
		$request['billTo_state'] = '';
		$request['billTo_postalCode'] = '';
		$request['billTo_country'] = 'KE';
*/
		$request['billTo_email'] = $this->email;
		if($cca !== null){
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
	public function setAuthEnrollDetails($cardDetails){
		//echo "last name". $cardDetails->last_Name;
		$this->referenceID = $cardDetails->referenceId;
		$arr =include('iso_4217_currency_codes.php');
			foreach ($arr as $currency => $code) {
			 if ($code === $cardDetails->currency_code){
			 	$this->currency =$currency;
			 	break;
			}
		}
		$this->amount = $cardDetails->OrderDetails->Amount;
		$this->accountNumber=$cardDetails->Account->AccountNumber;
		$this->expirationMonth=$cardDetails->Account->ExpirationMonth;
		$this->expirationYear = $cardDetails->Account->ExpirationYear;
		$this->cardType = $cardDetails->cardType;
		$this->merchantID=$merchantId;
		$this->order_id = $cardDetails->OrderDetails->OrderNumber;
/*		$this->first_Name = $cardDetails->BillingAddress->FirstName;
		$this->last_Name = $cardDetails->BillingAddress->LastName;
		$this->street  =  $cardDetails->street;
		$this->city=$cardDetails->city;
		$this->email = $cardDetails->email;
*/

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
