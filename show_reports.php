<?php
session_start();

require 'collect_data.php';

$landlordid = $_SESSION['login_landlordid']; 


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Jotell Technologies Uganda Limited</title>
<style type="text/css">
<!--
body {
	background-color: #D3DCE6;
	background-image: url(images/background.jpg);
	background-repeat: repeat;
}
#Layer1 {position:absolute;
	width:1149px;
	height:627px;
	z-index:0;
	left: 52px;
	top: 140px;
}
.style2 {
	color: #0000FF;
	font-weight: bold;
}
.style3 {color: #0000FF}
-->
.boxer {
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

</style>
<script type="text/JavaScript">
<!--

function confirmDelete7() { 
 return confirm("Are you sure you want to delete item?");   
} 

</script>
</head>
<body>
<?php
 


  $id2 = $_GET['id'];
  
  switch ($id2) {
    case 'Expense_Category':
        
$result= "SELECT *FROM expense_category
where
shopid ='$landlordid'";

include 'add_to_reports.php';
 break;
   case 'Expense_sub_category':   
	  
	  $result= "SELECT *FROM expense_sub_category
where
shopid ='$landlordid'";

include 'add_to_reports.php';
  break;
     case 'Date_entry': 
	include 'form_data_entry.php'; 	 
  break;
  
   case 'Date_trans': 
	include 'form_data_entry2.php';
	break; 	 
  default:
        ?>
  <p align="center"> You didn't select any thing </p>
  <?php
	  }
    ?>
  
</body>
</html>