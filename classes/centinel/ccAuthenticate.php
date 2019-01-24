<?php

    session_start();

    require('CentinelClient.php');
    require('CentinelConfig.php');
    require('CentinelUtility.php');

    $pares         = $_POST['PaRes'];
    $merchant_data = $_POST['MD'];

    /******************************************************************************/
    /*                                                                            */
    /*    If the PaRes is Not Empty then process the cmpi_authenticate message    */
    /*                                                                            */
    /******************************************************************************/

    if (strcasecmp('', $pares )!= 0 && $pares != null) {

        $centinelClient = new CentinelClient;

        $centinelClient->add('MsgType', 'cmpi_authenticate');
        $centinelClient->add('Version', CENTINEL_MSG_VERSION);
        $centinelClient->add('MerchantId', CENTINEL_MERCHANT_ID);
        $centinelClient->add('ProcessorId', CENTINEL_PROCESSOR_ID);
        $centinelClient->add('TransactionPwd', CENTINEL_TRANSACTION_PWD);
        $centinelClient->add('TransactionType', $_SESSION['Centinel_TransactionType']);
        $centinelClient->add('OrderId', $_SESSION['Centinel_OrderId']);
        $centinelClient->add('TransactionId', $_SESSION['Centinel_TransactionId']);
        $centinelClient->add('PAResPayload', $pares);

        $centinelClient->sendHttp(CENTINEL_MAPS_URL, CENTINEL_TIMEOUT_CONNECT, CENTINEL_TIMEOUT_READ);

        $_SESSION["Centinel_cmpiMessageResp"]       = $centinelClient->response; // Save authenticate response in session
        $_SESSION["Centinel_PAResStatus"]           = $centinelClient->getValue("PAResStatus");
        $_SESSION["Centinel_SignatureVerification"] = $centinelClient->getValue("SignatureVerification");
        $_SESSION["Centinel_ErrorNo"]               = $centinelClient->getValue("ErrorNo");
        $_SESSION["Centinel_ErrorDesc"]             = $centinelClient->getValue("ErrorDesc");

    } else {

        $_SESSION["Centinel_ErrorNo"]   = "0";
        $_SESSION["Centinel_ErrorDesc"] = "NO PARES RETURNED";
    }

    /******************************************************************************/
    /*                                                                            */
    /*                  Determine if the transaction resulted in                  */
    /*                  an error.                                                 */
    /*                                                                            */
    /******************************************************************************/
    $redirectPage = "ccStart.php";

        
    if( (strcasecmp('Y', $_SESSION['Centinel_PAResStatus']) == 0 || strcasecmp('A', $_SESSION['Centinel_PAResStatus']) == 0) && (strcasecmp('Y', $_SESSION['Centinel_SignatureVerification']) == 0) && (strcasecmp('0', $_SESSION['Centinel_ErrorNo']) == 0 || strcasecmp('1140', $_SESSION['Centinel_ErrorNo']) == 0) ) {
        // Transaction completed successfully. 
		$_SESSION["Message"] = "Transaction completed successfully. (ErrorNo: [{$_SESSION['Centinel_ErrorNo']}], ErrorDesc: [{$_SESSION['Centinel_ErrorDesc']}])";
        $redirectPage = 'ccResults.php';
        
    } else if( (strcasecmp('N', $_SESSION['Centinel_PAResStatus']) == 0) && (strcasecmp('Y', $_SESSION['Centinel_SignatureVerification']) == 0) && (strcasecmp('0', $_SESSION['Centinel_ErrorNo']) == 0 || strcasecmp('1140', $_SESSION['Centinel_ErrorNo']) == 0) ) {
        // Unable to authenticate. Provide another form of payment. 
		$_SESSION["Message"] = "Unable to authenticate. Provide another form of payment. (ErrorNo: [{$_SESSION['Centinel_ErrorNo']}], ErrorDesc: [{$_SESSION['Centinel_ErrorDesc']}])";
        $redirectPage = 'ccResults.php';
    } else {
        // Transaction complete however is pending review. Order will be shipped once payment is verified. 
		$_SESSION["Message"] = "Transaction complete however is pending review. Order will be shipped once payment is verified. (ErrorNo: [{$_SESSION['Centinel_ErrorNo']}], ErrorDesc: [{$_SESSION['Centinel_ErrorDesc']}])";
        $redirectPage = 'ccResults.php';

    } // end processing logic
?>
<html>
<head>
<title>Centinel - ccAuthenticate Page</title>
</head>
<body onload="document.frmResultPage.submit();"></body>
<form name="frmResultPage" method="POST" action="<?php echo $redirectPage; ?>" target="_parent">
<noscript>
    <br><br>
    <center>
    <font color="red">
    <h1>Processing Your Transaction</h1>
    <h2>JavaScript is currently disabled or is not supported by your browser.<br></h2>
    <h3>Please click Submit to continue the processing of your transaction.</h3>
    </font>
    <input type="submit" value="Submit">
    </center>
</noscript>
</form>
</html>
