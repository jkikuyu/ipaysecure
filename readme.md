iPay -Cybersource 3D Secure 
====================

This is the documentation for integrating to the Cybersource 3d security feature that will enable the cardholder to enter a one time pin(OTP) before completion of the checkout process.

## API Deployment
- Pull code from bitbucket using git clone https://jkikuyu@bitbucket.org/jkikuyu/ipaysecure.git. run composer install to get the following dependancies

| Dependancy |Description 										      | url        |
|------------|------------------------------------------------------------------------------------------------|------------|
| cybersource|This is the PHP client for the CyberSource SOAP Toolkit API.  				      |            |
| phpdotenv  |Loads environment variables from .env   							      |            |
| JWT	     |A simple library to work with JSON Web Token and JSON Web Signature based on the RFC 7519.      |            | 
| php-jwt    |A simple library to encode and decode JSON Web Tokens (JWT) in PHP, conforming to RFC 7519.     |            |
        	
        	
- Place it in htdocs of your webserver

## Environment variables

- Create a .env file located in ipaysecure/classes/secure
- The following are description of the variables

| variables   |Description 										      |
|-------------|-----------------------------------------------------------------------------------------------|
| MERCHANT_ID |Merchant identifier recognized by cybersource  				      		      |            
| NVP_WSDL_URL|Cybersource name value pair endpoint (https://ics2wstest.ic3.com/commerce/1.x/transactionProcessor/ for the latest endpoints)		      			                                                              |            
| ORGUNIT_ID  |Cardinal Cruise Javascript credential for use with songbird.js		         	      |            
| API_ID      |Cardinal Cruise Javascript credential for use with songbird.js	      			      |            
| API_KEY     |Cardinal Cruise Javascript credential for use with songbird.js	      			      |            
| LOGDIR      |Log file location	               				      			      |            

- Change the NVP_WSDL_URL to reflect current endpoint
- Set ORGUNIT_ID, API_ID ,API_KEY to values provided for production

## Card Verification and Validation request
A request with card details is forwarded to the endpoint in this format.
~~~~
	{
	"cardType":"001",
	"street":"Sifa Towers, Lenana Rd",
	"OrderDetails":{
		"OrderNumber":"123456789",
		"OrderDescription":"Phone cover", 
		"Amount":"1000",
		"CurrencyCode":"KES",
		"OrderChannel":"M",
		"TransactionId":"'.uniqid().'"
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
	}

~~~~ 
- Card type is either 001 - visa or 002 - mastercard  
- Currency code is either USD or KES
- Billing information is required during the validation process

## Endpoint
The endpoint to access shall be (ipaysecure/Secure3d.php)






