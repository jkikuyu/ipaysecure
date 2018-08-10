<?php
    namespace ipaysecure

    use Lcobucci\JWT\Builder;
    use Lcobucci\JWT\Signer\Hmac\Sha256;
    clase JWTUtil {

        $api_Key ;
        $api_Id ;
        $orgUnit_Id;
        function __construct() {
            /* test credentials*/
            $this->api_Key = '754be3dc-10b7-471f-af31-f20ce12b9ec1';
            $this->api_Id = '582e0a2033fadd1260f990f6';
            $this->orgUnit_Id = '582be9deda52932a946c45c4';

        }


        /*  $GLOBALS['ApiKey'] = '[INSERT_API_KEY_HERE]';
            $GLOBALS['ApiId'] = '[INSERT_API_KEY_ID_HERE]';
            $GLOBALS['OrgUnitId'] = '[INSERT_ORG_UNIT_ID_HERE]';
        */     

     
        function generateJwt($orderTransactionId, $orderObj){
         
            $currentTime = time();
            $expireTime = 3600; // expiration in seconds - this equals 1hr
         
            $token = (new Builder())->setIssuer($this->api_Key) // API Key Identifier (iss claim)
                        ->setId($orderTransactionId, true) // The Transaction Id (jti claim)
                        ->setIssuedAt($currentTime) // Configures the time that the token was issued (iat claim)
                        ->setExpiration($currentTime + $expireTime) // Configures the expiration time of the token (exp claim)
                        ->set('OrgUnitId',   $this->orgUnit_Id) // Configures a new claim, called "OrgUnitId"
                        ->set('Payload', $_SESSION['Order']) // Configures a new claim, called "Payload", containing the OrderDetails
                        ->set('ObjectifyPayload', true)
                        ->sign(new Sha256(),  $this->api_Key) // Sign with API Key
                        ->getToken(); // Retrieves the generated token
         
            return $token; // The JWT String
        }
     
    
    }

?>