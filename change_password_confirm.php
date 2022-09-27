<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include("lib/iq.php");

$sqlPass ="select password from staff_reg where sid = '".$_SESSION['sid']."' and user_id = '".$_SESSION['user_id']."'";
$rst = mysqli_query($cn, $sqlPass) or die(mysqli_error($cn));
$data = mysqli_fetch_assoc($rst);
$data['password'];
$password = $_POST['password'];
$npassword = $_POST['new_password'];
$cpassword = $_POST['confirm_password'];

if($data['password']== sha1($password) && $npassword == $cpassword)
{
   mysqli_query($cn, "update staff_reg set password = sha1('$npassword') where sid = '".$_SESSION['sid']."' and user_id = '".$_SESSION['user_id']."'"); 
   header("location: change.php?msg=Password+Changed+Successfully!");
}
else
   header("location: change.php?msg=Canot+change+Password+Try+Again!");