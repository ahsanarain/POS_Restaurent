<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include('lib/iq.php');

$name = $_POST['name'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$id = $_GET['id'];

$updQry ="update customer set customer_name = '$name', customer_phone = '$phone', customer_address = '$address' where customer_id = '$id'";

if(mysqli_query($cn, $updQry)) header("location: customer.php?msg=Customer+Updated+Successfully");
else header("location: customer.php?msg=Error+Occured");