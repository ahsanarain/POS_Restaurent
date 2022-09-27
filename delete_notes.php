<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');

$itemDeleteId = $_GET['id'];

mysqli_query($cn, "delete from notification where noid = '$itemDeleteId'");
header("location: notes.php?msg=Record+Deleted+Successfully");
