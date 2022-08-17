<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');

$itemDeleteId = $_GET['id'];

$res=mysqli_query($cn, "SELECT item_image FROM items WHERE item_id = $itemDeleteId ");
$row=mysqli_fetch_array($res);

unlink("images/items/".$row['item_image']);


mysqli_query($cn, "delete from items where item_id = '$itemDeleteId'");
header("location: items.php?msg=Record+Deleted+Successfully");
?>
