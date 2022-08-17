<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include('lib/iq.php');

$date = mres($_POST["date"]);
$rdate = $date;
$date = str_replace('/', '-', $date);
$date = date("Y-m-d", strtotime($date));

$sale = mres($_POST["sale"]);
$sc = mres($_POST["service_charge"]);
$tax3 = $_POST['tax3'];
$exp = mres($_POST["expanse"]);
$dis = mres($_POST["discount"]);
$pdp = mres($_POST["previous_day_petty"]);
$owner = mres($_POST["owner"]);



$sqlCheck = "select count(*) cc from pt where pt_date = '$date'";
$result = mysqli_query($cn, $sqlCheck);
	$dataArr = array();
	while($data = mysqli_fetch_assoc($result)){
		$dataArr[] = $data;
	}
 if($dataArr[0]['cc']>=1){
     $sqlpickamount="select pt_main from pt where pt_date = '$date'";
     $result = mysqli_query($cn, $sqlpickamount);
	$dataArr = array();
	while($data = mysqli_fetch_assoc($result)){
		$dataArr[] = $data;
	}
        $updatedamount = $dataArr[0]['pt_main'] + $owner;
     $sqlUpdate = "update 
	 					pt 
						set 
						pt_sale = '$sale',
						pt_sc = '$sc' ,
						tax3 = '$tax3',
						pt_pdpt = '$pdp' ,
						pt_exp = '$exp',
						pt_disc = '$disc',
						pt_main = '$updatedamount' 
						
						where pt_date = '$date'
	 			  ";
     mysqli_query($cn, $sqlUpdate);
     header("location:pt.php?msg=Record+Updated+Successfully.&txtdate=$rdate");
     
 }
else{
    $insertMgtQry = "insert into pt(pt_date,pt_sale,pt_sc,tax3,pt_pdpt,pt_exp,pt_disc,pt_main)
	                 values('$date','$sale','$sc','$tax3','$pdp','$exp','$dis','$owner')";
		
		if(mysqli_query($cn, $insertMgtQry))
		{
			header("location:pt.php?msg=Record+Saved+Successfully.&txtdate=$rdate");

		}
		else
		{
			header("location:pt.php?msg=Error+Occured.&txtdate=$rdate");
		} 
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


