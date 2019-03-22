iPay -Cybersource 3D Secure 
====================

This is the documentation for integrating to the Cybersource 3d security feature that will enable the cardholder to enter a one time pin(OTP) before completion of the checkout process.

## API Deployment
- Pull code from bitbucket using git clone https://jkikuyu@bitbucket.org/jkikuyu/ipaysecure.git. run composer install to get the following dependancies

| Dependancy |Description 										      | 
|------------|------------------------------------------------------------------------------------------------|
| cybersource|This is the PHP client for the CyberSource SOAP Toolkit API.  				      |            
| phpdotenv  |Loads environment variables from .env   							      |
| JWT	     |A simple library to work with JSON Web Token and JSON Web Signature based on the RFC 7519.      |
| php-jwt    |A simple library to encode and decode JSON Web Tokens (JWT) in PHP, conforming to RFC 7519.     |
        	
- Replace the CybsClient.php with that file from vendor/cybersource/sdk-php/lip folder         	
- Copy the ipaysecure folder to the webserver

## Environment variables

- Create a .env file located in ipaysecure/classes/secure
- The following are description of the variables

| variables   |Description 										      |
|-------------|-----------------------------------------------------------------------------------------------|
| MERCHANT_ID |Merchant identifier recognized by cybersource  				      		      |            
| NVP_WSDL_URL|Cybersource name value pair endpoint (https://ics2wsa.ic3.com/commerce/1.x/transactionProcessor)
for the latest endpoints		      			                                                              |            
| ORGUNIT_ID  |Cardinal Cruise Javascript credential for use with songbird.js. This tells the API which processor ID to place the newly registered merchant under.  		         	      |            
| API_KEY     |Cardinal Cruise Javascript credential for use with songbird.js.The ApiKey will be given by Cardinal. This is how we identify who you are.	      			      |
| API_ID      |Cardinal Cruise Javascript credential for use with songbird.js. This works in conjunction with the ApiKey to generate the signature.	      			      |            
| LOGDIR      |Log file location	               				      			      |            

- Change the NVP_WSDL_URL to reflect current endpoint

- Set ORGUNIT_ID, API_ID ,API_KEY to values provided for production

- Specify the location of your log folder will be created and ensure that it has pre-requiste permission.


## Card Verification and Validation request
A request with card details is forwarded to the endpoint in this format.
~~~~
	$cardInfo = '{
	"cardType":"001",
	"street":"Sifa Towers, Lenana Rd",
	"referenceID":"987654321",
	"OrderDetails":{
		"OrderNumber":"123456789",
		"OrderDescription":"Phone cover", 
		"Amount":"1000",
		"CurrencyCode":"KES",
		"OrderChannel":"M",
		"TransactionId":"M123456789"
	},
	"Consumer":{
		"Email1":"abc@review.com",
		"BillingAddress":{
			"FirstName":"William",
			"MiddleName":"C",
			"LastName":"Paul",
			"Address1":"Argwings Kodhek Rd",
			"City":"Nairobi",
			"CountryCode":"KE",
			"Phone1":"722644550"
		}
	},

	"Account":{
		"AccountNumber":"4000000000000002",
		"CardCode":"366",
		"ExpirationMonth":"12",
		"ExpirationYear":"2019"
	}';

~~~~ 
- Card  information is required during the validation process

## Usage
1. Instantiate the Secure3d class and pass your json containing card information as follows. 

2. Call the processCard function to begin the 3d authentication process
~~~~ 
$secure3d = new Secure3d($cardInfo);
$secure3d->processCard();
~~~~ 

## FAQ
Q1. Errors are displayed when composer install is run

Ensure you have php-curl, php-mbstring and php-soap installed for the version of php you are using
