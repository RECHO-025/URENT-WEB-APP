<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Report</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function confirmDelete1() {
            return confirm("Are you sure you want to delete? You cannot undo this action.");
        }
    </script>
</head>
<body>
<?php 
#include 'index.php';

include 'db_connect.php'; 

#include 'collect_data.php';

#session_start();
 
$landlordid = $_SESSION['login_landlordid']; 

$datedo = $_POST['datedo'];
$dated1 = $_POST['dated1'];

$date = mktime(0,0,0,date("d"),date("m"),date("Y")); 
$date_2day = date("d-m-Y", $date);

$from_date =date("$datedo",$date);
$to_date =date("$dated1",$date); 

$reporttype = $_POST['reporttype'];

if($reporttype == 'General')
 {

$result= "SELECT *FROM expenses
where (Date_of_entry BETWEEN '$from_date' AND '$to_date') 
AND shopid ='$landlordid'";
}
else {

$result= "SELECT *FROM expenses
where (Date_of_entry BETWEEN '$from_date' AND '$to_date') 
AND shopid ='$landlordid' AND (SiteType ='$reporttype' OR HouseId ='$reporttype')";

}

//mysql_close();
?>
<div class="container">
    <h1 class="title"><?php 
$query23 =  "SELECT business_name FROM landlords
 WHERE landlord_id ='$landlordid'";
 
 $info = $conn1-> query_database_many_rows($conn,$query23);
 
 
 $shop_name =  $info["business_name"];
 echo $shop_name;  ?></h1>
    <p class="date-range">Expenses record sheet:  <?php echo $from_date; ?> to <?php echo $to_date; ?></p>

    <div class="button-group">
        <button>
		<form id="form1" name="form1" method="post" action="expense_report_printable.php">
		<input name="reporttype" type="hidden" value="<?php echo $reporttype; ?>" />
		<input name="from" type="hidden" value="<?php echo $from_date; ?>" />
		   <input name="to" type="hidden" value="<?php echo $to_date; ?>" /> 
          <input type="submit" name="Submit" value="Click to Export Return to Ms Excel" />
	</form>
	</button>
	<?php
	if($reporttype == 'General')
 {
	
	?>
        <button>
		<form action="index.php?page=profit_loss" method="post">
	    <input name="reporttype" type="hidden" value="<?php echo $reporttype; ?>" />
		<input name="from8" type="hidden" value="<?php echo $from_date;  ?>" />
		<input name="to8" type="hidden" value="<?php echo $to_date;  ?>" />
		<input type="hidden" name="expense_id" value="1">
		<input name="" type="submit" value="Profit and Loss Statement" />
		</form>	
		</button>
        <button>
		<form action="index.php?page=report_gui" method="post" id ="">
       <input name="reporttype" type="hidden" value="<?php echo $reporttype; ?>" />
		<input name="from9" type="hidden" value="<?php echo $from_date;  ?>" />
		<input name="to9" type="hidden" value="<?php echo $to_date;  ?>" />
		<input type="hidden" name="expense_id" value="1">
		<input name="" type="submit" value="Tenancy Performance Report" />
		</form>
		</button>
		<?php
      }
	  else {
	  
	  
	  }
 ?>
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th>Site</th>
                <th>House Id</th>
                <th>Transid</th>
                <th>Item</th>
                <th>Category</th>
                <th>Sub Category</th>
                <th>Description</th>
                <th>Rate</th>
                <th>Tax</th>
                <th>Qty</th>
                <th>Amount</th>
                <th>Trans Date</th>
                <th>Date of Entry</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <!-- PHP code to dynamically generate rows here -->
			<?php
$myquery12 = $conn1-> query_database_many($conn,$result);

while($nt2 = $myquery12->fetch_assoc()) {

$no=$nt2["TRANSID"];

$SiteType =$nt2["SiteType"];

$HouseId =$nt2["HouseId"];

$item=$nt2["ITEM"];
$descrn=$nt2["DESCRIPTION"];
$rate =$nt2["RATE"];
$tax =$nt2["TAX"];
$qty =$nt2["QTY"];
$mt =$nt2["AMOUNT"];
$category =$nt2["CATEGORY"];
$sub_category =$nt2["SUB_CATEGORY"];
$date =$nt2["Date_of_entry"];  
$date_trans =$nt2["Date_of_trans"];
?>
<tr>
	<td><?php 
	  
	  $query2311 =  "SELECT name FROM categories
 WHERE shopid ='$landlordid' AND id ='$SiteType'";
 
 $info11 = $conn1->query_database_many_rows($conn,$query2311);
 
 
 $sitename =  $info11["name"];
	  
	  echo $SiteType;
	  echo ":";
	  echo $sitename;
	  
	  
	  ?></td>
	  <td><?php 
	  
	  $query23112 =  "SELECT description FROM houses
WHERE shopid ='$landlordid' AND house_no ='$HouseId'";

$info112 = $conn1-> query_database_many_rows($conn,$query23112);


$housename =  $info112["description"];
	 
	 echo $HouseId;
	 echo ":";
	  echo  $housename;
	  ?></td>
	  <td><?php echo $no; ?></td>
	  <td><?php echo $item; ?></td>
	  <td><?php echo $category; ?></td>
	  <td><?php echo $sub_category; ?></td>
	  <td><?php echo $descrn; ?></td>
	  <td><?php echo $rate; ?></td>
	  <td><?php echo $tax; ?></td>
	  <td><?php echo $qty ; ?></td>
	  <td><?php echo $mt; ?></td>
	  <td><?php echo $date_trans;  ?></td>
	  <td><?php echo $date; ?></td>
	  <td><form action="delete_expense.php" method="POST" name="edit" onsubmit="return confirmDelete1();">
				  <input name="transid" type="hidden"  value="<?php echo $no; ?>" />
				  <input name="shopid" type="hidden"  value="<?php echo $landlordid; ?>" />
	              <input type="submit" name="delete" value="X" />
			  </form></td>

</tr>
<?php

}
?>
        </tbody>
    </table>

    <div class="summary">
        <h2>Summary By Category</h2>
        <table class="summary-table">
            <tr>
                <th>Category</th>
                <th>Amount</th>
            </tr>
            <!-- PHP code to dynamically generate summary rows here -->
			<?php 
  
  if($reporttype == 'General')
 {
  $result23= "SELECT SUM(AMOUNT),CATEGORY FROM expenses
where
(Date_of_entry BETWEEN '$from_date' AND '$to_date') AND shopid ='$landlordid' GROUP BY CATEGORY";
}
else {

 $result23= "SELECT SUM(AMOUNT),CATEGORY FROM expenses
where
(Date_of_entry BETWEEN '$from_date' AND '$to_date') AND shopid ='$landlordid' AND (SiteType ='$reporttype' OR HouseId ='$reporttype') GROUP BY CATEGORY";


}
 
$myquery122 = $conn1-> query_database_many($conn,$result23);

while($nt22 = $myquery122->fetch_assoc()) {

$mt12 =$nt22["SUM(AMOUNT)"];
$category12 =$nt22["CATEGORY"];
  ?>
   <tr>
    <td><?php echo $category12;  ?></td>
    <td><?php echo $mt12;  ?></td>
  </tr>
  <?php

}
?>
			<tr>
                <td>Total:</td>
                <td>
					<!-- PHP total amount here -->
					<?php
	
	if($reporttype == 'General')
{
   
   $result244= "SELECT SUM(AMOUNT) FROM expenses
where
(Date_of_entry BETWEEN '$from_date' AND '$to_date') AND shopid ='$landlordid'";

   }
   else {
   
   $result244= "SELECT SUM(AMOUNT) FROM expenses
where
(Date_of_entry BETWEEN '$from_date' AND '$to_date') AND shopid ='$landlordid' AND (SiteType ='$reporttype' OR HouseId ='$reporttype')";
   
   }

$info23 = $conn1-> query_database_many_rows($conn,$result244);
   
   $tt12 = $info23["SUM(AMOUNT)"];
	echo $tt12; 
	 ?></td>
				</tr>
        </table>
    </div>
</div>

</body>
</html>

<style>
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    color: #333;
    margin: 0;
    padding: 0;
}

.container {
    width: 100%;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    border-radius: 8px;
    margin-top: 20px;
}

.title {
    text-align: center;
    font-size: 24px;
    color: #000;
    font-weight:bold;
}

.date-range {
    text-align: center;
    font-size: 16px;
    color: #000;
    margin-bottom: 20px;
    font-weight:bold;
}

.button-group {
    text-align: center;
    margin-bottom: 20px;
}

button {
    padding: 10px 20px;
    margin: 0 10px;
    border: none;
    border-radius: 5px;
    background-color: #0D2074;
    color: #fff;
    font-size: 14px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #0275d8;
}

.report-table, .summary-table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px 0;
}

.report-table th, .report-table td, .summary-table th, .summary-table td {
    padding: 10px;
    text-align: center;
    border: 1px solid #ddd;
}

.report-table th {
    background-color: #0D2074;
    color: #fff;
    font-weight: normal;
}

.report-table tr:nth-child(even) {
    background-color: #f9fafb;
}

.report-table tr:hover {
    background-color: #e2e8f0;
}

.summary {
    text-align: center;
    margin-top: 20px;
}

.summary h2 {
    color: #333;
}

.summary-table th {
    background-color: #0D2074;
    color: #fff;
}

.summary-table tr:nth-child(even) {
    background-color: #f9fafb;
}
</style>
