<?php
  session_start();

require 'collect_data.php';

$landlordid = $_SESSION['login_landlordid']; 
?>
<!DOCTYPE HTML>
<html>
<head>
<title>MyShop | Expenses</title>

<script type="text/javascript">

function show_reports(str5)
{
if (str5=="")
  {
  document.getElementById("txtHint5").innerHTML="";
  return;
  }
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("txtHint5").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","show_reports.php?id="+str5,true);
xmlhttp.send();
}
function show_reports(str2)
{
if (str2=="")
  {
  document.getElementById("txtHint2").innerHTML="";
  return;
  } 
if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
  xmlhttp=new XMLHttpRequest();
  }
else
  {// code for IE6, IE5
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("txtHint2").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","show_reports.php?id="+str2,true);
xmlhttp.send();
}
</script>
<?php 
  $type = $_GET['category'];
 
   
   switch ($type) {
    case 'New_category':
        ?>
		<form action="category2.php" method="post">
          <p>
            <input name="category" type="text" size="30" maxlength="30" placeholder ='Enter New category Name' />
 </p>
          <p>
            <label>
            <input type="submit" name="Submit" value="Post">
            </label>         
            </p>
		</form>
		<?php
		break;
		 case 'New_sub_category':
		  ?>
		<form action="expense_sub_category.php" method="post">
          <p>
		 
		<?php
  
$category11 = "SELECT Expense_Category FROM  expense_category
where shopid ='$landlordid'";

/* You can add order by clause to the sql statement if the names are to be displayed in alphabetical order */


echo "<select name=category value='' class='element select medium' id='element_3'>Category</option>";
// printing the list box select command\
echo "<option value=>Select Category</option>";

$myquery12 = $conn1-> query_database_many($conn,$category11);

while($nt2 = $myquery12->fetch_assoc()) {

echo "<option value=$nt2[Expense_Category]>$nt2[Expense_Category]</option>";
/* Option values are added by looping through the array */
}
echo "</select>";// Closing of list box ?>
<br />
            <input name="sub_category" type="text" size="30" maxlength="30" placeholder ='Enter new sub-category' />
 </p>
          <p>
            <label>
            <input type="submit" name="Submit" value="Post">
            </label>         
            </p>
		</form>
		<?php
		break;
		case 'department':
		  ?>
		<form action="departments.php" method="post">
          <p>
            <input name="department" type="text" size="30" maxlength="30" placeholder ='Enter New Department' />
 </p>
          <p>
            <label>
            <input type="submit" name="Submit" value="Post">
            </label>         
            </p>
		</form>
		<?php
		break;
		case 'Quick':
		  ?>
		<form action="quickSearch.php" method="post">
          <p>
            <input name="quick" type="text" size="30" maxlength="30" placeholder ='Enter Machine Category' onKeyUp="machineSearch(this.value)" />
 </p>
          <p>
            <label>
            <input type="submit" name="Submit" value="Post">
            </label>         
            </p>
		</form>
		<?php
		break;
		case 'reports':
		  ?>
		<form action="reports.php" method="post">
          <p>
            <select name="" onChange="show_reports(this.value)">
              <option>Select</option>
              <option value="Expense_Category">Category List</option>
              <option value="Expense_sub_category">Sub Category List</option>
    </select>
	<script type="text/javascript">
			Calendar.setup({
			inputField	 : "element_1_3",
			baseField    : "element_1",
			displayArea  : "calendar_1",
			button		 : "cal_img_1",
			ifFormat	 : "%B %e, %Y",
			onSelect	 : selectDate
			});
		</script>
 
 </p> 
		</form>
		<div id="txtHint2"></div>
		<?php
		break;
    default:
	echo '';
} 
?>

</body>
</html>