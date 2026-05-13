

<?php
session_start();

require 'collect_data.php';

$landlordid = $_SESSION['login_landlordid']; 

$category = $_GET['category'];


$category = "SELECT Expense_sub_category FROM expense_sub_category
where 
shopid = '$landlordid' AND Expense_Category ='$category' ";

echo "<select name=sub_category value=''";
//echo "onchange = 'show_sub(this.value)'";
echo "'element select medium' id='element_3'>Category</option>";
// printing the list box select command\
echo "<option value=>Select sub_category</option>";


$myquery1 = $conn1-> query_database_many($conn,$category);

while($nt = $myquery1->fetch_assoc()) {

echo "<option value=$nt[Expense_sub_category]>$nt[Expense_sub_category]</option>";
/* Option values are added by looping through the array */
}
echo "</select>";// Closing of list box 
?>

