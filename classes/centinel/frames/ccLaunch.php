<?php

 /************************************************************************************************/
 /*                                                                                              */
 /*CardinalCommerce (http://www.cardinalcommerce.com)                                            */
 /*ccLaunch.php                                                                                  */
 /*                                                                                              */
 /*Usage                                                                                         */
 /*   Form used to POST the payer authentication request to the Card Issuer's Access Control     */
 /*   Server (ACS).                                                                              */
 /*   The ACS will in turn display the payer authentication window to the consumer within        */
 /*   this area.                                                                                 */
 /*                                                                                              */
 /*   Note that the form field names below are CASE SENSITIVE, and all form fields listed below  */
 /*   are required. For additional information please see the merchant integration documentation.*/
 /*                                                                                              */
 /************************************************************************************************/


session_start();

require(".." . DIRECTORY_SEPARATOR . "CentinelConfig.php");

/*******************************************************************************************/
/*                                                                                         */
/*Note that the MD value is available for session management if required. Any value that is*/
/*passed thru the MD form field will be available from the TermUrl to restablish session.  */
/*                                                                                         */
/*******************************************************************************************/

$AcsUrl     = $_SESSION["Centinel_ACSUrl"];
$PaReq      = $_SESSION["Centinel_Payload"];
$TermUrl    = CENTINEL_TERM_URL;
$MD         = "Merchant data";
?>

<html>
    <head>
        <title>Launch Payer Authentication Page</title>
        <script language="javascript">
            function onLoadHandler(){
                document.frmLaunchACS.submit();
            }
        </script>
    </head>

    <body onLoad="onLoadHandler();">
    <br/><br/><br/><br/>
    <center>
    <form name="frmLaunchACS" method="Post" action="<?php echo $AcsUrl; ?>">
        <input type=hidden name="PaReq" value="<?php echo $PaReq; ?>">
        <input type=hidden name="TermUrl" value="<?php echo $TermUrl; ?>">
        <input type=hidden name="MD" value="<?php echo $MD; ?>">
        <noscript> 
        <center> 
            <font color="red"> 
                <h2>Processing your Payer Authentication Transaction</h2> 
                <h3>JavaScript is currently disabled or is not supported by your browser.<br></h3> 
                <h4>Please click Submit to continue the processing of your transaction.</h4> 
            </font> 
        <input type="submit" value="Submit"> 
        </center> 
        </noscript> 
    </form>
    </center>
    </body>
</html>
