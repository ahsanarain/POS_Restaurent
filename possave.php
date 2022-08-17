<?php
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include('lib/iq.php');

$user = $_SESSION['user_id'];

$orderno = $_REQUEST['orderno'];
$customer_name = $_REQUEST['cusname'];

if(empty($customer_name))
    $customer_name = "CUSTOMER";
$date_time = $_REQUEST['datetime'];
$phone = $_REQUEST['phno'];
$comment = $_REQUEST['commen'];
$amount = ($_REQUEST['summarytotal'] - $_REQUEST['servicecharge']) + $_REQUEST['discount'];
$servicecharge = $_REQUEST['servicecharge'];
$discount = $_REQUEST['discount'];
$amtstatus = $_REQUEST['amtstatus'];
$sdtp = $_REQUEST['sdtp'];
$plo=0;
if(isset($_REQUEST['plo']) && $_REQUEST['plo']=="on"){
	$plo="1";
};


$query_rsMgtList = "SELECT * FROM tax_tab where status = '1'";
$rsMgtList = mysqli_query($cn, $query_rsMgtList) or die(mysqli_error($cn));
$row_rsMgtList = mysqli_fetch_assoc($rsMgtList);

$tax1 = $row_rsMgtList['tax1'];
$tax2 = $row_rsMgtList['tax2'];
$tax3 = $row_rsMgtList['tax3'];





$insertQry = "insert into order_tab
			  (customer_name,date_time,phone,amount,service_charge,discount,tax1,tax2,tax3,orderno,comments,usr,amount_status,order_type,plo) 
			  values
			  ('$customer_name','$date_time','$phone','$amount','$servicecharge','$discount','$tax1','$tax2','$tax3','order-$orderno','$comment','$user','$amtstatus','$sdtp','$plo')";
			  
	if(mysqli_query($cn, $insertQry ))
	{
		$maxID = "SELECT MAX(ORDER_ID) oid from ORDER_TAB";
		$rID = mysqli_query($cn, $maxID) or die(mysqli_error($cn));
		$arrAuto=array();
		while($data= mysqli_fetch_assoc($rID)){
			$arrAuto[] = $data['oid'];
		}
		$maxID = $arrAuto[0];
		if(!isset($maxID)){
			$maxID = 1;
		}
		
		
		
		foreach($_POST['record'] as $data){
			
			$total = $data['qty'] * $data['price'];
			$insertQry = "insert into sub_order_tab
					  (order_id,item_id,sub_item_id,item,qty,price,total) 
					  values
					  ('".$maxID."','".$data['item_id']."','".$data['sub_item_id']."','".$data['item']."','".$data['qty']."','".$data['price']."','".$total."')";
			
			mysqli_query($cn, $insertQry );
		
		}
		header("location:pos_activity.php?msg=Order+Posted+Successfully.");

	}
	else
	{
		header("location:pos_activity.php?msg=Error+Occured.");
	}  	

//item_id,sub_item_id,item,qty,price,total

?>