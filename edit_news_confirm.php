<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include("lib/iq.php");
$id = $_GET['id'];

$heading = mres($_POST["heading"]);
$detail=mres($_POST['detail']);
$status = $_POST["status"];

$updMgtQry = "update notification set nohead = '$heading', nodetail='$detail' , nostatus =  '$status' where noid = '$id'";

if(mysqli_query($cn, $updMgtQry))
{
	header("location: notes.php?msg=Item+Updated+Successfully");
}
else
{
header("location: notes.php?msg=Error+Occured");
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