<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');

$itemDeleteId = $_GET['id'];

$res=mysqli_query($cn, "SELECT sub_item_image FROM sub_items WHERE sub_item_id = $itemDeleteId ");
$row=mysqli_fetch_array($res);

unlink("images/subitems/".$row['sub_item_image']);


mysqli_query($cn, "delete from sub_items where sub_item_id = '$itemDeleteId'");
header("location: sub_items.php?msg=Record+Deleted+Successfully");
?>
