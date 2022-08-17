<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');

$itemDeleteId = $_GET['id'];



mysqli_query($cn, "delete from sub_order_tab where order_id = '$itemDeleteId'");
mysqli_query($cn, "delete from order_tab where order_id = '$itemDeleteId'");
header("location: orders.php?msg=Record+Deleted+Successfully");
?>
