<?php
    /**
    ** @author:andrew
    **/
    namespace IpaySecure;
    use IpaySecure\interfaces\Transaction;
    require_once(dirname(__dir__).'/interfaces/TransactionInterface.php');
    class Transaction implements TransactionInterface{
        private $order_id;
        private $msisdn;
        private $vendor_id;
        private $amount;
        private $currency;
        private $email;
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

        public function initInput($php_input = null){
            $required_params = [
                'transactionId',
                'orderNumber',
                'Amount',
                'CurrencyCode',
                'first_Name',
                'last_Name',
                'email',                
                'city',
                'account_Number',
                'expiration_Month',
                "expiration_Year",
                "currency",
                "amount"
            ];
            try{
                if(empty($this->php_input)){
                    throw new InvalidArgumentException('POST JSON cannot be empty');
                }
                $valid_input = Utils::validatePhpInput($this->php_input, $required_params);
                $this->order_id = $valid_input->order_id;
                $this->msisdn = $valid_input->msisdn;
                $this->vendor_id = $valid_input->vendor_id;
                $this->amount = $valid_input->amount;
                $this->currency = $valid_input->currency;
                $this->email = $valid_input->email;
            }
            catch(InvalidArgumentException $e){
                die(Utils::formatError($e, 'Invalid POST JSON'));
            }
            catch(Exception $e){
                die(Utils::formatError($e, 'Invalid POST JSON'));
            }
        }
        public function getTransactionInfo(){
            return (object) [
                'order_id' => $this->order_id,
                'msisdn' => $this->msisdn,
                'email' => $this->email,
                'vendor_id' => $this->vendor_id,
                'amount' => $this->amount,
                'currency' => $this->currency
            ];
        }
}
?>