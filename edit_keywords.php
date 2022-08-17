<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include('lib/iq.php');

$id = $_GET['id'];
$query_rsMgtList = "SELECT * FROM keywords where keyword_id = '$id'";
$rsMgtList = mysqli_query($cn, $query_rsMgtList) or die(mysqli_error($cn));
$row_rsMgtList = mysqli_fetch_assoc($rsMgtList);
$totalRows_rsMgtList = mysqli_num_rows($rsMgtList);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$org_name?></title>
<link href="css/styles.css" type="text/css" rel="stylesheet" />
<link href="css/jquery-ui.css" type="text/css" rel="stylesheet" />

<script src="js/jquery.js"></script>


<script language="javascript" type="text/javascript">
$(document).ready(function(){
	$("#name").focus();
});
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
        <h1>Add Keyword </h1>
        <form action="edit_keywords_confirm.php" method="post" enctype="multipart/form-data" name="frm1" style="margin:0; padding:0;" >
        	<table border="0" cellspacing="4" cellpadding="4">
              <tr>
                  <td><strong>Name:</strong></td>
                <td>
                    <input type="hidden" value="<?=$id?>" name="id">
                    <input type="text" value="<?=$row_rsMgtList['keyword_name']?>" autocomplete="off" name="name" id="name" class="admin-inputBox" maxlength="50" size="40" onkeypress="return validateQty(event);"/></td>
              </tr>
              <tr>
                <td><strong>Status:</strong></td>
                <td>
                    <select name="status" id="status" class="admin-inputBox">
                        <option value="1" <?=($row_rsMgtList['status']=="1")? "selected='selected'" : ""?>>Publish</option>
			<option value="0" <?=($row_rsMgtList['status']=="0")? "selected='selected'" : ""?>>Un-Publish</option>
                    </select>
                </td>
              </tr>
              <tr>
                <td><strong>Details:</strong></td>
                <td>
				<label>
                  <input type="text" autocomplete="off" value="<?=$row_rsMgtList['keyword_details']?>" name="details" id="details" maxlength="50" size="40" class="admin-inputBox">
                </label>				</td>
              </tr>
              <tr>
                <td colspan="2">
                  <input type="submit" id="submit" class="admin-button" value="Save" onclick="return validateForm();" />  
                  <input type="reset" class="admin-button" value="Cancel"/>
                </td>
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
