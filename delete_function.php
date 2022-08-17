<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');

$functionCode = $_GET['id'];

mysqli_query($cn, "delete from res_functions where function_code = '$functionCode'");
header("location: function.php?msg=Record+Deleted+Successfully");
?>
