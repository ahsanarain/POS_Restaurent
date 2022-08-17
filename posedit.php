<?php
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include('lib/iq.php');

$user = $_SESSION['user_id'];
$orderno = $_REQUEST['orderno'];
$customer_name = $_REQUEST['cusname'];

if(empty($customer_name)){
$customer_name = "CUSTOMER";
}
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

$tax1 = isset($row_rsMgtList['tax1']) ? $row_rsMgtList['tax1'] : '0' ;
$tax2 = isset($row_rsMgtList['tax2']) ? $row_rsMgtList['tax2'] : '0' ;
$tax3 = isset($row_rsMgtList['tax3']) ? $row_rsMgtList['tax3'] : '0' ;

$horderno = $_POST['HORDERID'];


$updQry = "update order_tab
				set
			  		customer_name = '".$customer_name."', 
					phone = '".$phone."',
					order_type = '".$sdtp."',
					amount = '".$amount."',
					service_charge = '".$servicecharge."', 
					discount = '".$discount."',
					comments = '".$comment."',
					amount_status = '".$amtstatus."',
					plo = '".$plo."',
					usr = '".$user."'
				Where
					order_id = '".$horderno."'
					";
			mysqli_query($cn, $updQry);

			foreach($_POST['record'] as $data){
			$total = $data['qty'] * $data['price'];
			
			// 	price = '".$data['price']."',
			$updSubQry = "update sub_order_tab
						set
						item = '".$data['item']."',	
						qty = '".$data['qty']."',
					
						total = '".$total."'
						where 
						sub_order_id = '".$data['sub_order_id']."' 
						and order_id = '".$horderno."'";
	
				mysqli_query($cn, $updSubQry);
		
		}

if(isset($_POST) && isset($_POST['manual'])){

$item_id ="";
$sub_item_id = "";	
$i=0;
foreach($_POST['manual'] as $data){
			$total = $data['qty'] * $data['price'];
			if($data['item_id']==""){
				$item_id = ($i+1);
			}else{
				$item_id = $data['item_id'];
			}
			if($data['sub_item_id']==""){
				$sub_item_id = ($i+2);
			}else{
				$sub_item_id = $data['sub_item_id'];
			}	
			$insertQry = "insert into sub_order_tab
					  (order_id,item_id,sub_item_id,item,qty,price,total) 
					  values
					  ('".$horderno."','".$item_id."','".$sub_item_id."','".$data['item']."','".$data['qty']."','".$data['price']."','".$total."')";
		
		mysqli_query($cn, $insertQry );
		
		}

}
	

echo "<script>window.open('','_self').close();</script>";


?>