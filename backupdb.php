<?php
//Session started and database connection  included here...
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include('lib/iq.php');

$dbname = $database_cn;
$backuped = $database_cn.date('dmyhis').".sql";
$dir = "d:/xampp/mysql/bin/";
$pathto = "d:/databasebackups/".$backuped;
$msg="";
if (isset($_POST['backup'])){
		if(file_exists($pathto)){	
			$msg="Backup Already Taken";
	}else{
		$exe = $dir."mysqldump.exe --user=".$username_cn." --password=".$password_cn." ".$dbname." > ".$pathto;
		exec($exe);
		$msg="Backup Taken Successfully";
		
	}
}
$file = ucwords(strtolower(str_replace ("_"," ",basename($_SERVER["SCRIPT_FILENAME"], '.php'))));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$file?></title>
<link href="css/styles.css" type="text/css" rel="stylesheet" />
<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/jquery.datepick.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.datepick.css">
<link rel="stylesheet" type="text/css" href="css/jquery.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">

<style>
.table-hover thead tr:hover th, 
 .table-hover tbody tr:hover td {
        background-color: lightyellow;
}
</style>
<link href="css/styles.css" type="text/css" rel="stylesheet" />
</head>
<body>
<?php include 'include/header.php' ?>
<div class="admin-greyBg">
  <div class="admin-wrapper">
    <div id="admin-leftNav" class="admin-fLeft">
<?php include 'include/left_nav.php' ?>
    </div>
	<div id="msg" align="center"><?php if(isset($msg)) { echo $msg; } ?></div>
    <div id="admin-body" class="admin-fRight">
       <h1>Backup Database</h1>
	   <form method="post">
	   <table border="0" width="73%" cellpadding="4" cellspacing="4">
			<tr>
				<td><h3><strong>Backup</strong></h3></td>
				<td><input type="radio" checked="checked" name="backup"></td>
			</tr>
			<tr>
			  <td colspan="2">
				<input type="submit" value="Backup <?=$org_name?> Database" class="admin-button">
				<br /><br />
				<b>Note:</b> First Create a folder by the name <font color="red"><b>"databasebackups"</b></font> in <font color="red"><b>"D: drive" </b></font>
				then perform operation.				</td>
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
