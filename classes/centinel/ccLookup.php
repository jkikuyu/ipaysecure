<?php
    require('CentinelClient.php');
    require('CentinelConfig.php');
    require('CentinelUtility.php');


    session_start();
    clearCentinelSession();


    /*******************************************************************************/
    /*                                                                             */
    /*Using the local variables and constants, build the Centinel message using the*/
    /*Centinel Thin Client.                                                        */
    /*                                                                             */
    /*******************************************************************************/

    $centinelClient = new CentinelClient;

	$centinelClient->add("MsgType", "cmpi_lookup");
	$centinelClient->add("Version", CENTINEL_MSG_VERSION);
	$centinelClient->add("ProcessorId", CENTINEL_PROCESSOR_ID);
	$centinelClient->add("MerchantId", CENTINEL_MERCHANT_ID);
	$centinelClient->add("TransactionPwd", CENTINEL_TRANSACTION_PWD);
	$centinelClient->add("UserAgent", $_SERVER["HTTP_USER_AGENT"]);
	$centinelClient->add("BrowserHeader", $_SERVER["HTTP_ACCEPT"]);
	$centinelClient->add("TransactionType", $_POST["txn_type"]);
//    $centinelClient->add('IPAddress', $_SERVER['REMOTE_ADDR']);
    $centinelClient->add('IPAddress', "127.0.0.1");

    // Standard cmpi_lookup fields
    $centinelClient->add('OrderNumber', $_POST['order_number']);
    $centinelClient->add('OrderDescription', $_POST['order_description']);
    $centinelClient->add('Amount', $_POST['amount']);
    $centinelClient->add('CurrencyCode', $_POST['currency_code']);
    $centinelClient->add('ShippingAmount', $_POST['shipping_amount']);
    $centinelClient->add('TaxAmount', $_POST['tax_amount']);
    $centinelClient->add('GiftCardAmount', $_POST['gift_card_amount']);
    $centinelClient->add('CategoryCode', $_POST['category_code']);
    $centinelClient->add('MerchantData', $_POST['merchant_data']);
    $centinelClient->add('OrderChannel', $_POST['order_channel']);
    $centinelClient->add('ProductCode', $_POST['product_code']);
    $centinelClient->add('TransactionMode', $_POST['transaction_mode']);

    $centinelClient->add('BillingFirstName', $_POST['b_first_name']);
    $centinelClient->add('BillingMiddleName', $_POST['b_middle_name']);
    $centinelClient->add('BillingLastName', $_POST['b_last_name']);
    $centinelClient->add('BillingAddress1', $_POST['b_address1']);
    $centinelClient->add('BillingAddress2', $_POST['b_address2']);
    $centinelClient->add('BillingCity', $_POST['b_city']);
    $centinelClient->add('BillingState', $_POST['b_state']);
    $centinelClient->add('BillingPostalCode', $_POST['b_postal_code']);
    $centinelClient->add('BillingCountryCode', $_POST['b_country_code']);
    $centinelClient->add('BillingPhone', $_POST['b_phone']);
    $centinelClient->add('EMail', $_POST['email_address']);

    $centinelClient->add('ShippingFirstName', $_POST['s_first_name']);
    $centinelClient->add('ShippingMiddleName', $_POST['s_middle_name']);
    $centinelClient->add('ShippingLastName', $_POST['s_last_name']);
    $centinelClient->add('ShippingAddress1', $_POST['s_address1']);
    $centinelClient->add('ShippingAddress2', $_POST['s_address2']);
    $centinelClient->add('ShippingCity', $_POST['s_city']);
    $centinelClient->add('ShippingState', $_POST['s_state']);
    $centinelClient->add('ShippingPostalCode', $_POST['s_postal_code']);
    $centinelClient->add('ShippingCountryCode', $_POST['s_country_code']);
    $centinelClient->add('ShippingPhone', $_POST['s_phone']);
    $centinelClient->add('Item_Name_1', $_POST['Item_Name_1']);
    $centinelClient->add('Item_SKU_1', $_POST['Item_SKU_1']);
    $centinelClient->add('Item_Price_1', $_POST['Item_Price_1']);
    $centinelClient->add('Item_Quantity_1', $_POST['Item_Quantity_1']);
    $centinelClient->add('Item_Desc_1', $_POST['Item_Desc_1']);

    $centinelClient->add('Item_Name_2', $_POST['Item_Name_2']);
    $centinelClient->add('Item_SKU_2', $_POST['Item_SKU_2']);
    $centinelClient->add('Item_Price_2', $_POST['Item_Price_2']);
    $centinelClient->add('Item_Quantity_2', $_POST['Item_Quantity_2']);
    $centinelClient->add('Item_Desc_2', $_POST['Item_Desc_2']);


    // Recurring
    $centinelClient->add('Recurring', $_POST['recurring']);
    $centinelClient->add('RecurringFrequency', $_POST['recurring_frequency']);
    $centinelClient->add('RecurringEnd', $_POST['recurring_end']);
    $centinelClient->add('Installment', $_POST['installment']);


    // Payer Authentication specific fields
    $centinelClient->add('CardNumber', $_POST['card_number']);
    $centinelClient->add('CardExpMonth', $_POST['expr_mm']);
    $centinelClient->add('CardExpYear', $_POST['expr_yyyy']);
    $centinelClient->add('Password', $_POST['acquirer_password']);

    /**********************************************************************************/
    /*                                                                                */
    /*Send the XML Msg to the MAPS Server, the Response is the CentinelResponse Object*/
    /*                                                                                */
    /**********************************************************************************/

    $centinelClient->sendHttp(CENTINEL_MAPS_URL, CENTINEL_TIMEOUT_CONNECT, CENTINEL_TIMEOUT_READ);

    // Save response in session
    $_SESSION["Centinel_cmpiMessageResp"]   = $centinelClient->response; // Save lookup response in session
    $_SESSION["Centinel_Enrolled"]          = $centinelClient->getValue("Enrolled");
    $_SESSION["Centinel_TransactionId"]     = $centinelClient->getValue("TransactionId");
    $_SESSION["Centinel_OrderId"]           = $centinelClient->getValue("OrderId");
    $_SESSION["Centinel_ACSUrl"]            = $centinelClient->getValue("ACSUrl");
    $_SESSION["Centinel_Payload"]           = $centinelClient->getValue("Payload");
    $_SESSION["Centinel_ErrorNo"]           = $centinelClient->getValue("ErrorNo");
    $_SESSION["Centinel_ErrorDesc"]         = $centinelClient->getValue("ErrorDesc");

    // Needed for the cmpi_authenticate message
    $_SESSION["Centinel_TransactionType"] = $_POST['txn_type'];

    // Add TermUrl to session
    $_SESSION["Centinel_TermUrl"] = CENTINEL_TERM_URL;

    /******************************************************************************/
    /*                                                                            */
    /*                          Result Processing Logic                           */
    /*                                                                            */
    /******************************************************************************/
        
    if( (strcasecmp('Y', $_SESSION['Centinel_Enrolled']) == 0) && (strcasecmp('0', $_SESSION['Centinel_ErrorNo']) == 0) ) {
        // Proceed with redirect 
		$_SESSION["Message"] = "Proceed with redirect (ErrorNo: [{$_SESSION['Centinel_ErrorNo']}], ErrorDesc: [{$_SESSION['Centinel_ErrorDesc']}])";
        redirectBrowser('frames/ccFrame.php');
        
    } else if( (strcasecmp('N', $_SESSION['Centinel_Enrolled']) == 0) && (strcasecmp('0', $_SESSION['Centinel_ErrorNo']) == 0) ) {
        // Card not enrolled, continue to authorization 
		$_SESSION["Message"] = "Card not enrolled, continue to authorization (ErrorNo: [{$_SESSION['Centinel_ErrorNo']}], ErrorDesc: [{$_SESSION['Centinel_ErrorDesc']}])";
        redirectBrowser('ccResults.php');
        
    } else if( (strcasecmp('U', $_SESSION['Centinel_Enrolled']) == 0) && (strcasecmp('0', $_SESSION['Centinel_ErrorNo']) == 0) ) {
        // Authentication unavailable, continue to authorization 
		$_SESSION["Message"] = "Authentication unavailable, continue to authorization (ErrorNo: [{$_SESSION['Centinel_ErrorNo']}], ErrorDesc: [{$_SESSION['Centinel_ErrorDesc']}])";
        redirectBrowser('http://localhost/cardinal/CardinalConsumerAuthentication/ccResults.php');
    } else {
        // Authentication unable to complete, continue to authorization 
		$_SESSION["Message"] = "Authentication unable to complete, continue to authorization (ErrorNo: [{$_SESSION['Centinel_ErrorNo']}], ErrorDesc: [{$_SESSION['Centinel_ErrorDesc']}])";
        redirectBrowser('cardinal/CardinalConsumerAuthentication/ccResults.php');

    } // end processing logic
?>
