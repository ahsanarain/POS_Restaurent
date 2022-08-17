<?php
	session_start();	
	include('Connections/cn.php');
	require_once('sessions/mysessionscript.php');
	include('lib/iq.php');
	$sub_item_ing_id = $_GET['sub_item_ing_id'];
	$stock_detail_id = $_GET['stock_detail_id'];
	
	$date = $_REQUEST['date'];

	$q = "SELECT qty_in FROM stock_detail WHERE sub_item_ing_id = '$sub_item_ing_id' and stock_detail_id = '$stock_detail_id' ";
	$res=mysqli_query($cn, $q);
	$row=mysqli_fetch_array($res);
	
	$qty_in = $row['qty_in'];
	
	
	$qu = "update stock set qty_in = qty_in - $qty_in where sub_item_ing_id = '$sub_item_ing_id'";
	mysqli_query($cn, $qu);
	
	$qd = "delete FROM stock_detail WHERE sub_item_ing_id = '$sub_item_ing_id' and stock_detail_id = '$stock_detail_id' ";
	if(mysqli_query($cn, $qd))
	{ 
		header("location: stock_detail_view.php?msg=Record+Deleted+Successfully&txtdate=$date");
	}
?>