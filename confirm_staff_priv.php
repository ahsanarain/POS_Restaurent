<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include ('lib/iq.php');

$sid = $_POST['sid'];
$right = $_POST['right'];
$execute_allowed=$_POST['exe'];
$insert_allowed=$_POST['ins'];
$update_allowed=$_POST['update'];
$delete_allowed=$_POST['del'];
$view_allowed=$_POST['view'];

$sqlCheck = "select count(*) cc from staff_priv where sid = '".$sid."' and function_code = '".$right."'";
$result = mysqli_query($cn, $sqlCheck);
$row = mysqli_fetch_assoc($result);

$recCount  = $row['cc'];
$rightQry = "";
$msg="";

if($recCount>0)
{
    $rightQry =  "UPDATE 
					staff_priv 
				SET 			
					execute_allowed='".$execute_allowed."',
					insert_allowed='".$insert_allowed."',
					update_allowed='".$update_allowed."',
					delete_allowed='".$delete_allowed."',
					view_allowed='".$view_allowed."' 
					WHERE
					sid='".$sid."' and function_code='".$right."'";
    $msg = "Right Updated Successfully";
}
else
{
    $rightQry =  "INSERT into 
					staff_priv 
				 	(
						sid,
						function_code,
						execute_allowed,
						insert_allowed,
						update_allowed,
						delete_allowed,
						view_allowed
					)
					values
					(				
						'".$sid."',
						'".$right."',		
						'".$execute_allowed."',
						'".$insert_allowed."',
						'".$update_allowed."',
						'".$delete_allowed."',
						'".$view_allowed."' 
					)";
    $msg = "Right Created Successfully";
}
mysqli_query($cn, $rightQry);
header("location:add_staff_priv.php?msg=".$msg);
