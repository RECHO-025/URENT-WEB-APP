<?php

include'index.php';

//$username1 = $_SESSION['username'];
 $id = $_POST['id'];
  $id2 = $_POST['id2'];
  $name = $_POST['name'];
   $name2 = $_POST['name2'];

$dated = mktime(0,0,0,date("m"),date("d"),date("Y")); 
$date_2day = date("Y/m/d", $dated);


if ($id2 =='Expense_Category')
      {
	  
$sql="UPDATE expense_category
SET 
Expense_Category='$name'
 where
 Expense_CategoryID ='$id' AND shopid ='$landlordid'";
 
 $execute_query = $conn1-> update_record ($conn,$sql);


  
   $sql2="UPDATE expense_sub_category
SET 
Expense_Category='$name'
 where
Expense_Category ='$name2' AND shopid ='$landlordid'";

$execute_query2 = $conn1-> update_record ($conn,$sql2);

 /*
 $dated = mktime(0,0,0,date("m"),date("d"),date("Y")); 
$date_2day = date("Y/m/d", $dated);
$date3 =date("h:i:s");
//$time =date($date3);

$ctivity ="User of Type:Administrator successfully changed expense category";

$records="INSERT INTO user_activities (UserName,Level,Activity,Date,Time)
VALUES('$username1','Administrator','$ctivity','$date_2day','$date3')";

$execute_query3 = $conn1-> update_record ($conn,$records);


      }
	  else {
	  
	 $sql4="UPDATE expense_sub_category
SET 
Expense_sub_category='$name'
 where
 Expense_CategoryID ='$id' AND shopid ='$shopid'";
 
 $execute_query4 = $conn1-> update_record ($conn,$sql4);


 $dated = mktime(0,0,0,date("m"),date("d"),date("Y"));
  
$date_2day = date("Y/m/d", $dated);

$date3 =date("h:i:s");

//$time =date($date3);

$ctivity ="User of Type:Administrator successfully changed expense sub category";

$records2="INSERT INTO user_activities (UserName,Level,Activity,Date,Time)
VALUES('$username1','Administrator','$ctivity','$date_2day','$date3')";

$execute_query4 = $conn1-> update_record ($conn,$records2);
	 
	*/
	  }


?>
<p align="center"> Update Successfull  </p>
<?php

 ?>
</body>
</html>