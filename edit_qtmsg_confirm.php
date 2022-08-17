<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');

$id = $_POST['id'];
$message = mres($_POST["message"]);
$status = $_POST["status"];
$updMgtQry = "update qtmsg set msg = '$message', status =  '$status' where id = '$id'";

if(mysqli_query($cn, $updMgtQry))
{
	header("location: qtmsg.php?msg=Message+Updated+Successfully");
}
else
{
header("location: qtmsg.php?msg=Error+Occured");
}
?>

<?php
	function mres($value)
	{
	    $search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
	    $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");
	
	    return str_replace($search, $replace, $value);
	}
?>