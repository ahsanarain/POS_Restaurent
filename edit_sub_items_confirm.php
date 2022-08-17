<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');

$id = $_GET['id'];

$name = mres($_POST["name"]);
$current_image=mres($_FILES['file']['name']);
$category = $_POST['category'];
$price = $_POST["price"];
$status = $_POST["status"];
$dealdetails = $_POST["dealdetails"];

$extension = substr(strrchr($current_image, '.'), 1);
        if(!empty($current_image))
	if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "gif") && ($extension != "png"))
	{
		header("location: sub_items.php?msg=Image+Required.");
		die;
	}
	$time = date("fYhis");

	$new_image = $time . "image" . "." . $extension;
	$image = $new_image;
	$destination="images/subitems/".$new_image;
	$action = copy($_FILES['file']['tmp_name'], $destination);
		
	if(!$action)
	{
		$updMgtQry = "update sub_items set price = '$price', sub_item_name = '$name', sub_item_status =  '$status' , item_id = '$category' , dealdetails = '$dealdetails' where sub_item_id = '$id'";
	}	
	else 
	{
		$updMgtQry = "update sub_items set price = '$price', sub_item_name = '$name', sub_item_image='$image' , sub_item_status =  '$status' , item_id = '$category' , dealdetails = '$dealdetails' where sub_item_id = '$id'";

	}
if(mysqli_query($cn, $updMgtQry))
{
	header("location: sub_items.php?msg=Subitem+Updated+Successfully");
}
else
{
header("location: sub_items.php?msg=Error+Occured");
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