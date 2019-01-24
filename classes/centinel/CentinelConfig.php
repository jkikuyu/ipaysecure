<?php
//////////////////////////////////////////////////////////////////////////////////////////////
//	Cardinal Commerce (http://www.cardinalcommerce.com)
//	CentinelConfig.php
//	Configuration file for Thin Client Integrations
//
//	Usage
//		Configuration file centralizes integration paramaters for easy management. Each variable
//      is required.
//		Note: Curl thin client does not use  CENTINEL_TIMEOUT_CONNECT
//
//		CENTINEL_PROCESSOR_ID
//			Your assigned Centinel Processor Id. Contact Support if you need assistance in
//          determining what value to use.
//
//      CENTINEL_MERCHANT_ID
//			Your assigned Centinel Merchant Id. Contact Support if you need assistance in
//          determining what value to use.
//
//      CENTINEL_TRANSACTION_PWD
//          Transaction password defined by the merchant within the merchant profile. Note that this
//          is NOT your user password. Contact Support if you need assistance in
//            determining what value to use.
//
//		CENTINEL_MAPS_URL
//			The fully qualified URL to the MAPS server. Contact Support if you need assistance in
//          determining what value to use.
//
//          Note: For testing use the following MAPS server URL.
//
//          https://centineltest.cardinalcommerce.com/maps/txns.asp
//
//		CENTINEL_TERM_URL
//          Represents the fully qualified address of the webpage on your website that will
//          receive the HTTP Form POST from the Centinel System. This page will process the
//			cmpi_authenticate message and receive the results of the authentication.
//
//		DEMO_TERM_URL
//          Represents the fully qualified address of the webpage on your website that will
//          receive the HTTP Form POST from the Centinel System. This page will process the
//			cmpi_authenticate message and receive the results of the authentication for the demo.
//
//      CENTINEL_TIMEOUT_READ
//          Connection timeout in value seconds. Timeout value related to receiving the
//          response from the transaction url.
//
//		CENTINEL_TIMEOUT_CONNECT
//          Connection timeout in value seconds. Timeout value related to establishing
//          a connection to the transaction url.
//
//
//////////////////////////////////////////////////////////////////////////////////////////////


    define("CENTINEL_MSG_VERSION", "1.7");
	define("CENTINEL_PROCESSOR_ID", "enter value");
    define("CENTINEL_MERCHANT_ID", "enter value");
	define("CENTINEL_TRANSACTION_PWD", "enter value");

	define("CENTINEL_MAPS_URL", "https://centineltest.cardinalcommerce.com/maps/txns.asp");
	
	define("CENTINEL_TERM_URL", "http://localhost/cardinal/CardinalConsumerAuthentication/ccAuthenticate.php");

    // Check with Cardinal to determine appropriate Timeout period for this payment type
	define("CENTINEL_TIMEOUT_CONNECT","5000");
	define("CENTINEL_TIMEOUT_READ","15000");

	define("CENTINEL_AUTHENTICATION_MESSAGING","For your security, please fill out the form below to complete your order.</b><br/>Do not click the refresh or back button or this transaction may be interrupted or cancelled.");
	
	define("CENTINEL_MERCHANT_LOGO",  "..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."merchant_logo.gif");

?>
