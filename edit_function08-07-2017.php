<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include('lib/iq.php');

	$functionCode = $_GET['id'];
	$mgtQry = mysqli_query($cn, "select * from res_functions where function_code = '$functionCode'");
	
       $mgtRow=mysqli_fetch_array($mgtQry);
       
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$org_name?></title>
<link href="css/styles.css" type="text/css" rel="stylesheet" />
<script src="js/jquery.js"></script>
    <script language="JavaScript" type="text/javascript" src="js/tiny_mce.js"></script>
	<script language="JavaScript" type="text/javascript" src="js/custom_config.js"></script>
	
<link href="css/jquery-ui.css" type="text/css" rel="stylesheet" />
<script>
$(document).ready(function(){
	$("#name").focus();
});
function validateQty(event) {
    var key = window.event ? event.keyCode : event.which;
if (event.keyCode == 8 || event.keyCode == 46
 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 9) {
    return true;
}
else if ( key >= 65 && key <=90 || key>=97 && key <=122 || key == 32 || key == 11 || key == 46 || key == 95) {
    return true;
}
else return false;
};

function numbersonly(e){
    
    var unicode=e.charCode? e.charCode : e.keyCode;
    if(unicode!=8)
    {
        if(unicode>=48 && unicode <=57 || unicode == 9)
        {
            return true;
        }
	else
        {
            return false;
        }
    }
}

function validateForm()
{
		var flag=0;
		var fields="";
		var name = document.getElementById('name');
                var menuname = document.getElementById('menuname');
                var filename = document.getElementById('filename');
			
	if(name.value=="")
	fields=fields+"Name, ";
	else
	flag++;
	
    if(menuname.value=="")
	fields=fields+"Menu Name, ";
	else
	flag++;
    
    if(filename.value=="")
	fields=fields+"and File Name. ";
	else
	flag++;
    
    
    

	
if(flag==3)
//document.frm1[0].submit();
	return true;
	else
	{
//alert("Please correct the following fields: "+fields);
		document.getElementById('spanMsg').innerHTML = "Please correct the following field. "+fields;
		document.getElementById('spanMsg').style.background="#FFFFD7";
	return false;
	} 
}
</script>

</head>
<body>
<?php include 'include/header.php' ?>

<div class="admin-greyBg">
  <div class="admin-wrapper">
    <div id="admin-leftNav" class="admin-fLeft">
<?php include 'include/left_nav.php' ?>
    </div>
    <div id="admin-body" class="admin-fRight">
		<h1>Edit Main Menu Item </h1>
		<form action="edit_function_confirm.php?id=<?php echo $_GET['id']; ?>" method="post" enctype="multipart/form-data" style="margin:0; padding:0;">
        	<table border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td><strong><span class="redColor">*</span> Name:</strong></td>
                <td><input type="text" name="name" id="name" value="<?=$mgtRow['function_name'];?>" class="admin-inputBox" maxlength="50" size="40" onkeypress="return validateQty(event);"/></td>
              </tr>
              <tr>
                <td><strong><span class="redColor">*</span>Menu Name:</strong></td>
                <td> 
                    <input type="text" name="menuname" id="menuname" value="<?=$mgtRow['menu_name'];?>" class="admin-inputBox" maxlength="50" size="40" onkeypress="return validateQty(event);"/>
                </td>
              </tr>
              <tr>
                <td><strong><span class="redColor">*</span>File Name:</strong></td>
                <td>
                    <input type="text" name="filename" id="filename" value="<?=$mgtRow['file_name'];?>" class="admin-inputBox" maxlength="50" size="40" onkeypress="return validateQty(event);"/>
                </td>
              </tr>
              <tr>
                <td><strong><span class="redColor">*</span>Target:</strong></td>
                <td>
                    <select name="target" id="target" class="admin-inputBox">
                        <option value="">Self</option>
                        <option value="_blank">Other Window</option>
                    </select>
                </td>
              </tr>
              <tr>
                <td><strong>Table Name</strong></td>
                <td>
                    <input type="text" name="tablename" id="tablename" value="<?=$mgtRow['table_name'];?>" class="admin-inputBox" maxlength="50" size="40" onkeypress="return validateQty(event);"/>
                </td>
              </tr>
              <tr>
                <td><strong>Menu Level</strong></td>
                <td>
                    <input type="text" readonly name="menulevel" value="<?=$mgtRow['menu_level'];?>" id="menulevel" class="admin-inputBox" maxlength="50" size="40" onkeypress="return validateQty(event);"/>
                </td>
              </tr>
              <tr>
                <td><strong>Menu Type</strong></td>
                <td>
                    <input type="text" readonly name="menutype" id="menutype" value="<?=$mgtRow['menu_type'];?>" class="admin-inputBox" maxlength="50" size="40" onkeypress="return validateQty(event);"/>
                </td>
              </tr>              <tr>
                <td colspan="2">
                	<input type="submit" id="submit" class="admin-button" value="Save" /> 
                    <input type="button" class="admin-button" value="Cancel" onclick="javascript:history.back(1);" />				</td>
              </tr>
            </table>
      </form>
    </div>
    <br clear="all" />
  </div>
</div>
<?php include 'include/footer.php' ?>
</body>
</html>
