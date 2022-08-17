<?php
	// *** Validate request to login to this site.
	if (!isset($_SESSION)) {
	  session_start();
	}
	include('Connections/cn.php');
	if(isset($_SESSION)){
		if(isset($_SESSION['sid']) && isset($_SESSION['sname']) && isset($_SESSION['user_id']) && isset($_SESSION['rcode'])){
			header("Location:cms.php");
		}

	}
?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$org_name?></title>
<link href="css/styles.css" type="text/css" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="/images/1.ico">
    <script src="js/sweetalert2.all.min.js"></script>
<script language="javascript" type="text/javascript">
function validateFrm()
{
    if(document.getElementById('loginname').value == "" || document.getElementById('password').value == "")
    {
        Swal.fire({
                icon: 'error',
                title: 'Oops',
                text: 'Please enter the Username/Password!',
        });
        return false;
    }
}
</script>
</head>

<body class="admin-greyBg" style="padding:0 0 0 0;background-color: black;">
    <div class="admin-logo admin-fLeft" style="font-size:38px;padding: 15px;">
        <a href="cms.php?mon=<?=Date('m')?>"><img src="images/logo - small.png" width="150"/></a>
    </div>
  <div class="admin-wrapper" style="padding:20px 0 0 0;">
  <br /><br /><br /><br />  <br /><br /><br /><br /><br /><br />
    <div class="admin-hd">Login To <?=$org_name?> System </div>
    <div class="admin-login">
		<form style="margin:0; padding:0;" name="loginFrm" action="check.php" method="post" onsubmit="return validateFrm();">
        	<table border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td align="right">User Name:</td>
                <td><input type="text" name="loginname" id="loginname" class="admin-inputBox" size="32" autocomplete="off" /></td>
              </tr>
              <tr>
                <td align="right">Password:</td>
                <td><input type="password" name="password" id="password" class="admin-inputBox" size="32" /></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><input type="submit" name="submit" id="submit" class="admin-button" value="Login" /><input type="Reset" name="reset" id="reset" class="admin-button" value="Reset" /></td>
              </tr>
          </table>
        </form>
    </div>
</div>
<?php
if(isset($_GET['loginmsg']))
{
    echo '<script>
    Swal.fire({
      icon: "error",
      title: "Oops",
      text: "'.$_GET['loginmsg'].'",
    });
    </script>';
}
?>
<?php include 'include/footer.php' ?>
</body>
</html>
