<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');

$id = mres($_GET["id"]);
$insertMgtQry = "delete from customer where customer_id ='$id'";

if(mysqli_query($cn, $insertMgtQry)) header("location:customer.php?msg=Record+Deleted+Successfully.");
else header("location:customer.php?msg=Error+Occured.");
?>
<?php
function mres($value)
{
	$search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
	$replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");
	return str_replace($search, $replace, $value);
}
?>