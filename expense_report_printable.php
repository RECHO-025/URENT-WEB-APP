
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title></title>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	width:1064px;
	height:388px;
	z-index:1;
	left: 128px;
	top: 40px;
}
.style1 {color: #FFFFFF}
body{
	background-color:#FFFFFF;
	background-image:url(images/background.jpg);
	color:#000000;
	margin-left: 50px;
	margin-right: 100px;
	background-repeat: repeat;
}
#ad{ display:none;}
#leftbar{ display:none;}
#contentarea{ width:100%;}
}
#Layer2 {
	position:absolute;
	width:355px;
	height:39px;
	z-index:1;
	left: 495px;
	top: 67px;
}
body,td,th {
	font-family: Times New Roman, Times, serif;
	font-size: 12px;
}
#Layer3 {
	position:absolute;
	width:165px;
	height:20px;
	z-index:1;
	left: 530px;
	top: 47px;
}
-->
</style>
</head>

<body onLoad="myFunction()">
<?php 
$Use_Title = 1;
//define date for title: EDIT this to create the time-format you need
$now_date = DATE('m-d-Y H:i');
//define title for .doc or .xls file: EDIT this if you want
$title = "";

IF ($w=1)
{
      $file_type = "vnd.ms-excel";
     $file_ending = "xls";
	 
}ELSE {
     $file_type = "msword";
     $file_ending = "doc";
}
//header info for browser: determines file type ('.doc' or '.xls')
HEADER("Content-Type: application/$file_type");
HEADER("Content-Disposition: attachment; filename=Expense_report.$file_ending");
HEADER("Pragma: no-cache");
HEADER("Expires: 0");
 
/*    Start of Formatting for Word or Excel    */
 
IF ($w=1) //check for $w again
{
     /*    FORMATTING FOR WORD DOCUMENTS ('.doc')   */
     //create title with timestamp:
     IF ($Use_Title == 1)
     {
         ECHO("$title\n\n");
     }
     //define separator (defines columns in excel & tabs in word)
     $sep = "\n"; //new line character

session_start();

$landlordid = $_SESSION['login_landlordid']; 

include 'collect_data.php';

$date = mktime(0,0,0,date("Y"),date("m"),date("d")); 

$date_2day = date("Y-m-d", $date);

$from =$_POST['from'];

$to =$_POST['to'];

$from_date =date("$from",$date);

$to_date =date("$to",$date);

$reporttype = $_POST['reporttype'];


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
<h5 align="center"><?php 
$query23 =  "SELECT business_name FROM landlords
 WHERE landlord_id ='$landlordid'";
 
 $info = $conn1-> query_database_many_rows($conn,$query23);
 
 
 $shop_name =  $info["business_name"];
 echo $shop_name;  ?></h1>
 
<p align="center"><strong>Expenses record sheet: <?php echo $from_date; ?> to <?php echo $to_date; ?> </strong></p>

<table width="800" border="1" align ="center" cellpadding="0" cellspacing="0" align="center">
  <tr>
   <td width="87"><div align="left" class="style24 style25">
      <div align="center"> Site</div>
    </div></td>
	
	<td width="87"><div align="left" class="style24 style25">
      <div align="center"> House Id</div>
    </div></td> 
	<td width="87"><div align="left" class="style24 style25">
      <div align="center">Transid</div>
    </div></td>
	<td width="90"><div align="left" class="style24 style25">
	  <div align="center">Item</div>
	</div></td>
	<td width="112"><div align="left" class="style24 style25">
	  <div align="center">Category</div>
	</div></td>
	<td width="127"><div align="left" class="style24 style25">
	  <div align="center">Sub Category</div>
	</div></td>
    <td width="129"><div align="left" class="style24 style25">
      <div align="center"><span class="style11"><font face="Arial, Helvetica, sans-serif">Description</span></div>
    </div></td>
    <td width="97"><div align="left" class="style24 style25">
      <div align="center">Rate</div>
    </div></td>
	<td width="93"><div align="center" class="style24 style25">
	  <div align="center" class="style11">
	    <div align="center">Tax</div>
	  </div>
	</div></td>
	<td width="92"><div align="left" class="style24 style25">
	  <div align="center">Qty</div>
	</div></td>
	<td width="103"><div align="center" class="style25"><span class="style24">Amount</span></div></td>
	<td width="71">Trans Date </td>
	<td width="342"><div align="left" class="style25">
	  <div align="center">Date of Entry </div>
	</div></td>
	<td> Delete</td>
  </tr>
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
    <td><div align="center" class="style2 style11 style25">
      <div align="center"><?php 
	  
	  $query2311 =  "SELECT name FROM categories
 WHERE shopid ='$landlordid' AND id ='$SiteType'";
 
 $info11 = $conn1-> query_database_many_rows($conn,$query2311);
 
 
 $sitename =  $info11["name"];
	  
	  echo $SiteType;
	  echo ":";
	  echo $sitename;
	  
	  
	  ?></div>
    </div></td>
	
	
	<td><div align="center" class="style2 style11 style25">
      <div align="center"><?php 
	  
	   $query23112 =  "SELECT description FROM houses
 WHERE shopid ='$landlordid' AND house_no ='$HouseId'";
 
 $info112 = $conn1-> query_database_many_rows($conn,$query23112);
 
 
 $housename =  $info112["description"];
	  
	  echo $HouseId;
	  echo ":";
	   echo  $housename;
	  
	  
	  
	   ?></div>
    </div></td>
	
	<td><div align="center" class="style2 style11 style25">
      <div align="center"><?php echo $no; ?></div>
    </div></td> 
	
    <td><div align="center" class="style2 style11 style25">
      <div align="center"><?php echo $item; ?></div>
    </div></td>
	
	<td><div align="center" class="style2 style11 style25">
      <div align="center"><?php echo $category; ?></div>
    </div></td>
	<td><div align="center" class="style2 style11 style25">
      <div align="center"><?php echo $sub_category; ?></div>
    </div></td>
	
    <td><div align="center" class="style2 style11 style25">
      <div align="center"><?php echo $descrn; ?></div>
    </div></td>
    <td><div align="center" class="style2 style11 style25">
      <div align="center"><?php echo $rate; ?></div>
    </div></td>
	<td><div align="center" class="style2 style11 style25">
      <div align="center"><?php echo $tax; ?></div>
	</div></td>
	<td><div align="center" class="style2 style11 style25">
      <div align="center"><?php echo $qty ; ?></div>
	</div></td>
	<td width="103"><div align="center" class="style25"><?php echo $mt; ?></div></td>
	<td width="71"><?php echo $date_trans;  ?></td>
	<td width="342"><div align="center" class="style2 style11 style25">
        <div align="center"><?php echo $date; ?></div>
	</div></td>
  </tr>
  <p>
    <?php

}
?>
  </p>
</table>
<table width="200" border="1" align="center">
  <tr>
    <td>Category</td>
    <td>Amount</td>
  </tr>
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
<tr align="center"><p align="center"> Summary By Category </p> </tr>
<tr>
    <td>Total:</td>
    <td><?php
	
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
<?php 

}ELSE{
 ?>
 <p align="center"> An error occured while processing user request</p> <?php 
}


?>
</body>
</html>