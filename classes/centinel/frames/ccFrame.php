<?php
	require(".." . DIRECTORY_SEPARATOR . "CentinelConfig.php");

    session_start(); // start session

?>
<html>
    <head>
        <title>Processing Your Transaction</title>

        <?php
            /////////////////////////////////////////////////////////////////////////////////////////////
            // Check the transaction Id value to verify that this transaction has not already
            // been processed. This attempts to block the user from using the back button.
            /////////////////////////////////////////////////////////////////////////////////////////////

            if (!isset($_SESSION["Centinel_TransactionId"])){
            
                $_SESSION["Message"] = "Order Already Processed, User Hit the Back Button";
                echo "\n<meta http-equiv=\"refresh\" content=\"0;URL=..\ccStart.php\">\n";
            }
        ?>
    </head>

    <body>
        <img src="<?php echo CENTINEL_MERCHANT_LOGO; ?>"/>
        <hr /><br />
        <center>
            <iframe frameborder="0" width='400' height='400' scrolling='auto' src='ccLaunch.php'>
				Frames are currently disabled or not supported by your browser.  Please click <A HREF="ccLaunch.asp">here</A> to continue processing your transaction.
            </iframe>
        </center>
    </body>
</html>
