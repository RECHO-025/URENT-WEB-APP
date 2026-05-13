<?php
include 'index.php';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>MyShop MIS</title>
<style type="text/css">
<!--
.style1 {font-weight: bold}
.style2 {font-weight: bold}
.style3 {color: #000000}
-->
</style>
<script type="text/JavaScript">
<!--
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
//-->
</script>
</head>
<body>

<?php

 $id = $_POST['id'];
 $id2 = $_POST['id2'];
 $name = $_POST['name'];
  $name2 = $_POST['name2'];
							 
?>
<form action="update_category.php" method="post" name="form1" id="form1">
      <table width="576" height="102" border="0" align="center">
        <tr>
          <td height="21" colspan="2" nowrap="nowrap">
            <p> Change values, then click the Save Changes button </p>
          </td>
</tr>
        <tr>
          <td width="147" height="21" nowrap="nowrap" class="navText"><strong>Name</strong></td>
          <td width="203" nowrap="nowrap" class="navText"><div align="center" class="style3">
              <div align="left">
                <div align="center" class="style3">
                  <div align="left">
                    <input name="id" type="hidden" readonly="readonly" value="<?php echo $id ; ?>" />
					<input name="id2" type="hidden" readonly="readonly" value="<?php echo $id2 ; ?>" />
					<input name="name2" type="hidden" readonly="readonly" value="<?php echo $name2 ; ?>" />
					 <br />
					<textarea name="name" cols="" rows=""><?php echo $name ; ?></textarea>
                  </div>
                </div>
              </div>
          </div></td>
        </tr>
           <tr>
          <td height="45" nowrap="nowrap" class="navText">&nbsp;</td>
          <td nowrap="nowrap" class="navText"><label>
              <input type="submit" name="Submit" value="Save Changes" />
          </label></td>
        </tr>
  </table>
</form>
</body>
</html>
