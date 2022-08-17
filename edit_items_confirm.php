<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');

$id = $_GET['id'];

$name = mres($_POST["name"]);
$current_image= mres($_FILES['file']['name']);
$status = $_POST["status"];

$extension = substr(strrchr($current_image, '.'), 1);
 if(!empty($current_image))
	if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "gif") && ($extension != "png"))
	{
		header("location: cms.php?msg=Image+Required.");
		die;
	}
	$time = date("fYhis");

	$new_image = $time . "image" . "." . $extension;
	$image = $new_image;
	$destination="images/items/".$new_image;
	$action = copy($_FILES['file']['tmp_name'], $destination);

	if(!$action)
	{
		$updMgtQry = "update items set item_name = '$name', item_status =  '$status' where item_id = '$id'";
	}	
	else 
	{
		$updMgtQry = "update items set item_name = '$name', item_image='$image' , item_status =  '$status' where item_id = '$id'";

	}

if(mysqli_query($cn, $updMgtQry))
{
	header("location: items.php?msg=Item+Updated+Successfully");
}
else
{
header("location: items.php?msg=Error+Occured");
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