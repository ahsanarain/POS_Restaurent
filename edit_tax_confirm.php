<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');

$id = $_GET['id'];

$description = mres($_POST["description"]);
$tax1 =  $_POST["tax1"];
$tax2 =  $_POST["tax2"];
$tax3 =  $_POST["tax3"];
$status = $_POST["status"];
$dt = Date('Y-m-d');
$updMgtQry = "update tax_tab set description = '$description',tax1 = '$tax1' ,tax2 = '$tax2' ,tax3 = '$tax3', status =  '$status', activation_date = '$dt' where tax_id = '$id'";


if(mysqli_query($cn, $updMgtQry))
{
	header("location: tax.php?msg=Tax+Updated+Successfully");
}
else
{
header("location: tax.php?msg=Error+Occured");
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