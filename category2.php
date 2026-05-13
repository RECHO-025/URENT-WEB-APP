<!DOCTYPE HTML>
<html>
<head>
<title>MyShop | Expense Category up</title>
<?php

include 'index.php';

$category = $_POST['category'];

  if ($category =='')
    {
	echo 'Enter Category Name';
	
	 }
	 else{
	 $query = "SELECT *FROM expense_category 
where 
( Expense_Category='$category' AND shopid ='$landlordid' )";

if($info = $conn1-> query_database_many_rows($conn,$query))
  {
	 ?>
	 <p align="center"> This Category Name already in Use </p>
	 <?php
	 }
	   else {
	   
	 $sql="INSERT INTO expense_category  (shopid,Expense_Category)
 VALUES
('$landlordid','$category')";


$execute_query = $conn1-> update_record ($conn,$sql);

if ($execute_query =='True')
  {
  ?>
  
  <p align="center"> Category Type successfully Added </p>
  
  <?php
  }
  else{
  
  ?>
  <p align="center"> Category entry failed </p>
  <?php
       }
	   }
	     }
?>