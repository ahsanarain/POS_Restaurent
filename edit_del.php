<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');

$itemDeleteId = $_GET['id'];
$id = $_GET['idd'];

mysqli_query($cn, "delete from sub_item_ing where sub_item_ing_id = '$itemDeleteId'");
header("location: edit_sub_items.php?msg=Record+Deleted+Successfully&id=$id");
