<?php
/******************************************************************************************************/
/*                                                                                                    */
/*Cardinal Commerce (http://www.cardinalcommerce.com)                                                 */
/*                                                                                                    */
/*Usage                                                                                               */
/*    A sample or template integration of the Centinel Thin Client. The sample follows a basic inline*/
/*    authentication approach and provides the sample that utilizes the Thin Client for communication */
/*    with the MAPS servers.                                                                          */
/*                                                                                                    */
/*Transaction API                                                                                     */
/*    Please reference the current transaction API documentation for a complete list of               */
/*    all required and optional message elements.                                                     */
/*                                                                                                    */
/******************************************************************************************************/

session_start();
?>

<html>
<head>
    <title>Centinel - Start Page</title>
	<link rel="stylesheet" type="text/css" href="style.css" />
    
    <script type="text/javascript" src="util.js"></script>
    
    <script type="text/javascript">
        window.onload = function() {
            document.frm.order_number.value = randomOrderNumber();
            document.frm.Item_Price_1.value = randomAmount();
            document.frm.Item_Price_2.value = randomAmount();
            document.frm.amount.value = parseInt(document.frm.Item_Price_1.value) + parseInt(document.frm.Item_Price_2.value);
            document.frm.onsubmit = singleSubmit("frm");

            // Dynamic country / state functionality
            document.forms["frm"].b_country_code.onchange = function() { countryCodeMonitor(); }
            document.forms["frm"].s_country_code.onchange = function() { countryCodeMonitor(); }
            document.getElementById("s_state_field").innerHTML = getStateSelectionHTML("s_state");
            document.getElementById("b_state_field").innerHTML = getStateSelectionHTML("b_state");

                            document.forms["frm"].card_number_selector.onchange = 
                function() {
                    document.getElementById("card_number").value = document.getElementById("card_number_selector").value;
                }
            build3DSDateOptions();


        } // end onLoad
    </script>


</head>
<body>

<?php
    include ("ccMenu.php");

    echo"<br/>";
    echo"<b>Lookup Transaction Form</b>";
    echo"<br/>";
    echo"This form is intended to simulate your payment page within your ecommerce website.";
    echo"<br/>All payment information is collected, and clicking the submit button at the bottom of the page simulates the consumer clicking the final buy button.";
    echo"<br/>";
    if (isset($_SESSION["Message"])){
        echo"<br>";
        echo"<font color=\"red\"><b>Sample Message :".$_SESSION["Message"]."</b></font>";
        echo"<br>";
    }
    echo"<br>";
?>

    <form name="frm"method=post action="ccLookup.php">
        <table>
        <tr>
            <td bgcolor="ffff40">Transaction Type</td><td>
            <select name="txn_type">
                <option value="C">C - Payer Authentication
            </select></td>
            <td>&nbsp;</td><td></td>
        </tr>

        <tr><td>&nbsp;</td><td></td><td>&nbsp;</td><td></td></tr><tr><td bgcolor="ffff40">Card Number</td>
	<td id="card_number_field">
		<input id="card_number" type="text" name="card_number" value="4000000000000002" style="null"/>
	</td>
<td>Test Case</td>
	<td id="card_number_selector_field">
		<select id="card_number_selector" name="card_number_selector" style="width: 25EM;">
			
                <option value="">Select PAN</option>
                <option value="4000000000000002">4000000000000002 - Y,Y,Y</option>
                <option value="5200000000000007">5200000000000007 - Y,Y,Y</option>
                <option value="3000000000000004">3000000000000004 - Y,Y,Y</option>
                <option value="1000000000000001">1000000000000001 - Error</option>
                <option value="4000000000000010">4000000000000010 - Y,Y,N</option>
                <option value="5200000000000015">5200000000000015 - Y,Y,N</option>
                <option value="3000000000000012">3000000000000012 - Y,Y,N</option>
                <option value="4000000000000028">4000000000000028 - Y,N,Y</option>
                <option value="3000000000000020">3000000000000020 - Y,N,Y</option>
                <option value="5200000000000023">5200000000000023 - Y,N,Y</option>
                <option value="4000000000000101">4000000000000101 - Y,A,Y</option>
                <option value="5200000000000106">5200000000000106 - Y,A,Y</option>
                <option value="180000000000028">  180000000000028 - Y,A,Y</option>
                <option value="4000000000000036">4000000000000036 - Y,U</option>
                <option value="5200000000000031">5200000000000031 - Y,U</option>
                <option value="3000000000000038">3000000000000038 - Y,U</option>
                <option value="4000000000000044">4000000000000044 - Timeout Test - Lookup</option>
                <option value="4000000000000259">4000000000000259 - Timeout Test - Authenticate</option>
                <option value="5200000000000049">5200000000000049 - Timeout Test - Lookup</option>
                <option value="5200000000000452">5200000000000452 - Timeout Test - Authenticate</option>
                <option value="213100000000001">  213100000000001 - Timeout Test - Lookup</option>
                <option value="3000000000000376">3000000000000376 - Timeout Test - Authenticate</option>
                <option value="4000000000000051">4000000000000051 - N</option>
                <option value="5200000000000056">5200000000000056 - N</option>
                <option value="213100000000019">  213100000000019 - N</option>
                <option value="4000000000000069">4000000000000069 - U</option>
                <option value="5200000000000064">5200000000000064 - U</option>
                <option value="213100000000027">  213100000000027 - U</option>
                <option value="4000000000000077">4000000000000077 - Error</option>
                <option value="5200000000000072">5200000000000072 - Error</option>
                <option value="213100000000035">  213100000000035 - Error</option>
                <option value="4000000000000085">4000000000000085 - Error</option>
                <option value="5200000000000080">5200000000000080 - Error</option>
                <option value="180000000000002">  180000000000002 - Error</option>
                <option value="4000000000000093">4000000000000093 - Y, Error</option>
                <option value="5200000000000098">5200000000000098 - Y, Error</option>
                <option value="180000000000010">  180000000000010 - Y, Error</option>
                <option value="4000000400000093">4000000400000093 - Not Test Card</option>
                <option value="5200000400000098">5200000400000098 - Not Test Card</option>
                <option value="180000040000010">  180000040000010 - Not Test Card</option>
                <option value="4000000000000432">4000000000000432 - Y,A,N</option>
                <option value="4000000000000606">4000000000000606 - Y,U,N</option>
                <option value="4000000000000838">4000000000000838 - Y,N,N</option>
                <option value="5200000000000221">5200000000000221 - Y,A,N</option>
                <option value="5200000000000270">5200000000000270 - Y,U,N</option>
                <option value="5200000000000148">5200000000000148 - Y,N,N</option>
                <option value="3000000000000657">3000000000000657 - Y,A,N</option>
                <option value="3000000000000962">3000000000000962 - Y,U,N</option>
                <option value="3000000000000210">3000000000000210 - Y,N,N</option>
		</select>
	</td></tr><tr><td bgcolor="ffff40">Expiration Month</td>
	<td id="expr_mm_field">
		<select id="expr_mm" name="expr_mm" style="width: 125px;">
			
                <option value="01">01</option>
                <option value="02">02</option>
                <option value="03">03</option>
                <option value="04">04</option>
                <option value="05">05</option>
                <option value="06">06</option>
                <option value="07">07</option>
                <option value="08">08</option>
                <option value="09">09</option>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
		</select>
	</td><td bgcolor="ffff40">Expiration Year</td>
	<td id="expr_yyyy_field">
		<select id="expr_yyyy" name="expr_yyyy" style="width: 125px;">
			null
		</select>
	</td></tr><tr><td>Acquirer Password</td>
	<td id="acquirer_password_field">
		<input id="acquirer_password" type="text" name="acquirer_password" value="" style="null"/>
	</td>


<tr><td>&nbsp;</td><td></td><td>&nbsp;</td><td></td></tr><tr><td bgcolor="ffff40">Order Number</td>
	<td id="order_number_field">
		<input id="order_number" type="text" name="order_number" value="" style="null"/>
	</td>
<td>Order Description</td>
	<td id="order_description_field">
		<input id="order_description" type="text" name="order_description" value="Sample Order..." style="null"/>
	</td>
</tr><tr><td bgcolor="ffff40">Amount</td>
	<td id="amount_field">
		<input id="amount" type="text" name="amount" value="" style="null"/>
	</td>
<td bgcolor="ffff40">Currency Code</td>
	<td id="currency_code_field">
		<select id="currency_code" name="currency_code" style="width: 125px;">
			
<option value ="840">840 - USD</option>
<option value ="036">036 - AUD </option>
<option value ="124">124 - CAD</option>
<option value ="978">978 - EUR</option>
<option value ="826">826 - GBP</option>
<option value ="392">392 - JPY</option>
<option value ="203">203 - CZK</option>
<option value ="208">208 - DKK</option>
<option value ="344">344 - HKD</option>
<option value ="348">348 - HUF</option>
<option value ="376">376 - ILS</option>
<option value ="484">484 - MXN</option>
<option value ="578">578 - NOK</option>
<option value ="554">554 - NZD</option>
<option value ="985">985 - PLN</option>
<option value ="702">702 - SGD</option>
<option value ="752">752 - SEK</option>
<option value ="756">756 - CHF</option>

		</select>
	</td></tr><tr><td>Shipping Amount</td>
	<td id="shipping_amount_field">
		<input id="shipping_amount" type="text" name="shipping_amount" value="" style="null"/>
	</td>
<td>Tax Amount</td>
	<td id="tax_amount_field">
		<input id="tax_amount" type="text" name="tax_amount" value="" style="null"/>
	</td>
</tr><tr><td>Gift Card Amount</td>
	<td id="gift_card_amount_field">
		<input id="gift_card_amount" type="text" name="gift_card_amount" value="" style="null"/>
	</td>
<td>Category Code</td>
	<td id="category_code_field">
		<input id="category_code" type="text" name="category_code" value="" style="null"/>
	</td>
</tr><tr><td>Merchant Data</td>
	<td id="merchant_data_field">
		<input id="merchant_data" type="text" name="merchant_data" value="" style="null"/>
	</td>
<td bgcolor="ffff40">Order Channel</td>
	<td id="order_channel_field">
		<select id="order_channel" name="order_channel" style="width: 125px;">
			
<option value="MARK">MARK - Mark</option>
<option value="CART" >CART - Cart</option>
<option value="CALLCENTER" >CALLCENTER - Call Center</option>
<option value="WIDGET" >WIDGET - Widget</option>
<option value="PRODUCT" >PRODUCT - Product</option>
<option value="1CLICK" >1CLICK - 1 Click</option>

		</select>
	</td></tr><tr><td bgcolor="ffff40">Product Code</td>
	<td id="product_code_field">
		<select id="product_code" name="product_code" style="width: 125px;">
			
<option value="PHY">PHY - Physical Delivery</option>
<option value="CNC">CNC - Cash and Carry</option>
<option value="DIG">DIG - Digital Good</option>
<option value="SVC">SVC - Service</option>
<option value="TBD">TBD - Other</option>

		</select>
	</td><td bgcolor="ffff40">Transaction Mode</td>
	<td id="transaction_mode_field">
		<select id="transaction_mode" name="transaction_mode" style="width: 125px;">
			
<option value="S">S - E-Commerce</option>
<option value="M">M - Call Center</option>
<option value="R">R - Retail</option>

		</select>
	</td></tr>

<tr><td>&nbsp;</td><td></td><td>&nbsp;</td><td></td></tr><tr><td bgcolor="ffff40">Recurring</td>
	<td id="recurring_field">
		<select id="recurring" name="recurring" style="width: 125px;">
			
               <option value="N">N - No</option>
               <option value="Y">Y - Yes</option>
               <option value="AR">AR - Auto Recurring</option>
		</select>
	</td><td>Recurring Frequency</td>
	<td id="recurring_frequency_field">
		<input id="recurring_frequency" type="text" name="recurring_frequency" value="" style="null"/>
	</td>
</tr><tr><td>Recurring End</td>
	<td id="recurring_end_field">
		<input id="recurring_end" type="text" name="recurring_end" value="" style="null"/>
	</td>
<td>Installment</td>
	<td id="installment_field">
		<input id="installment" type="text" name="installment" value="" style="null"/>
	</td>
</tr>

<tr><td>&nbsp;</td><td></td><td>&nbsp;</td><td></td></tr><tr><td bgcolor="ffff40">Billing First Name</td>
	<td id="b_first_name_field">
		<input id="b_first_name" type="text" name="b_first_name" value="John" style="null"/>
	</td>
<td bgcolor="ffff40">Shipping First Name</td>
	<td id="s_first_name_field">
		<input id="s_first_name" type="text" name="s_first_name" value="John" style="null"/>
	</td>
</tr><tr><td>Billing Middle Name</td>
	<td id="b_middle_name_field">
		<input id="b_middle_name" type="text" name="b_middle_name" value="" style="null"/>
	</td>
<td>Shipping Middle Name</td>
	<td id="s_middle_name_field">
		<input id="s_middle_name" type="text" name="s_middle_name" value="" style="null"/>
	</td>
</tr><tr><td bgcolor="ffff40">Billing Last Name</td>
	<td id="b_last_name_field">
		<input id="b_last_name" type="text" name="b_last_name" value="Consumer" style="null"/>
	</td>
<td bgcolor="ffff40">Shipping Last Name</td>
	<td id="s_last_name_field">
		<input id="s_last_name" type="text" name="s_last_name" value="Consumer" style="null"/>
	</td>
</tr><tr><td bgcolor="ffff40">Billing Address1</td>
	<td id="b_address1_field">
		<input id="b_address1" type="text" name="b_address1" value="1234 Main St." style="null"/>
	</td>
<td bgcolor="ffff40">Shipping Address1</td>
	<td id="s_address1_field">
		<input id="s_address1" type="text" name="s_address1" value="1234 Main St." style="null"/>
	</td>
</tr><tr><td>Billing Address2</td>
	<td id="b_address2_field">
		<input id="b_address2" type="text" name="b_address2" value="" style="null"/>
	</td>
<td>Shipping Address2</td>
	<td id="s_address2_field">
		<input id="s_address2" type="text" name="s_address2" value="" style="null"/>
	</td>
</tr><tr><td bgcolor="ffff40">Billing City</td>
	<td id="b_city_field">
		<input id="b_city" type="text" name="b_city" value="Mentor" style="null"/>
	</td>
<td bgcolor="ffff40">Shipping City</td>
	<td id="s_city_field">
		<input id="s_city" type="text" name="s_city" value="Mentor" style="null"/>
	</td>
</tr><tr><td bgcolor="ffff40">Billing State</td>
	<td id="b_state_field">
		<select id="b_state" name="b_state" style="width: 125px;">
			
                <option value="">(No selection)</option>
                <option value="AK">AK - Alaska</option>
                <option value="AL">AL - Alabama</option>
                <option value="AR">AR - Arkansas</option>
                <option value="AZ">AZ - Arizona</option>
                <option value="CA">CA - California</option>
                <option value="CO">CO - Colorado</option>
                <option value="CT">CT - Connecticut</option>
                <option value="DC">DC - District Of Columbia</option>
                <option value="DE">DE - Delaware</option>
                <option value="FL">FL - Florida</option>
                <option value="GA">GA - Georgia</option>
                <option value="HI">HI - Hawaii</option>
                <option value="IA">IA - Iowa</option>
                <option value="ID">ID - Idaho</option>
                <option value="IL">IL - Illinois</option>
                <option value="IN">IN - Indiana</option>
                <option value="KS">KS - Kansas</option>
                <option value="KY">KY - Kentucky</option>
                <option value="LA">LA - Louisiana</option>
                <option value="MA">MA - Massachusetts</option>
                <option value="MD">MD - Maryland</option>
                <option value="ME">ME - Maine</option>
                <option value="MI">MI - Michigan</option>
                <option value="MN">MN - Minnesota</option>
                <option value="MO">MO - Missouri</option>
                <option value="MS">MS - Mississippi</option>
                <option value="MT">MT - Montana</option>
                <option value="NC">NC - North Carolina</option>
                <option value="ND">ND - North Dakota</option>
                <option value="NE">NE - Nebraska</option>
                <option value="NH">NH - New Hampshire</option>
                <option value="NJ">NJ - New Jersey</option>
                <option value="NM">NM - New Mexico</option>
                <option value="NV">NV - Nevada</option>
                <option value="NY">NY - New York</option>
                <option value="OH" selected="selected">OH - Ohio</option>
                <option value="OK">OK - Oklahoma</option>
                <option value="OR">OR - Oregon</option>
                <option value="PA">PA - Pennsylvania</option>
                <option value="RI">RI - Rhode Island</option>
                <option value="SC">SC - South Carolina</option>
                <option value="SD">SD - South Dakota</option>
                <option value="TN">TN - Tennessee</option>
                <option value="TX">TX - Texas</option>
                <option value="UT">UT - Utah</option>
                <option value="VA">VA - Virginia</option>
                <option value="VT">VT - Vermont</option>
                <option value="WA">WA - Washington</option>
                <option value="WI">WI - Wisconsin</option>
                <option value="WV">WV - West Virginia</option>
                <option value="WY">WY - Wyoming</option>
		</select>
	</td><td bgcolor="ffff40">Shipping State</td>
	<td id="s_state_field">
		<select id="s_state" name="s_state" style="width: 125px;">
			
                <option value="">(No selection)</option>
                <option value="AK">AK - Alaska</option>
                <option value="AL">AL - Alabama</option>
                <option value="AR">AR - Arkansas</option>
                <option value="AZ">AZ - Arizona</option>
                <option value="CA">CA - California</option>
                <option value="CO">CO - Colorado</option>
                <option value="CT">CT - Connecticut</option>
                <option value="DC">DC - District Of Columbia</option>
                <option value="DE">DE - Delaware</option>
                <option value="FL">FL - Florida</option>
                <option value="GA">GA - Georgia</option>
                <option value="HI">HI - Hawaii</option>
                <option value="IA">IA - Iowa</option>
                <option value="ID">ID - Idaho</option>
                <option value="IL">IL - Illinois</option>
                <option value="IN">IN - Indiana</option>
                <option value="KS">KS - Kansas</option>
                <option value="KY">KY - Kentucky</option>
                <option value="LA">LA - Louisiana</option>
                <option value="MA">MA - Massachusetts</option>
                <option value="MD">MD - Maryland</option>
                <option value="ME">ME - Maine</option>
                <option value="MI">MI - Michigan</option>
                <option value="MN">MN - Minnesota</option>
                <option value="MO">MO - Missouri</option>
                <option value="MS">MS - Mississippi</option>
                <option value="MT">MT - Montana</option>
                <option value="NC">NC - North Carolina</option>
                <option value="ND">ND - North Dakota</option>
                <option value="NE">NE - Nebraska</option>
                <option value="NH">NH - New Hampshire</option>
                <option value="NJ">NJ - New Jersey</option>
                <option value="NM">NM - New Mexico</option>
                <option value="NV">NV - Nevada</option>
                <option value="NY">NY - New York</option>
                <option value="OH" selected="selected">OH - Ohio</option>
                <option value="OK">OK - Oklahoma</option>
                <option value="OR">OR - Oregon</option>
                <option value="PA">PA - Pennsylvania</option>
                <option value="RI">RI - Rhode Island</option>
                <option value="SC">SC - South Carolina</option>
                <option value="SD">SD - South Dakota</option>
                <option value="TN">TN - Tennessee</option>
                <option value="TX">TX - Texas</option>
                <option value="UT">UT - Utah</option>
                <option value="VA">VA - Virginia</option>
                <option value="VT">VT - Vermont</option>
                <option value="WA">WA - Washington</option>
                <option value="WI">WI - Wisconsin</option>
                <option value="WV">WV - West Virginia</option>
                <option value="WY">WY - Wyoming</option>
		</select>
	</td></tr><tr><td bgcolor="ffff40">Billing Postal</td>
	<td id="b_postal_code_field">
		<input id="b_postal_code" type="text" name="b_postal_code" value="44060" style="null"/>
	</td>
<td bgcolor="ffff40">Shipping Postal</td>
	<td id="s_postal_code_field">
		<input id="s_postal_code" type="text" name="s_postal_code" value="44060" style="null"/>
	</td>
</tr><tr><td bgcolor="ffff40">Billing Country Code</td>
	<td id="b_country_code_field">
		<select id="b_country_code" name="b_country_code" style="width: 125px;">
			
                <option value="">(No selection)</option>
                <option value="US" selected="selected">US - United States</option>
                <option value="AT">AT - Austria</option>
                <option value="BE">BE - Belgium</option>
                <option value="CA">CA - Canada</option>
                <option value="CH">CH - Switzerland</option>
                <option value="CZ">CZ - Czech Republic</option>
                <option value="DE">DE - Germany</option>
                <option value="DK">DK - Denmark</option>
                <option value="ES">ES - Spain</option>
                <option value="FI">FI - Finland</option>
                <option value="FR">FR - France</option>
                <option value="GB">GB - United Kingdom</option>
                <option value="HK">HK - Hong Kong</option>
                <option value="HU">HU - Hungary</option>
                <option value="IE">IE - Ireland</option>
                <option value="IL">IL - Israel</option>
                <option value="IT">IT - Italy</option>
                <option value="JP">JP - Japan</option>
                <option value="MX">MX - Mexico</option>
                <option value="NL">NL - Netherlands</option>
                <option value="NO">NO - Norway</option>
                <option value="NZ">NZ - New Zealand</option>
                <option value="PL">PL - Poland</option>
                <option value="PT">PT - Portugal</option>
                <option value="SE">SE - Sweden</option>
                <option value="SG">SG - Singapore</option>
		</select>
	</td><td bgcolor="ffff40">Shipping Country Code</td>
	<td id="s_country_code_field">
		<select id="s_country_code" name="s_country_code" style="width: 125px;">
			
                <option value="">(No selection)</option>
                <option value="US" selected="selected">US - United States</option>
                <option value="AT">AT - Austria</option>
                <option value="BE">BE - Belgium</option>
                <option value="CA">CA - Canada</option>
                <option value="CH">CH - Switzerland</option>
                <option value="CZ">CZ - Czech Republic</option>
                <option value="DE">DE - Germany</option>
                <option value="DK">DK - Denmark</option>
                <option value="ES">ES - Spain</option>
                <option value="FI">FI - Finland</option>
                <option value="FR">FR - France</option>
                <option value="GB">GB - United Kingdom</option>
                <option value="HK">HK - Hong Kong</option>
                <option value="HU">HU - Hungary</option>
                <option value="IE">IE - Ireland</option>
                <option value="IL">IL - Israel</option>
                <option value="IT">IT - Italy</option>
                <option value="JP">JP - Japan</option>
                <option value="MX">MX - Mexico</option>
                <option value="NL">NL - Netherlands</option>
                <option value="NO">NO - Norway</option>
                <option value="NZ">NZ - New Zealand</option>
                <option value="PL">PL - Poland</option>
                <option value="PT">PT - Portugal</option>
                <option value="SE">SE - Sweden</option>
                <option value="SG">SG - Singapore</option>
		</select>
	</td></tr><tr><td bgcolor="ffff40">Billing Phone</td>
	<td id="b_phone_field">
		<input id="b_phone" type="text" name="b_phone" value="2162162116" style="null"/>
	</td>
<td bgcolor="ffff40">Shipping Phone</td>
	<td id="s_phone_field">
		<input id="s_phone" type="text" name="s_phone" value="2162162116" style="null"/>
	</td>
</tr><tr><td bgcolor="ffff40">Buyer Email</td>
	<td id="email_address_field">
		<input id="email_address" type="text" name="email_address" value="johnconsumer@consumerdomain.com" style="null"/>
	</td>


<tr><td>&nbsp;</td><td></td><td>&nbsp;</td><td></td></tr><tr><td bgcolor="ffff40">Item 1 Name</td>
	<td id="Item_Name_1_field">
		<input id="Item_Name_1" type="text" name="Item_Name_1" value="2GB MP3 Player" style="null"/>
	</td>
<td>Item 2 Name</td>
	<td id="Item_Name_2_field">
		<input id="Item_Name_2" type="text" name="Item_Name_2" value="1TB Hard Drive" style="null"/>
	</td>
</tr><tr><td bgcolor="ffff40">Item 1 SKU</td>
	<td id="Item_SKU_1_field">
		<input id="Item_SKU_1" type="text" name="Item_SKU_1" value="123456" style="null"/>
	</td>
<td>Item 2 SKU</td>
	<td id="Item_SKU_2_field">
		<input id="Item_SKU_2" type="text" name="Item_SKU_2" value="789012" style="null"/>
	</td>
</tr><tr><td bgcolor="ffff40">Item 1 Price</td>
	<td id="Item_Price_1_field">
		<input id="Item_Price_1" type="text" name="Item_Price_1" value="3999" style="null"/>
	</td>
<td>Item 2 Price</td>
	<td id="Item_Price_2_field">
		<input id="Item_Price_2" type="text" name="Item_Price_2" value="19999" style="null"/>
	</td>
</tr><tr><td bgcolor="ffff40">Item 1 Quantity</td>
	<td id="Item_Quantity_1_field">
		<input id="Item_Quantity_1" type="text" name="Item_Quantity_1" value="1" style="null"/>
	</td>
<td>Item 2 Quantity</td>
	<td id="Item_Quantity_2_field">
		<input id="Item_Quantity_2" type="text" name="Item_Quantity_2" value="1" style="null"/>
	</td>
</tr><tr><td bgcolor="ffff40">Item 1 Description</td>
	<td id="Item_Desc_1_field">
		<input id="Item_Desc_1" type="text" name="Item_Desc_1" value="The simple MP3 player" style="null"/>
	</td>
<td>Item 2 Description</td>
	<td id="Item_Desc_2_field">
		<input id="Item_Desc_2" type="text" name="Item_Desc_2" value="One TB Hard Disk Drive" style="null"/>
	</td>
</tr>





        <tr>
            <td>&nbsp;</td><td></td>
            <td>&nbsp;</td><td></td>
        </tr>

        <tr>
          <td></td>
          <TD colspan="" align="center"><input type=submit name="submit" value="Submit Order"></td>
        </tr>
        <tr>
          <TD colspan="2"><br><b><i>Required fields highlighted</i></b></td>
        </tr>
        </table>
    </form>

</body>
</html>
