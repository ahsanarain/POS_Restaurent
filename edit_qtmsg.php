<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');


	$mgtId = $_GET['id'];
	$mgtQry = mysqli_query($cn, "select * from qtmsg where id = '$mgtId'");
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
</head>
<body>
<?php include 'include/header.php' ?>

<div class="admin-greyBg">
  <div class="admin-wrapper">
    <div id="admin-leftNav" class="admin-fLeft">
<?php include 'include/left_nav.php' ?>
    </div>
    <div id="admin-body" class="admin-fRight">
		<h1>Edit QT Footer Message </h1>
		<form action="edit_qtmsg_confirm.php" method="post" enctype="multipart/form-data" style="margin:0; padding:0;">
        	<table border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td><strong>Name:</strong>
                <input type="hidden" value="<?=$_GET['id']?>" name="id">
                </td>
                <td><input type="text" name="message" id="message" class="admin-inputBox" size="40" value="<?php echo $mgtRow['msg']; ?>"/></td>
              </tr>
			  <tr>
			    <td><strong>Status:</strong></td>
			    <td><select name="status" id="status" class="admin-inputBox">
                  <?php 
				 if ($mgtRow['status']==1)
				 {
				?>
                  <option value="1" selected="selected">Publish</option>
                  <option value="0"> Un-Publish</option>
                  <?php
				}
				?>
                  <?php
				if ($mgtRow['status']==0)
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
