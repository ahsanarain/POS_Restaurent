<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');


	$mgtId = $_GET['id'];
	$mgtQry = mysqli_query($cn, "select * from items where item_id = '$mgtId'");
	while($mgtRow=mysqli_fetch_array($mgtQry))
	{
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
function validateQty(event) {
    var key = window.event ? event.keyCode : event.which;
if (event.keyCode == 8 || event.keyCode == 46
 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 9) {
    return true;
}
else if ( key >= 65 && key <=90 || key>=97 && key <=122 || key == 32 || key == 11) {
    return true;
}
else return false;
};

function numbersonly(e){
    var unicode=e.charCode? e.charCode : e.keyCode
    if (unicode!=8)
	{ 
        if (unicode>=48 && unicode <=57 || unicode == 40 || unicode == 41 || unicode == 43 || unicode == 32 || unicode == 11 || unicode == 9 ) 
            return true 
		else
			return false
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
		<form action="edit_items_confirm.php?id=<?php echo $_GET['id']; ?>" method="post" enctype="multipart/form-data" style="margin:0; padding:0;">
        	<table border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td><strong>Name:</strong></td>
                <td><input type="text" name="name" id="date" class="admin-inputBox" size="40" value="<?php echo $mgtRow['item_name']; ?>" onkeypress="return validateQty(event);" /></td>
              </tr>
			  <tr>
			    <td align="left" valign="top"><strong>Image:</strong></td>
			    <td><input type="file" name="file" class="admin-inputBox" accept="image/x-png, image/gif, image/jpeg/"/>
		        <img src="images/items/<?php echo $mgtRow['item_image']; ?>" height="100"  align="right"/></td>
		      </tr>
			  <tr>
			    <td><strong>Status:</strong></td>
			    <td><select name="status" id="status" class="admin-inputBox">
                  <?php 
				 if ($mgtRow['item_status']==1)
				 {
				?>
                  <option value="1" selected="selected">Publish</option>
                  <option value="0"> Un-Publish</option>
                  <?php
				}
				?>
                  <?php
				if ($mgtRow['item_status']==0)
				{
				?>
                  <option value="1">Publish</option>
                  <option value="0" selected="selected"> Un-Publish</option>
                  <?php
				}
				?>
                </select></td>
		      </tr>
			  
              <tr>
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
<?php } ?>
</body>
</html>
