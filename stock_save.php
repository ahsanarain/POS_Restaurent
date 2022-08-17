<?php
//Session started and database connection  included here...
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include('lib/iq.php');

$sub_item_ing_id = mres($_POST["itemid"]);
$dat= mres($_POST["dat"]);
$dat = explode("/",$dat);
$d = $dat[0];
$m = $dat[1];
$y = $dat[2];
$dat = $y.'-'.$m.'-'.$d;

$item = mres($_POST["item"]);
$unit = mres($_POST["unit"]);
$in= mres($_POST["in"]);
$balance = mres($_POST["balance"]);
$details = mres($_POST['details']);


$sqlChk = "select count(*) as cc from stock where sub_item_ing_id = '".$sub_item_ing_id."'";
$row_rsauto = mysqli_query($cn, $sqlChk) or die(mysqli_error($cn));
$data= mysqli_fetch_assoc($row_rsauto);
if($data[cc]>0){
    $insertMgtQry = "update stock set qty_in = qty_in + $in where sub_item_ing_id = '".$sub_item_ing_id."'";
	$insertMgtQryDetail = "insert into stock_detail(sub_item_ing_id,dat,item_name,unit,qty_in,details) values('$sub_item_ing_id','$dat','$item','$unit','$in','$details')";
}
else{
	$insertMgtQry = "insert into stock(sub_item_ing_id,dat,item_name,unit,qty_in) values('$sub_item_ing_id','$dat','$item','$unit','$in')";
	$insertMgtQryDetail = "insert into stock_detail(sub_item_ing_id,dat,item_name,unit,qty_in,details) values('$sub_item_ing_id','$dat','$item','$unit','$in','$details')";		
}

if(mysqli_query($cn, $insertMgtQry) && mysqli_query($cn, $insertMgtQryDetail))
{
      header("location:stock.php?msg=Record+Saved+Successfully.");

}
else
{
       header("location:stock.php?msg=Error+Occured.");
}  
?>

<?php
	function mres($value)
	{
	    $search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
	    $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");
	
	    return str_replace($search, $replace, $value);
	}
?>


?>
