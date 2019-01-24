<?php

    session_start();

    include('CentinelConfig.php');

    if (!isset($_SESSION['Centinel_OrderId']) && !isset($_SESSION['Centinel_TransactionId'])){

        $_SESSION['Message'] ='Order Already Processed, User Hit the Back Button';
        print "\n<meta http-equiv=\"refresh\" content=\"0;URL=./ccStart.php\">\n";	
        
    } else {

        print"<html>";
        print"<head>";
	    print"<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\" />";
        print"<title>Centinel - Transaction Results Page</title>";
        print"<body>";

        include("ccMenu.php");

        print"<br/>";
        print"<b>Transaction Results Page</b>";
        print"<br/>";
        print"Intended to simulate an order confirmation page.";
        if (isset($_SESSION["Message"])){
            print"<br>";
            print"<font color=\"red\"><b>Sample Message :".$_SESSION["Message"]."</b></font>";
            print"<br /><br />";
        }
?>
<table>
    <?php
        if( isset($_SESSION["Centinel_cmpiMessageResp"]) ) {
        
            $fields = $_SESSION["Centinel_cmpiMessageResp"];
            foreach($fields as $key => $value) {
                if($key != "") {
                    echo "<tr>\n";
                    echo "\t<td><b>$key</b></td>\n";
                    echo "\t<td> : </td>\n";
                    echo "\t<td>$value</td>\n";
                    echo "</tr>\n";
                }
            }

        } else if( isset($_SESSION["Centinel_ErrorNo"]) || isset($_SESSION["Centinel_ErrorDesc"]) ) {

            echo "<tr>\n";
            echo "\t<td><b>ErrorNo</b></td>\n";
            echo "\t<td> : </td>\n";
            echo "\t<td>{$_SESSION['Centinel_ErrorNo']}</td>\n";
            echo "</tr>\n";

            echo "<tr>\n";
            echo "\t<td><b>ErrorDesc</b></td>\n";
            echo "\t<td> : </td>\n";
            echo "\t<td>{$_SESSION['Centinel_ErrorDesc']}</td>\n";
            echo "</tr>\n";

        } else {

            echo "<tr>\n";
            echo "\t<td><b>ErrorNo</b></td>\n";
            echo "\t<td> : </td>\n";
            echo "\t<td>&nbsp;</td>\n";
            echo "</tr>\n";

            echo "<tr>\n";
            echo "\t<td><b>ErrorDesc</b></td>\n";
            echo "\t<td> : </td>\n";
            echo "\t<td>No response data found in session!</td>\n";
            echo "</tr>\n";
        } 
    ?>
</table>
<?php
        /////////////////////////////////////////////////////////////////////////////////////////////
        // Remove the Session values once the Transaction is complete.
        // Unset all of the session variables and destroy the session.
        /////////////////////////////////////////////////////////////////////////////////////////////

        session_unset();
        session_destroy();

        print"</body>";
        print"</head>";
        print"</html>";

    }  // end else
?>
