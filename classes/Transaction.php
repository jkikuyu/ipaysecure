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

        private $currency;
        private $amount;



        public function initInput($recd_data = null){
            $required_params = [
                'type',
                'transactionId',
                'orderNumber',
                'orderDescription',
                'currency_code',
                'first_Name',
                'last_Name',
                'email',                
                'city',
                'street',
                'account_number',
                'card_expiration_month',
                "card_expiration_year",
                "card_cvv",
                "amount"
            ];
            try{
                if(empty($recd_data)){
                    throw new \InvalidArgumentException('POST JSON cannot be empty');
                }
                $isValid = Utils::validatePhpInput($recd_data, $required_params);
                if($isValid){
                    $this->transactionId = $recd_data->transactionId;
                    $this->orderId = $recd_data->orderNumber;
                    $this->orderDescription = $recd_data->orderDescription;

                    $this->currencycode = $recd_data->currency_code;
                    $this->first_Name = $recd_data->first_Name;
                    $this->last_Name = $recd_data->last_Name;
                    $this->email = $recd_data->email;
                    $this->account_number = $recd_data->account_number;
                    $this->city = $recd_data->city;
                    $this->street = $recd_data->street;
                    $this->amount = $recd_data->amount;
                    $this->account_number = $recd_data->account_number;
                    $this->expiration_month = $recd_data->card_expiration_month;
                    $this->expiration_year =  $recd_data->card_expiration_year;
                    $this->cvv =  $recd_data->card_cvv;

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