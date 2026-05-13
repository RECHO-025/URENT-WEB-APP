<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Summary by Teller</title>
<script language=JavaScript>

function confirmDelete88() { 
 return confirm("Click OK to Confirm that this Credit has been paid");   
} 

</script>
<script type="text/javascript">     
    function PrintTd() {    
       var tdToPrint = document.getElementById('tdToPrint');
       var popupWin = window.open('', '_blank', 'width=100,height=100');
       popupWin.document.open();
       popupWin.document.write('<html><body onload="window.print()">' + tdToPrint.innerHTML + '</html>');
        popupWin.document.close();
            }
 </script>
<style>--
/*.boxer {
   display: table;
   border-collapse: collapse;
}
 
.boxer .box-row {
   display: table-row;
}
 
.boxer .box {
   display: table-cell;
   text-align: left;
   vertical-align: top;
   border: 1px solid black;
}

body {
	background-color: #D3DCE6;
	background-image: url(images/background.jpg);
}
#Layer2 {position:absolute;
	width:1055px;
	height:247px;
	z-index:1;
	left: 177px;
	top: 21px;
}
#Layer1 {
	position:absolute;
	width:725px;
	height:503px;
	z-index:1;
	left: 258px;
	top: 36px;
}
#Layer3 {
	position:absolute;
	width:385px;
	height:124px;
	z-index:2;
	left: 281px;
	top: 45px;
}
#Layer4 {
	position:absolute;
	width:375px;
	height:127px;
	z-index:2;
	left: 699px;
	top: 221px;
}
.style67 {color: #000000}
.style68 {font-size: 14px}
.style69 {color: #000000; font-size: 14px; }
.style72 {color: #000000; font-size: 12px; }
*/
/*---styling ------*/
body {
    background-color: #f0f4f8;
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
    color: #333;
}

div[style="border:dotted"] {
    background-color: #fff;
    border-radius: 8px;
    padding: 20px;
    max-width: 900px;
    margin: 20px 50px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    margin-left:auto;
}

/* Container styling */
#tdToPrint {
    margin: 20px;
    padding: 20px;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 8px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
}

/* Table styling */
#tdToPrint table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    font-family: Arial, sans-serif;
    font-size: 14px;
}

/* Table headers */
#tdToPrint table th,
#tdToPrint table td {
    padding: 12px 15px;
    border: 1px solid #333;
    text-align: center;
}

/* Header row styling */
#tdToPrint table th {
    background-color: #f2f2f2;
    color: #333;
    font-weight: bold;
}

/* Data rows */
#tdToPrint table tr:nth-child(even) {
    background-color: #f9f9f9;
}

#tdToPrint table tr:nth-child(odd) {
    background-color: #fff;
}

/* Total row styling */
#tdToPrint table tr:last-child td {
    font-weight: bold;
    background-color: #f2f2f2;
}

/* Print button styling */
#tdToPrint input[type="button"] {
    margin-top: 15px;
    padding: 10px 20px;
    font-size: 14px;
    font-weight: bold;
    color: #fff;
    background-color: #003366;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

#tdToPrint input[type="button"]:hover {
    background-color: #00509e;
}


</style>
</head>
<body>
<?php

$from =$_POST['from8'];
$to =$_POST['to8'];

$date = mktime(0,0,0,date("Y"),date("m"),date("d")); 
$date_2day = date("Y-m-d", $date);
$from_date =date("$from",$date);
$to_date =date("$to",$date);


$payments = [];
$wallet = [];
$expenses = [];
$tenantid = [];

$result2= "SELECT tenant_id,SUM(amount) as total_amount FROM wallet
where shopid ='$landlordid' AND Trans_date BETWEEN '$from_date' AND '$to_date' AND amount > 0
GROUP BY tenant_id";

//mysql_close();
?>
<div style="border:dotted" align="center">
<div id="tdToPrint">
<p align="center" class="style69"> INCOME </p>
<p align="center" class="style69">Reporting period:<?php echo $from_date;  ?> : <?php echo $to_date;  ?>  </p>
<table>
<tr>
		<td class="style67 style68">Customer Id </td>
		<td class="style67 style68">Name</td>
		<td class="style69">Total Amount </td>
  </tr>
	<?php
$myquery1 = $conn1-> query_database_many($conn,$result2);

while($nt = $myquery1->fetch_assoc()) {	
?>
	<tr>
	    <td class="style67"><span class="style68"><?php echo $nt['tenant_id']; ?></span></td>
		<td class="style67"><span class="style68">
		<?php
		$customerid = $nt['tenant_id'];
		
		$result612 = "SELECT firstname,middlename,lastname
            FROM tenants
            WHERE shopid ='$landlordid' AND id ='$customerid'";

// Execute the query
$info2456 = $conn1->query_database_many_rows($conn, $result612);

// Correctly reference the result from $info24
$names1 = $info2456["firstname"]; 

$names2 = $info2456["middlename"];

$names23 = $info2456["lastname"];      

echo $names1; echo "  "; echo $names2; echo "  "; echo $names23; 

$amount_wallet = $nt['total_amount'];

array_push($wallet,$amount_wallet);

array_push($tenantid,$customerid);
		
		?>
		</span> </td> 
		<td class="style67"><span class="style68"><?php echo number_format($nt['total_amount'], 0, '.', ','); ?></span></td>
	</tr>
	<?php
}

?>
<tr> 
<td class="style69">Total</td> 
 <td class="style69"> </td> 
		<td class="style67"><span class="style68">
	    <?php
		// SQL Query
$result5 = "SELECT SUM(amount) as total_amount 
            FROM wallet
            WHERE shopid ='$landlordid' 
            AND Trans_date BETWEEN '$from_date' AND '$to_date' 
            AND amount > 0";

// Execute the query
$info23 = $conn1->query_database_many_rows($conn, $result5);

// Check if a result is returned
if ($info23) {
    $totalss = number_format($info23["total_amount"], 0, '.', ',');
    echo $totalss;
} else {
    echo "0";
}
		
		  ?>
		</span> </td>
	
</tr>
</table>
<?php

$result22= "SELECT tenant_id,SUM(amount) as total_amount FROM payments
where shopid ='$landlordid' AND date_created BETWEEN '$from_date' AND '$to_date'
GROUP BY tenant_id";

$myquery12 = $conn1-> query_database_many($conn,$result22);

while($nt22 = $myquery12->fetch_assoc()) {

$payment_amount2 = $nt22['total_amount'];

array_push($payments,$payment_amount2);

}

?>
<p align="center" class="style69"> Revenue Stream: Debts [Balances on customer Invoices] </p>
<p align="center" class="style69">Reporting Period:<?php echo $from_date;  ?> : <?php echo $to_date;  ?>  </p>
<table border="1" align="center">
<tr>
		<td class="style67 style68">Customer Id</td>
		<td class="style67 style68">Name</td>
		<td class="style69">Debt amount </td>
  </tr>
	<?php

for ($j = 0; $j < count($tenantid); $j++) {

?>
	<tr>
	    <td class="style67"><span class="style68"><?php echo $tenantid[$j]; ?></span></td>
		<td class="style67"><span class="style68">
		<?php
		$customerid2 = $tenantid[$j];
		
		$result6122 = "SELECT firstname,middlename,lastname
            FROM tenants
            WHERE shopid ='$landlordid' AND id ='$customerid2'";

// Execute the query
$info24562 = $conn1->query_database_many_rows($conn, $result6122);

// Correctly reference the result from $info24
$names12 = $info24562["firstname"]; 

$names22 = $info24562["middlename"];  

$names23 = $info24562["lastname"];   

echo $names12; echo "  "; echo $names22; echo "  "; echo $names23; 
$query_wallet = "SELECT SUM(amount) AS total_wallet 
                 FROM payments 
                 WHERE tenant_id = '$customerid2' AND pay_status =0 
                   AND shopid ='$landlordid'";

$result_wallet = $conn1->query_database_many_rows($conn, $query_wallet);

// Ensure numeric value (avoid string/NULL issues)
$wallet_amount = isset($result_wallet['total_wallet']) ? floatval($result_wallet['total_wallet']) : 0.0;

// If wallet entries are negative values and you want to treat them as positive amounts:
$wallet_amount = abs($wallet_amount);

// Ensure payment value is numeric too
$payment_amount = isset($payments[$j]) ? floatval($payments[$j]) : 0.0;

// Compute the per-tenant balance
$blc_amount = $payment_amount - $wallet_amount;
//$blc_amount = $payments[$j]  - $wallet[$j]; 

?>
		</span> </td> 
		<td class="style67"><span class="style68"><?php #echo number_format($payments[$j], 0, '.', ','); ?>
		<span class="style68"><?php echo number_format($wallet_amount, 0, '.', ','); ?></span>
		
		
		</td>
	
</tr>
<?php
  } 
?>
<tr> 
<td class="style69">Total debts: </td> 
 <td class="style69"> </td> 
		<td class="style67"><span class="style68">
	    <?php
		// SQL Query
$total_wallet = array_sum($wallet);

$total_payments = array_sum($payments);

$debts_total = $total_payments - $total_wallet;

echo number_format($debts_total, 0, '.', ',');		
		  ?>
		</span> </td>
		
</tr>
</table>

<?php
$result9 = mysqli_query($conn, "SELECT 
    CASE 
        WHEN HouseId = 'General' THEN 'General'
        ELSE HouseId 
    END as display_id,
    CATEGORY,
    SUB_CATEGORY,
    SUM(AMOUNT) as total_exp 
FROM expenses
WHERE shopid ='$landlordid' 
AND (Date_of_trans BETWEEN '$from_date' AND '$to_date')
AND (HouseId = 'General' OR HouseId IN (SELECT house_no FROM houses WHERE shopid ='$landlordid'))
GROUP BY display_id, CATEGORY, SUB_CATEGORY");
?>

<p align="center" class="style69">Expenses (Cost of Sales, Operating expenses)</p>
<table border="1" align="center">
    <tr>
        <th class="style69">Id</th>
        <th class="style69">Description</th>
        <th class="style69">Category</th>
        <th class="style69">Sub Category</th>
        <th class="style69">Amount</th>
    </tr>
    <?php
    while ($row11 = $result9->fetch_assoc()) {
        $display_id = $row11['display_id']; 
        $category = $row11['CATEGORY']; 
        $subcategory = $row11['SUB_CATEGORY']; 
        $amt = $row11['total_exp'];
        array_push($expenses, $amt); // Add this line to collect expense amounts

        
        // Get description (if not General)
        $description = '';
        if ($display_id != 'General') {
            $desc_query = "SELECT description FROM houses WHERE shopid ='$landlordid' AND house_no ='$display_id'";
            $desc_result = $conn1->query_database_many_rows($conn, $desc_query);
            $description = isset($desc_result["description"]) ? $desc_result["description"] : '';
        }
    ?>
    <tr>
        <td class="style69"><?php echo $display_id; ?></td>
        <td class="style69"><?php echo $description; ?></td>
        <td class="style69"><?php echo $category; ?></td>
        <td class="style69"><?php echo $subcategory; ?></td>
        <td class="style69"><?php echo number_format($amt, 0, '.', ','); ?></td>
    </tr>
    <?php } ?>
    <tr>
        <td class="style69">Total Amount</td>
        <td class="style69"></td>
        <td class="style69"></td>
        <td class="style69"></td>
        <td class="style69">
            <?php
            $total_query = "SELECT SUM(AMOUNT) as total_expenses 
                           FROM expenses 
                           WHERE shopid ='$landlordid' 
                           AND (Date_of_trans BETWEEN '$from_date' AND '$to_date')
                           AND (HouseId = 'General' OR HouseId IN (SELECT house_no FROM houses WHERE shopid ='$landlordid'))";
            $total_result = $conn1->query_database_many_rows($conn, $total_query);
            echo number_format($total_result["total_expenses"], 0, '.', ',');
            ?>
        </td>
    </tr>
</table>
<span class="style67">
    <p class="style72"> Net profit:
<?php
$total_wallet = array_sum($wallet);

$total_expenses = array_sum($expenses);

$net_profit = $total_wallet - $total_expenses;

echo number_format($net_profit, 0, '.', ',');
		       ?> </p>
</span>			   
		  <p align="center"><input type="button" value="print" onclick="PrintTd();" /> </p>
</body>
</html>

