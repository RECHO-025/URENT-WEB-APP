<?php

include 'index.php';


$category = $_POST['category'];

$sub_category = $_POST['sub_category'];

  if ($sub_category =='')
    {
	echo 'You never typed any thing';
	
	 }
	 else{
	 $query = "SELECT *FROM expense_sub_category 
where 
(Expense_sub_category='$sub_category' AND shopid ='$landlordid' )";

if($info = $conn1-> query_database_many_rows($conn,$query))
  {
	 ?>
	 <p align="center"> This Category Name already in Use </p>
	 <?php
	 }
	   else {
	   
	 $sql="INSERT INTO expense_sub_category (shopid,Expense_Category,Expense_sub_category)
 VALUES
('$landlordid','$category','$sub_category')";


  $execute_query = $conn1-> update_record ($conn,$sql);

if ($execute_query =='True')
  {
  ?>
  
  <p align="center"> Category item successfully Added </p>
  
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