<?php

require __DIR__ . '/vendor/autoload.php';


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
     public function makeRequest($cardDetails){
     	$this->validCard($cardDetails);

		$client = new CybsNameValuePairClient();


		$request = array();
		$request['ccAuthService_run'] = 'true';
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
		//$request['item_0_unitPrice'] = $this->amount;
		$request['item_0_unitPrice'] = '12.34';
		$request['item_1_unitPrice'] = '56.78';

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
     	$cardDetails = json_decode($sample);

		$req->makeRequest($cardDetails);
?>
