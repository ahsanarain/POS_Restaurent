<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include('lib/iq.php');

$item = $_POST['item'];
$amt = $_POST['amt'];
$unit = $_POST['unit'];
$sub_item_ing_id = $_POST['sub_item_ing_id'];
$sub_item_id = $_POST['sub_item_id'];

$updQry ="update sub_item_ing set item = '$item' , amt = '$amt' , unit = '$unit' where sub_item_id = '$sub_item_id' and sub_item_ing_id = '$sub_item_ing_id'";

if(mysqli_query($cn, $updQry))
    header("location: edit_sub_items.php?msg=SubItem+Updated+Successfully&id=$sub_item_id");
else
    header("location: edit_sub_items.php?msg=Error+Occured&id=$sub_item_id");