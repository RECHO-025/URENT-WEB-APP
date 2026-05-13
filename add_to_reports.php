
<?php


if($info = $conn1-> query_database_many_rows($conn, $result))
  {
  
?>

<div align="center">
<div class="boxer">
	<div class="box-row">
		<div class="box">Name</div>
		<div class="box">Edit</div>
		<div class="box">Delete</div>
	</div>
	<?php
	$myquery1 = $conn1-> query_database_many($conn,$result);

while($nt = $myquery1->fetch_assoc()) {

$name= $nt["Expense_Category"];
$id= $nt["Expense_CategoryID"];

?>
	<div class="box-row">
		<div class="box"><font face="Arial, Helvetica, sans-serif"><?php echo $name; ?></font></div>
		<div class="box"><font face="Arial, Helvetica, sans-serif">
		
		 <form action="edit_category.php" method="POST" name="edit">
	              <input name="name" type="hidden"  value="<?php echo $name; ?>" />
				  <input name="name2" type="hidden"  value="<?php echo $name; ?>" />
				  <input name="id" type="hidden"  value="<?php echo $id; ?>" />
				   <input name="id2" type="hidden"  value="<?php echo $id2; ?>" />
				  
	        	  <input type="submit" name="Edit" value="Edit" />  
			 </form>
		</font></div>
		<div class="box"><font face="Arial, Helvetica, sans-serif">
				<form action="delete_category.php" method="POST" name="edit" onsubmit="return confirmDelete1();">
				  <input name="name2" type="hidden"  value="<?php echo $name; ?>" />
				  <input name="name22" type="hidden"  value="<?php echo $name; ?>" />
				  <input name="id2" type="hidden"  value="<?php echo $id; ?>" />
				  <input name="id22" type="hidden"  value="<?php echo $id2; ?>" />
	              <input type="submit" name="delete" value="X" />
			  </form>
		</font></div>	
	</div>

  <p>
    <?php
}

?>
</div>
<?php
}
 else{
 ?>
   <p align="center">Zero Records could match user query or No search term entered</p>
   <?php
   } ?>