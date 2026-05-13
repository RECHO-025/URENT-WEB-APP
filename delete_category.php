
<?php 
include 'index.php';

 $id = $_POST['id2'];
  $id2 = $_POST['id22'];
  $name = $_POST['name2'];
   $name2 = $_POST['name22'];
  
  
 if ($id2 =='Expense_Category')
      { 
      
$result = "DELETE
 FROM expense_category
where 
Expense_CategoryID ='$id' AND shopid ='$landlordid'";

$execute_query = $conn1-> update_record ($conn,$result);



$result2 = "DELETE
 FROM expense_sub_category
where 
Expense_Category ='$name2' AND shopid ='$landlordid'";

 $execute_query2 = $conn1-> update_record ($conn,$result2);

$result3 = "DELETE
 FROM expense_sub_category
where 
Expense_CategoryID ='$id' AND shopid ='$landlordid'";

$execute_query3 = $conn1-> update_record ($conn,$result3);

if($execute_query =='True' && $execute_query2 =='True' && $execute_query3 =='True' )
{
?>
<p align="center"> Erase success</p>
<?php

}
else {

?>
<p align="center"> Erase failured</p>
<?php
}
  

  }

?>


</body>
</html>
