<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');

$id = mres($_GET["id"]);
$insertMgtQry = "delete from  keywords_ing where keyword_id ='$id'";

if(mysqli_query($cn, $insertMgtQry))
	header("location:keyword_ing.php?msg=Record+Deleted+Successfully.");
else
	header("location:keyword_ing.php?msg=Error+Occured.");
?>

<?php
function mres($value)
{
	$search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
	$replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");
	return str_replace($search, $replace, $value);
}
?>
