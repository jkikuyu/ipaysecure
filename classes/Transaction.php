<?php
    /**
    ** @author:andrew
    **/
    namespace IpaySecure;
    use IpaySecure\INT\TransactionInterface;
	register_shutdown_function(array('IpaySecure\Utils', 'suddenDeath'));

    //require_once(dirname(__dir__).'/interfaces/TransactionInterface.php');
    require_once ('classes/interfaces/TransactionInterface.php');
    class Transaction implements TransactionInterface{
        private $transactionId;
        private $orderId;
        private $orderDescription;
        private $currencycode;
        private $first_Name;
        private $last_Name;
        private $email;                
        private $city;
        private $account_number;
        private $expiration_month;
        private $expiration_year;
        private $cvv;
		private $cardType;
        private $currency;
        private $amount;



        public function initInput($recd_data = null){
            $required_params_primary = [
				'cardType',
                'street'
            ];
			$required_params_order=[
				'OrderNumber',
				'Amount',
                'OrderDescription',
                'TransactionId',
                'CurrencyCode'

			];
			$required_params_address =[          
				'FirstName',
                'LastName',
                'City'
			];
			$required_params_consumer =['Email1'];
	
	        $required_params_account=[
				'AccountNumber',
                'ExpirationMonth',
                'ExpirationYear',
                'CardCode'
			 ];
				
            try{
                if(empty($recd_data)){
                    throw new \InvalidArgumentException('POST JSON cannot be empty');
                }
				$order = $recd_data->OrderDetails;
				//var_dump($order);
				$consumer=$recd_data->Consumer;
				$address = $consumer->BillingAddress;
				$account = $recd_data->Account;
				//validate primary details
				
                $isValid = Utils::validatePhpInput($recd_data, $required_params_primary);
				//validate order
				$isValid = Utils::validatePhpInput($order, $required_params_order);
				//valid address
				$isValid = Utils::validatePhpInput($consumer, $required_params_consumer);
				//valid consumer
				$isValid = Utils::validatePhpInput($address, $required_params_address);
				//valid account
				$isValid = Utils::validatePhpInput($account, $required_params_account);

	
                if($isValid){
					//primary
					$this->street = $recd_data->street;
					
					// Order details
                    $this->transactionId = $order->TransactionId;
                    $this->orderId = $order->OrderNumber;
                    $this->orderDescription = $order->OrderDescription;
					$this->amount = $order->Amount;
                    $this->currencycode = $order->CurrencyCode;
					// consumer email
                    $this->email = $consumer->Email1;
					// address
					$this->firstName = $address->FirstName;
                    $this->lastName = $address->LastName;
					$this->city = $address->City;
					// account 
                    $this->account_number = $account->AccountNumber;
                    $this->expiration_month = $account->ExpirationMonth;
                    $this->expiration_year =  $account->ExpirationYear;
                    $this->cvv =  $account->CardCode;
                }
                else{
                    return $res;
                }

            }
            catch(\InvalidArgumentException $e){
                die(Utils::formatError($e, 'Invalid POST JSON'));
            }
            catch(Exception $e){
                die(Utils::formatError($e, 'Invalid POST JSON'));
            }
        }
        public function getTransactionInfo(){
            return (object) [
				'cardType'=>$this->cardType,
                'transactionId'=>$this->transactionId,
                'orderNumber'=> $this->orderId,
                'currency_code'=>$this->currencycode,
                'first_Name' => $this->first_Name,
                'last_Name'=> $this->last_Name,
                'email'=>$this->email ,                
                'city'=>$this->city,
                'street'=>$this->street,
                'account_number'=>$this->account_number,
                'expiration_month'=>$this->expiration_month ,
                "expiration_year" =>$this->expiration_year,
                "amount" => $this->amount

            ];
        }
}
?>