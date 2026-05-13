<?php

include('db_connect.php');

 
$landlordid = $_SESSION['login_landlordid']; 

if(isset($_GET['id'])){

    $qry = $conn->query("SELECT * FROM wallet WHERE id=".$_GET['id']);
    
}

$queryr2 = $conn->query("SELECT COUNT(TRANSID) FROM expenses where shopid ='$landlordid'");

$row5 = $queryr2->fetch_assoc();

$id= $row5["COUNT(TRANSID)"]; 

$id2 = $id+1;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Ensure jQuery is loaded -->
<script language="javascript">

function confirmDelete1() { 
 return confirm("Are you sure you want to delete? You cannot undo this action");   
} 


var counter = 0;
var limit = 1;
function add() {

   if (counter == limit)  {
          alert("First Submit the already added form ");
     }
     else {

        //Create an input type dynamically.
        var element1 = document.createElement("input");
		var element2 = document.createElement("input");
		var element3 = document.createElement("input");
		var element4 = document.createElement("input");
		var element5 = document.createElement("input");
		var element6 = document.createElement("input");
		var element7 = document.createElement("input");
       
        //Assign different attributes to the element.
        element1.setAttribute("type",'text');
        element1.setAttribute("name", 'qty');
        element1.setAttribute("value", '');
		element1.setAttribute("placeholder", 'Quantity');   
		element1.setAttribute("size", '30');
		element1.setAttribute("maxlength", '30');
		
		element2.setAttribute("type",'text');
        element2.setAttribute("name", 'item');
        element2.setAttribute("value", '');
		element2.setAttribute("placeholder", 'Particular');
		element2.setAttribute("size", '30');
		element2.setAttribute("maxlength", '30');
		
		element3.setAttribute("type",'text');
        element3.setAttribute("name", 'rate');
        element3.setAttribute("value", '');
		element3.setAttribute("placeholder", 'Rate');
		element3.setAttribute("size", '30');
		element3.setAttribute("maxlength", '30');
		
		element4.setAttribute("type",'text');
        element4.setAttribute("name", 'paid');
        element4.setAttribute("value", '');
		element4.setAttribute("placeholder", 'Paid by');
		element4.setAttribute("size", '30');
		element4.setAttribute("maxlength", '30');
		
		element5.setAttribute("type",'text');
        element5.setAttribute("name", 'received');
        element5.setAttribute("value", '');
		element5.setAttribute("placeholder", 'Received By');
		element5.setAttribute("size", '30');
		element5.setAttribute("maxlength", '30');
		
		
		element6.setAttribute("type",'text');
        element6.setAttribute("name", 'authorised');
        element6.setAttribute("value", '');
		element6.setAttribute("placeholder", 'Authorised By');
		element6.setAttribute("size", '30');
		element6.setAttribute("maxlength", '30');
		
		element7.setAttribute("type",'text');
        element7.setAttribute("name", 'pvn');
        element7.setAttribute("value", '<?php echo $id2;  ?>');
		element7.setAttribute("placeholder", 'PV Number');
		element7.setAttribute("size", '30');
		element7.setAttribute("maxlength", '30');
		var br = document.createElement("br");
        var foo = document.getElementById("fooBar");
         
        //Append the element in page (in span).
        foo.appendChild(element1);
		
		foo.appendChild(element2);
		
		foo.appendChild(element3);
		
		foo.appendChild(element4);
		
		foo.appendChild(br);
		
		foo.appendChild(element5);
		
		foo.appendChild(element6);
		
		foo.appendChild(element7);
		 counter++;
		
	}	
		
		 
}

function basic5_pay(str5)
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
    document.getElementById("txtHint99").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","exense_plug.php?category="+str5,true);
xmlhttp.send();
}

function show_sub(str)
{
if (str=="")
  {
  document.getElementById("txtHint333").innerHTML="";
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
    document.getElementById("txtHint333").innerHTML=xmlhttp.responseText;
    }
  }
xmlhttp.open("GET","show_sub.php?category="+str,true);
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

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_validateForm() { //v4.0
  var i,p,q,nm,test,num,min,max,errors='',args=MM_validateForm.arguments;
  for (i=0; i<(args.length-2); i+=3) { test=args[i+2]; val=MM_findObj(args[i]);
    if (val) { nm=val.name; if ((val=val.value)!="") {
      if (test.indexOf('isEmail')!=-1) { p=val.indexOf('@');
        if (p<1 || p==(val.length-1)) errors+='- '+nm+' must contain an e-mail address.\n';
      } else if (test!='R') { num = parseFloat(val);
        if (isNaN(val)) errors+='- '+nm+' must contain a number.\n';
        if (test.indexOf('inRange') != -1) { p=test.indexOf(':');
          min=test.substring(8,p); max=test.substring(p+1);
          if (num<min || max<num) errors+='- '+nm+' must contain a number between '+min+' and '+max+'.\n';
    } } } else if (test.charAt(0) == 'R') errors += '- '+nm+' is required.\n'; }
  } if (errors) alert('The following error(s) occurred:\n'+errors);
  document.MM_returnValue = (errors == '');
}

</script>
<style>
/* Global Settings */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f9;
    margin: 0;
    padding: 20px;
    color: #333;
}

/* Table Styles */
table {
    width: 100%;
    max-width: 900px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    margin: 20px auto;
    border-collapse: collapse;
    overflow: hidden;
    margin-right:50px;
}

td, th {
    padding: 15px;
    text-align: left;
}

thead {
    background-color: #0d2074;
    color: white;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}

tr:hover {
    background-color: #f1f1f1;
}

td {
    border-bottom: 1px solid #ddd;
}

/* Form Elements */
input, select {
    padding: 10px;
    margin: 10px 0;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 4px;
    width: 100%;
    font-size: 16px;
    transition: border-color 0.3s ease;
}

input[type="button"], input[type="submit"] {
    background-color:#0d2074;
    color: white;
    border: none;
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 4px;
    transition: background-color 0.3s ease;
}

input[type="button"]:hover, input[type="submit"]:hover {
    background-color: #007bff;
}

input[type="date"] {
    padding: 10px;
    width: auto;
}

select {
    background-color: #fff;
}

input[type="text"]::placeholder {
    color: #888;
}

input:focus, select:focus {
    border-color: #007ACC;
    outline: none;
}

/* Button to Add New Input Fields */
#fooBar input {
    margin-bottom: 10px;
}

#fooBar input:last-of-type {
    margin-bottom: 0;
}

button:hover {
    background-color: #007ACC;
}

/* Error and Message Styles */
.style6 {
    color: #FF0000;
}

.style7 {
    color: #000;
}

/* Miscellaneous Styles */
.description {
    font-weight: bold;
}

.box-row {
    margin: 20px 0;
    padding: 20px;
    background-color: #f4f4f9;
    border-radius: 6px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

form {
    max-width: 600px;
    margin: 0 auto;
}

form label {
    font-weight: bold;
    margin-top: 10px;
    display: block;
}


/* Responsive Design */
@media only screen and (max-width: 600px) {
    table, form {
        width: 100%;
        padding: 0;
    }

    td, th {
        padding: 10px;
    }

    input, select {
        width: 100%;
    }
}

</style>
<!--<link rel="stylesheet" type="text/css" href="view.css" media="all">-->
<script type="text/javascript" src="view.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />

<!--<link rel="stylesheet" type="text/css" href="view.css" media="all">-->
<script type="text/javascript" src="view.js"></script>
<script type="text/javascript" src="calendar.js"></script>
<title>Jsmis Expense Manager</title>
<script type="text/javascript"> function promptMessage() { //display the prompt box to get the value from the user var favColor = prompt("What is your favorite color?", ""); //if the user enters a value display a message in an alert box //with the value that the user entered if (favColor != null){ alert("Your favorite color is " + favColor); } //otherwise display a message informing the user that no value was entered else { alert("You did not specify your favorite color"); } } </script>

</style>
</head>
<body>
    <div class="container">
    <div class="row">
    <div class="col-md-8">
                <h2 style="text-align: center; color: #ffff;">Expense Entry</h2>
	<input type="button" value="Add New" onClick="add()"/>
        <form action="expense_entry.php" method="post" name="form1">
         
        <div align="center">
        	<div class="box-row">
		<div class="box" id="fooBar"> </div>
	                <?php
                    $category = "SELECT Expense_category FROM expense_category
                    where 
                    shopid = '$landlordid'";
                    
                    echo "<select name=category value=''";
                    echo "onchange = 'show_sub(this.value)'";
                    echo "'element select medium' id='element_3'>Category</option>";
                    // printing the list box select command\
                    echo "<option value=>Select Category</option>";
                    
                    $myquery1 = $conn1-> query_database_many($conn,$category);
                    
                    while($nt = $myquery1->fetch_assoc()) {
                    
                    
                    echo "<option value=$nt[Expense_category]>$nt[Expense_category]</option>";
                    /* Option values are added by looping through the array */
                    }
                    echo "</select>";// Closing of list box ?>
                	 <div id="txtHint333">
                	</div>
                <div class="form-group">
                        		<label class="description" for="element_1" style="color:#000000">  Date </label>
                        		<input name="dated" type="date" />
                        		</div>
	
<!-- Category Dropdown (For Site Category) -->
                        <label for="sitetype" style="color:#000000">Select Site:</label>
                        <select id="sitetype" name="sitetype" required>
                            <option value="">Select Site </option>
                            <?php 
                                $sites = $conn->query("SELECT * FROM categories WHERE shopid ='$landlordid' ORDER BY id ASC");
                                if ($sites->num_rows > 0) {
                                    while ($row2 = $sites->fetch_assoc()) {
                                        echo "<option value='{$row2['id']}'>{$row2['id']} : {$row2['name']}</option>";
                                    }
                                } else {
                                    echo "<option selected='' value='' disabled=''>Please check the Sites list.</option>";
                                }
                            ?>
                        </select>

                        <br><br>

                        <!-- House Dropdown -->
                        <label for="house" style="color:#000000">House:</label>
                        <select id="house" name="house" required>
                            <option value="">Sort report by House</option>
                            <!-- Houses will be populated here based on selected category -->
                        </select>

                        <br><br>
<input type="submit" name="Submit2" value="Submit" />
</form>

 <script>
        $(document).ready(function() {
            $('#sitetype').change(function() {
                var categoryId = $(this).val();
                console.log("Selected category ID:", categoryId);  // Debugging: Log the selected category ID

                // Only make AJAX request if a valid category is selected
                if (categoryId) {
                    $.ajax({
                        url: 'fetch_houses.php',
                        type: 'POST',
                        data: { category_id: categoryId },
                        success: function(data) {
                            console.log("Data received:", data);  // Debugging: Log the data received from the server
                            $('#house').html(data); // Populate the house dropdown
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error:", error);  // Log any AJAX errors
                        }
                    });
                } else {
                    $('#house').html('<option value="">Sort report by House</option>'); // Reset if no category is selected
                }
            });
        });
    </script>
    </div>
    </div>
    </div>
    <div class="col-md-4">
    <h2 style="text-align: center; color: #ffff;">Tasks</h2>
	<form id="form1" name="form1" method="post" action="">
      <label>
      <select name="category" onchange="basic5_pay(this.value)">
        <option>Select Task</option>
        <option value="New_category">Add New Category</option>
        <option value="New_sub_category">Add New Sub - Category</option>
        <option value="reports">View Reports</option>
      </select>
      </label>
      </form>   
    </td>
  </tr>
  <tr>
    <td><div id="txtHint99"></div></td>
  </tr>
  <tr>
    <td><div id="txtHint577"></div></td>
  </tr>
  <tr>
    <td><div id="txtHint2"></div></td>
  </tr>
  <tr>
</table>
</div>
</div>
</div>

</body>
</html>