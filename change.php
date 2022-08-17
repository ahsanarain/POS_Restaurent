<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Change Password</title>
<link href="css/styles.css" type="text/css" rel="stylesheet" />
<link href="css/jquery-ui.css" type="text/css" rel="stylesheet" />
<script src="js/jquery.js"></script>
<script>
 function validateForm(){
    if($("#password").val()==""){
        alert("Enter Old Password");        
        $("#password").focus();
        return false;
    }
    if($("#new_password").val()==""){
        alert("Enter New Password");
        $("#new_password").focus();
        return false;
    }
    if($("#confirm_password").val()==""){
        alert("Enter Confirm Password");
        $("#confirm_password").focus();
        return false;
    }
    if($("#new_password").val() != $("#confirm_password").val()){
       alert("New and Confirm Password Not matching");
       $("#new_password").val('');
       $("#confirm_password").val('');
       $("#new_password").focus();
       return false;
    }
    
    
    
    
 }
</script>
</head>
<body>
<?php include 'include/header.php' ?>
<div id="msg" align="center"><?php if(isset($_GET['msg'])) { echo $_GET['msg']; } ?></div>
<div class="admin-greyBg">
  <div class="admin-wrapper">
    <div id="admin-leftNav" class="admin-fLeft">
<?php include 'include/left_nav.php' ?>
    </div>
    <div id="admin-body" class="admin-fRight">
		<h1>Change Password </h1> 
		<span id="spanMsg"></span>
		<form action="change_password_confirm.php" method="post" enctype="multipart/form-data" name="frm1" style="margin:0; padding:0;" >
        	<table border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td><strong> Old Password:</strong></td>
                <td>
                    <input type="password" name="password" id="password" class="admin-inputBox">
                </td>
              </tr>
              <tr>
                <td><strong>New Password:</strong></td>
                <td>
                    <input type="password" name="new_password" id="new_password" class="admin-inputBox">
                </td>
              </tr>
              <tr>
                <td><strong>Confirm password:</strong></td>
                <td>
                    <input type="password" name="confirm_password" id="confirm_password" class="admin-inputBox">
                </td>
              </tr>
              
              <tr>
                <td colspan="2">
                  <input type="submit" id="submit" class="admin-button" value="Save" onclick="return validateForm();" />  
                  <input type="button" class="admin-button" value="Cancel" onclick="javascript:history.back(1);" />              	</td>
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
