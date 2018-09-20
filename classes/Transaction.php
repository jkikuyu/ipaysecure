<?php
    /**
    ** @author:andrew
    **/
    namespace IpaySecure;
    use IpaySecure\INT\TransactionInterface;
    //require_once(dirname(__dir__).'/interfaces/TransactionInterface.php');
    require_once ('classes/interfaces/TransactionInterface.php');
    class Transaction implements TransactionInterface{
        private $transactionId;
        private $orderId;
        private $currencycode;
        private $first_Name;
        private $last_Name;
        private $email;                
        private $city;
        private $account_number;
        private $expiration_month;
        private $expiration_year;
        private $currency;
        private $amount;



        public function initInput($php_input = null){
            $required_params = [
                'transactionId',
                'orderNumber',
                'currency_code',
                'first_Name',
                'last_Name',
                'email',                
                'city',
                'street',
                'account_number',
                'expiration_month',
                "expiration_year",
                "amount"
            ];
            try{
                if(empty($php_input)){
                    throw new \InvalidArgumentException('POST JSON cannot be empty');
                }
                $valid_input = Utils::validatePhpInput($php_input, $required_params);
                $this->transactionId = $valid_input->transactionId;
                $this->orderId = $valid_input->orderNumber;
                $this->currencycode = $valid_input->currency_code;
                $this->first_Name = $valid_input->first_Name;
                $this->last_Name = $valid_input->last_Name;
                $this->email = $valid_input->email;
                $this->account_number = $valid_input->account_number;
                $this->city = $valid_input->city;
                $this->street = $valid_input->street;
                $this->amount = $valid_input->amount;
                $this->account_number = $valid_input->account_number;
                $this->expiration_month = $valid_input->expiration_month;
                $this->expiration_year =  $valid_input->expiration_year;

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