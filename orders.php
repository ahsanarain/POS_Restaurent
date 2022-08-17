<?php
//Session started and database connection  included here...
session_start();
include('Connections/cn.php');
include('lib/iq.php');
require_once('sessions/mysessionscript.php');
if(!empty($_POST['txtdate'])){
	$string = explode('-',str_replace(' ', '', $_POST['txtdate']));
	$from = $string[0];
	$to = $string[1];
	
	$from = str_replace('/', '-', $from);
	$to = str_replace('/','-',$to);
	
	$fromto = "Point Of Sale Order Report from <font color='green'>'".$from."'</font> to <font color='green'>'".$to."'</font>";
	
	$from = $from;
	$from = date("Y-m-d", strtotime($from));
	
	$to = $to;
	$to = date("Y-m-d", strtotime($to));
        $Orders = "select
                    *
                    from
                    order_tab 
                    where 
                    DATE(date_time) 
                    between  '$from' and '$to' order by order_id desc";
                    
		$row = mysqli_query($cn,$Orders) or die(mysqli_error($cn));
		$arrAuto=array();
		while($data= mysqli_fetch_assoc($row)){
			$arrAuto[] = $data;
		}
	}
$file = ucwords(strtolower(str_replace ("_"," ",basename($_SERVER["SCRIPT_FILENAME"], '.php'))));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$file?></title>
<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/jquery.dataTables.js"></script>
<script src="js/jquery.datepick.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.datepick.css">
<link rel="stylesheet" type="text/css" href="css/jquery.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">

<link href="css/styles.css" type="text/css" rel="stylesheet" />
<script>
$(document).ready(function(){
	$("#txtdate").datepick({
                    rangeSelect: true,
                    dateFormat: 'dd/mm/yyyy',
                    monthsToShow: 2,
                    maxDate: 1,
                    
                });
	
});

</script>
<style>
.table-hover thead tr:hover th, 
 .table-hover tbody tr:hover td {
        background-color: lightyellow;
}
</style>
</head>

<body>
<?php include 'include/header.php' ?>

<div class="admin-greyBg">
<div id="msgs" align="center"><?php if(isset($_GET['msg'])) { echo $_GET['msg']; } ?></div>
  <div id="msg" align="center">
	<?php
		 if(!empty($_POST['txtdate']))
		 {
		 echo "<h2 align='center'>". $fromto ."</h2>";
			}
		
		 ?>
  </div>
  <div class="admin-wrapper">
    <div id="admin-leftNav" class="admin-fLeft">
<?php include 'include/left_nav.php' ?>
    </div>
    <div id="admin-body" class="admin-fRight">
		<h1> Orders Page </h1>
		<form action="orders.php" method="POST">
		 <input name="txtdate" type="text" autocomplete="off" id="txtdate" size="25" class="admin-inputBox">
		 <input type="submit" value="Filter Now" class="admin-button">
		</form>
		<?php 
               
	if(!empty($arrAuto)){
            $string = explode('-',str_replace(' ', '', $_POST['txtdate']));
            $from = $string[0];
            $to = $string[1];
	
            $from = str_replace('/', '-', $from);
            $to = str_replace('/','-',$to);
            
            $from = date("d-m-Y", strtotime($from));
            $to = date("d-m-Y", strtotime($to));
 ?>
                <table border="0" width="100%" cellspacing="0" cellpadding="0" class='table-hover'>
			<thead>
          <tr>
	    <td class="admin-tbHdRow1">Srno</td>
            <td class="admin-tbHdRow1">Cust Name</td>
            <td class="admin-tbHdRow1">Order Date</td>
			<td class="admin-tbHdRow1">Recvd Date</td>
            <td width="21%" class="admin-tbHdRow1">Amt / Srv.Charge / Disc</td>
            <td class="admin-tbHdRow1">User</td>
            <td width="5%" class="admin-tbHdRow1">Amt Status</td>
            <td width="5%" class="admin-tbHdRow1">Order Type</td>
            <td width="3%" class="admin-tbHdRow1 admin-tbHdRow3" align="center">Delete</td>
          </tr>
		  </thead>	 
		 <tbody>
		 <?php $srno=1; foreach($arrAuto as $data) { 
                     $sqlDetail = "select item,qty from sub_order_tab where order_id = '".$data['order_id']."'";
                     $resultDetail = mysqli_query($cn, $sqlDetail);
                        $dataArrDetail = array();
                        while($dataDetail = mysqli_fetch_assoc($resultDetail)){
                                $dataArrDetail[] = $dataDetail;
                        }
                        $str="";
			$bgcolor="";
                        foreach($dataArrDetail as $dt){
                            $str .= $dt['item']." (".$dt['qty'].")\n";
                        }
			if($data['amount_status']=="C"){
				$bgcolor="bgcolor='pink'";
			}else{
				$bgcolor="bgcolor='white'";
			}
                      
                        $amount = $data['amount'];
                        $tax1 = round(($amount * $data['tax1']) / 100,0);
                        $tax2 = round(($amount * $data['tax2']) / 100,0); 
                        $discount = $data['discount'];
                        
                        $amount = $amount - ($tax1 + $tax2 + $discount);
                ?>

            <tr title="<?=strtoupper($str)?>" <?=$bgcolor?>>
		<td class="admin-tbRow1" valign="top"><?=$srno;?></td>
			  <td class="admin-tbRow1" valign="top"><?=$data['customer_name']; ?></td>
			  <?php
				$newDate = date("d-m-Y H:m:i", strtotime($data['date_time']));
				$pr = $data['amount_status'];
				$dst = $data['order_type'];
				$pprr="";
				$ddsstt;
				if($pr=="P")
				{
					$pprr = "Pending";
				}
				if($pr=="R"){
					$pprr = "Received";
				}

				if($dst == "D"){
					$ddsstt = "Delivery";
				}
				if($dst == "T"){
					$ddsstt = "Take away";
				}
				if($dst == "S"){
					$ddsstt = "Service";
				}
			  ?>
			  <td class="admin-tbRow1" valign="top"><?=$newDate; ?> </td>
			  <td class="admin-tbRow1" valign="top"><?= $data['order_rec_date'] != '0000-00-00 00:00:00' ? date('d-m-Y H:m:i',strtotime($data['order_rec_date'])) : "<font color='red'><b>-</b></font>" ?></td>
                          <td class="admin-tbRow1" align="center" valign="top"><?=$amount?> &nbsp;|&nbsp;<?=$data['service_charge']?> &nbsp;|&nbsp; <?=$discount?></td>
			
			  <td class="admin-tbRow1" valign="top"><?=$data['usr']; ?></td>
			  <td class="admin-tbRow1" valign="top"><?=$pprr;?></td>
			  <td class="admin-tbRow1" valign="top"><?=$ddsstt;?></td>
			  
			  
              <td class="admin-tbRow1 admin-tbRow2" align="center" valign="top"><a href="order_del.php?id=<?= $data['order_id']; ?>" title="Delete" class="admin-del" onclick="return confirm('Are you sure you want to perform this action ?');"></a></td>
            </tr>
            <?php $srno++; } ?>
		 </tbody>
        <table>
            <?php
             
	}
	?>
    </div>
    <br clear="all" />
  </div>
</div>
<?php include 'include/footer.php' ?>

</body>
</html>
