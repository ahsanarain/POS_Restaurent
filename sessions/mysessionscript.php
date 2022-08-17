<?php
//This page will be called while session expiration and this page is included in all pages, from where we log out and want to move //to index.php (login) page..
       
	if (!isset($_SESSION)) session_start();

	if(!(isset($_SESSION['sname']))) header("location:index.php");

	include('Connections/cn.php');
	$userId = $_SESSION['user_id'];
	$userQry = "select * from staff_reg where user_id='$userId'";
	$userRs = mysqli_query($cn, $userQry);
	$userRow = mysqli_fetch_assoc($userRs);
	$userName = $userRow['sname'];

