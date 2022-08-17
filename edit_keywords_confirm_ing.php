<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include('lib/iq.php');


 $name = $_POST['name']; 
 $status = $_POST['status'];
 $details = $_POST['details'];
 
 $id = $_POST['id'];
 
 $updQry ="update keywords_ing set keyword_name = '$name', status = '$status', keyword_details = '$details' where keyword_id = '$id'";

 if(mysqli_query($cn, $updQry))
{
	header("location: keyword_ing.php?msg=Keyword+Updated+Successfully");
}
else
{
        header("location: keyword_ing.php?msg=Error+Occured");
}

?>