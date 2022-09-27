<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include('lib/iq.php');

$functionCode = $_GET['id'];

$name = mres($_POST["name"]);
$menuname = mres($_POST["menuname"]);
$filename = mres($_POST["filename"]);
$target = mres($_POST["target"]);
$tablename = mres($_POST["tablename"]);
$menulevel = mres($_POST["menulevel"]);
$menutype = mres($_POST["menutype"]);
$menuhead = $_POST['addin'];

$updMgtQry = "update res_functions set function_name = '$name', menu_name =  '$menuname',file_name = '$filename', target = '$target', table_name = '$tablename',menu_level='$menulevel',menu_type='$menutype', menu_head = '$menuhead' where function_code = '$functionCode'";

if(mysqli_query($cn, $updMgtQry)) header("location: function.php?msg=Record+Updated+Successfully");
else header("location: function.php?msg=Error+Occured");
?>
<?php
function mres($value)
{
	$search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
	$replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");

	return str_replace($search, $replace, $value);
}
?>