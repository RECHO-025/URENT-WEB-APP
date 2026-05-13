<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>Expense Entry</title>
</head>
<body>
<?php

include("index.php");

#include 'collect_data.php';


 
$landlordid = $_SESSION['login_landlordid']; 

include 'timezone.php';

$datedd = strtotime($_POST['dated']);

$Date_of_trans = date("Y-m-d",$datedd);


$date_2day1 = strtotime(date("Y-m-d"));

$date_2day = date("Y-m-d",$date_2day1);


$rate = $_POST['rate'];
$qty = $_POST['qty'];
$item = $_POST['item'];

$paid = $_POST['paid'];
$received = $_POST['received'];
$authorised = $_POST['authorised'];
$pvn = $_POST['pvn'];

$category = $_POST['category'];
$sub_category = $_POST['sub_category'];

$sitetype = $_POST['sitetype'];

$house = $_POST['house'];

$amount1 = $rate * $qty;

if($rate =='' or $qty =='' or  $item =='' or  $paid =='' or  $received =='' or  $authorised =='' or  $pvn =='')

 {
 ?>
 <p align="center"> One of cells in the form is empty</p>
 <?php
 }
 else {
$queryr2 = "SELECT *FROM expenses
where 
(TRANSID  ='$pvn') AND shopid ='$landlordid'";
 
  if($info22 = $conn1-> query_database_many_rows($conn,$queryr2))
  {
?>
<p align="center">Payment Voucher <?php echo $pvn; ?> already in use</p>

<?php  
}
else {

/* $sql788="INSERT INTO expenses (shopid,Paid_by,Received_by,Authorised_by,ITEM,DESCRIPTION,RATE,TAX,QTY,Amount,TRANSID,CATEGORY,SUB_CATEGORY,Date_of_entry,Date_of_trans)
VALUES('$shopid','$paid','$received','$authorised','$item','$item','$rate',' ','$qty','$amount1','$pvn','$category','$sub_category','2012-01-01','2012-01-01')";

*/

$tax = 0.18;

$sql788="INSERT INTO expenses (shopid,SiteType,HouseId,Paid_by,Received_by,Authorised_by,ITEM,DESCRIPTION,RATE,TAX,QTY,Amount,TRANSID,CATEGORY,SUB_CATEGORY,Date_of_entry,Date_of_trans) VALUES('$landlordid','$sitetype','$house','$paid','$received','$authorised','$item','$item','$rate','$tax', '$qty','$amount1','$pvn','$category','$sub_category','$date_2day','$Date_of_trans')";


$execute_query1 = $conn1-> update_record ($conn,$sql788); 
  
 /* 
  $result255 = "SELECT SUM(RATE*QTY) FROM expenses
where 
CATEGORY ='$category' AND shopid ='$landlordid'";

$info23 = $conn1-> query_database_many_rows($conn,$result255);


$bal_mt= $info23["SUM(RATE*QTY)"]; 

  $sql555="UPDATE 
charts 
SET 
balance ='$bal_mt',Dated ='$date_2day',user ='$username'
WHERE 
Name ='$category' AND Type ='Expense' AND shopid ='$landlordid'";

$execute_query2 = $conn1-> update_record ($conn,$sql555); 


*/

if($execute_query1 =='True')
  {

?>
<p align="center">Record Post: Success </p>

<?php
   }
   else {
   ?>
   
 <p align="center" style="color:#FF0000">Record Post: Fail <?php echo $execute_query1;   ?> II <?php echo $execute_query1;   ?>   </p>
  
   <?php
   }
}
}

?>
</body>
</html>
