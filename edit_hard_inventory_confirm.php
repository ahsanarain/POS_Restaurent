<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include('lib/iq.php');


 $name = $_POST['name']; 
 $desc = $_POST['desc'];
 $qty = $_POST['qty'];
 
 $id = $_POST['id'];
 
 $updQry ="update hard_inventory set inventory_name = '$name', inventory_desc = '$desc', inventory_qty = '$qty' where inventory_id = '$id'";

 if(mysqli_query($cn, $updQry))
{
	header("location: hard_inventory.php?msg=Inventory+Updated+Successfully");
}
else
{
        header("location: hard_inventory.php?msg=Error+Occured");
}

?>