<?php
//Session started and database connection  included here...
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include('lib/iq.php');

$sqlNote = "select * from notification where nostatus = '1'";
$result = mysqli_query($cn, $sqlNote);
$dataArr = array();
while($data = mysqli_fetch_assoc($result)){
        $dataArr[] = $data;
}


$from = date('Y-m-d');
$fromto = $from;

if(!empty($_POST['txtdate'])){
	$string = str_replace(' ', '', $_POST['txtdate']);

	$from = str_replace('/', '-', $string);
	
	$fromto = $from;
	
	$from = $from;
	$from = date("Y-m-d", strtotime($from));
	
}
	
if( $_SESSION['user_id']=="thames"){
	$query_rsFilterDisplay = "select sub_item_name ,sub_item_image, max(a.qty) qty,a.sub_item_id
from 
(
SELECT 
	
	DATE(main.date_time) date_time,
    si.sub_item_id,
	si.sub_item_name,
	si.sub_item_image,
    sum(sub.qty) qty,
    sum(sub.price) price
FROM 
	order_tab main,
    sub_order_tab sub,
    sub_items si
WHERE
	main.order_id = sub.order_id
    and sub.item_id = si.item_id
   and DATE(main.date_time) = '$from'
   group by si.sub_item_id
   order by qty
   ) a
 UNION
 select sub_item_name ,sub_item_image,min(a.qty) qty,a.sub_item_id
from 
(
SELECT 
	
	DATE(main.date_time) date_time,
    si.sub_item_id,
	si.sub_item_name,
	si.sub_item_image,
    sum(sub.qty) qty,
    sum(sub.price) price
FROM 
	order_tab main,
    sub_order_tab sub,
    sub_items si
WHERE
	main.order_id = sub.order_id
    and sub.item_id = si.item_id
   and DATE(main.date_time) = '$from'
   group by si.sub_item_id
   order by qty
   ) a
   ";

		$rsFilterDisplay = mysqli_query($cn, $query_rsFilterDisplay) or die(mysqli_error($cn));

		$arrF = array();
		while($row_rsFilterDisplay = mysqli_fetch_assoc($rsFilterDisplay)){
			$arrF[]=$row_rsFilterDisplay;
		}
			
	$total_orders = "SELECT count(*) total_orders from order_tab where DATE(date_time) = '$from'";
	$rsTotal_orders = mysqli_query($cn, $total_orders) or die(mysqli_error($cn));
	$rowTotal_orders = mysqli_fetch_assoc($rsTotal_orders);
	
	$total_deliveries = "SELECT count(*) total_deliveries from order_tab where DATE(date_time) = '$from' and order_type = 'D'";
	$rsTotal_deliveries = mysqli_query($cn, $total_deliveries) or die(mysqli_error($cn));
	$rowTotal_deliveries = mysqli_fetch_assoc($rsTotal_deliveries);
	
	$total_service = "SELECT count(*) total_service from order_tab where DATE(date_time) = '$from' and order_type = 'S'";
	$rsTotal_service = mysqli_query($cn, $total_service) or die(mysqli_error($cn));
	$rowTotal_service = mysqli_fetch_assoc($rsTotal_service);
	
	$total_take = "SELECT count(*) total_take from order_tab where DATE(date_time) = '$from' and order_type = 'T'";
	$rsTotal_take = mysqli_query($cn, $total_take) or die(mysqli_error($cn));
	$rowTotal_take = mysqli_fetch_assoc($rsTotal_take);
	
	$total_pending = "SELECT count(*) total_pending from order_tab where DATE(date_time) = '$from' and order_type = 'P'";
	$rsTotal_pending = mysqli_query($cn, $total_pending) or die(mysqli_error($cn));
	$rowTotal_pending = mysqli_fetch_assoc($rsTotal_pending);
	
}
$file = ucwords(strtolower(str_replace ("_"," ",basename($_SERVER["SCRIPT_FILENAME"], '.php'))));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$file?></title>
<link href="css/styles.css" type="text/css" rel="stylesheet" />
<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/jquery.datepick.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.datepick.css">
<link rel="stylesheet" type="text/css" href="css/jquery.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<script>
$(document).ready(function(){
$("#txtdate").datepick({
                    rangeSelect: false,
                    dateFormat: 'yyyy/mm/dd',
                    monthsToShow:1,
                    maxDate: 1
                    
        });
});
</script>
<style>
.table-hover thead tr:hover th, 
 .table-hover tbody tr:hover td {
        background-color: lightyellow;
}
</style>
<link href="css/styles.css" type="text/css" rel="stylesheet" />
<style type="text/css">
<!--
.style2 {color: #006600}
.style4 {color: #CACA00; }
.style5 {color: #003399; }
.style6 {color: #000000}
.style7 {color: #669900}
-->
</style>
</head>
<body>
<?php include 'include/header.php' ?>
<div class="admin-greyBg">

  <div class="admin-wrapper">
    <div id="admin-leftNav" class="admin-fLeft">
	
<?php include 'include/left_nav.php' ?>
    </div>
    <div id="admin-body" class="admin-fRight">
       <h1>Statistics</h1>
	   <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
         <input name="txtdate" autocomplete="off" type="text" value="<?=$from?>" id="txtdate" size="25" class="admin-inputBox">
		 <input type="submit" value="Filter Now" class="admin-button">
	  </form>  
<table border="0" width="100%" align="center">
	<tr>
	  <td colspan="3" align="center" valign="top">
	  <?="<h2 align='center'>Hot & Less Item Sold Report on <font color='green'>'".$fromto."'</font></h2>"?>	  </td>
	  </tr>
	<tr>
		<td width="34%" align="center" valign="top">
				<table width="98%" border="0" cellpadding="2" cellspacing="2">
					<tr>
					  <td height="33" bgcolor="#FFEEEA"><h2 align="center"><font color="red">Hot Sold Item Of the Day</font></h2></td>
					</tr>
					<tr>
					  <td height="22" bgcolor="#FFEEEA"><h2 align="center"><font color="red"><?=isset($arrF[0]['sub_item_name'])? $arrF[0]['sub_item_name'] : '-' ?></font></h2></td>
					</tr>
					<tr>
						<td height="22" align="center" valign="middle" bgcolor="#FFEEEA"><?=isset($arrF[0]['sub_item_image']) ? "<img width='120' height='120' src='images/subitems/".$arrF[0]['sub_item_image']."'" : "-" ?></td>
					</tr>
					<tr>
					  <td height="33" bgcolor="#FFEEEA"><h2 align="center"> <font color="red"> QTY : <?= isset($arrF[0]['qty']) ? $arrF[0]['qty'] : "-" ?></font> </h2></td>
					</tr>
			</table>		</td>
    	<td width="34%" align="center" valign="top"><table width="98%" border="0" cellpadding="2" cellspacing="2">
          <tr>
            <td height="33" bgcolor="#EAFFEA"><h2 align="center"><font color="green">Less Sold Item Of the Day</font></h2></td>
          </tr>
          <tr>
            <td height="22" bgcolor="#EAFFEA"><h2 align="center"><font color="green">
              <?=isset($arrF[1]['sub_item_name'])? $arrF[1]['sub_item_name'] : '-' ?>
            </font></h2></td>
          </tr>
          <tr>
            <td height="22" align="center" valign="middle" bgcolor="#EAFFEA"><?=isset($arrF[1]['sub_item_image']) ? "<img width='120' height='120' src='images/subitems/".$arrF[1]['sub_item_image']."'" : "-" ?>            </td>
          </tr>
          <tr>
            <td height="33" bgcolor="#EAFFEA"><h2 align="center"><font color="green">QTY :
              <?= isset($arrF[1]['qty']) ? $arrF[1]['qty'] : "-" ?>
            </font></h2></td>
          </tr>
        </table></td>
    	<td width="32%" align="center" valign="top"><table width="98%" border="0" cellpadding="2" cellspacing="2">
          <tr>
            <td align="left" valign="top" bgcolor="#F3FFF0" class="style2"><h3>Total Deliveries </h3></td>
            <td align="center" valign="middle" bgcolor="#F3FFF0" class="style2"><h3>:</h3></td>
            <td height="22" align="right" valign="top" bgcolor="#F3FFF0" class="style2"><h3>
<?=isset($rowTotal_deliveries['total_deliveries']) ? $rowTotal_deliveries['total_deliveries'] : "0"?>
			
			</h3></td>
          </tr>
          <tr>
            <td align="left" valign="top" bgcolor="#ECF3FF" class="style5"><h3>Total Service </h3></td>
            <td align="center" valign="middle" bgcolor="#ECF3FF" class="style5"><h3>:</h3></td>
            <td height="22" align="right" valign="top" bgcolor="#ECF3FF" class="style5"><h3>
<?=isset($rowTotal_service['total_service']) ? $rowTotal_service['total_service'] : "0"?>
			
			</h3></td>
          </tr>
          <tr>
            <td align="left" valign="top" bgcolor="#F0F0F0"><h3 class="style6">Total Take Aways </h3></td>
            <td align="center" valign="middle" bgcolor="#F0F0F0"><h3 class="style6">:</h3></td>
            <td height="33" align="right" valign="top" bgcolor="#F0F0F0"><h3 class="style6">
<?=isset($rowTotal_take['total_take']) ? $rowTotal_take['total_take'] : "0"?>
						
			</h3></td>
          </tr>
		 <tr>
            <td width="75%" align="left" valign="top" bgcolor="#FFFFFF" class="redColor"><h3 class="style7">Total Pending Orders </h3></td>
            <td width="6%" align="center" valign="middle" bgcolor="#FFFFFF" class="redColor"><h3 class="style7">:</h3></td>
            <td width="19%" height="29" align="right" valign="top" bgcolor="#FFFFFF" class="style7"><h3><?=isset($rowTotal_pending['total_pending']) ? $rowTotal_pending['total_pending'] : "0"?></h3></td>
          </tr>	
		  <tr>
            <td width="75%" align="left" valign="top" bgcolor="#FFECF1" class="redColor"><h3>Total Orders </h3></td>
            <td width="6%" align="center" valign="middle" bgcolor="#FFECF1" class="redColor"><h3>:</h3></td>
            <td width="19%" height="29" align="right" valign="top" bgcolor="#FFECF1" class="redColor"><h3><?=isset($rowTotal_orders['total_orders']) ? $rowTotal_orders['total_orders'] : "0"?></h3></td>
          </tr>
        </table></td>
	</tr>
	<tr>
	  <td align="center" valign="top">&nbsp;</td>
	  <td align="center" valign="top">&nbsp;</td>
	  <td align="center" valign="top">&nbsp;</td>
	  </tr>
	<tr>
	  <td align="center" valign="top"><table width="98%" border="0" cellpadding="2" cellspacing="2">
        <tr>
          <td height="33" bgcolor="#FFFFEC">&nbsp;</td>
        </tr>
        <tr>
          <td height="22" bgcolor="#FFFFEC">&nbsp;</td>
        </tr>
        <tr>
          <td height="22" align="center" valign="middle" bgcolor="#FFFFEC">&nbsp;</td>
        </tr>
        <tr>
          <td height="33" bgcolor="#FFFFEC">&nbsp;</td>
        </tr>
      </table></td>
	  <td align="center" valign="top"><table width="98%" border="0" cellpadding="2" cellspacing="2">
        <tr>
          <td height="33" bgcolor="#FFFFEC">&nbsp;</td>
        </tr>
        <tr>
          <td height="22" bgcolor="#FFFFEC">&nbsp;</td>
        </tr>
        <tr>
          <td height="22" align="center" valign="middle" bgcolor="#FFFFEC">&nbsp;</td>
        </tr>
        <tr>
          <td height="33" bgcolor="#FFFFEC">&nbsp;</td>
        </tr>
      </table></td>
	  <td align="center" valign="top"><table width="98%" border="0" cellpadding="2" cellspacing="2">
        <tr>
          <td height="33" bgcolor="#FFFFEC">&nbsp;</td>
        </tr>
        <tr>
          <td height="22" bgcolor="#FFFFEC">&nbsp;</td>
        </tr>
        <tr>
          <td height="22" align="center" valign="middle" bgcolor="#FFFFEC">&nbsp;</td>
        </tr>
        <tr>
          <td height="33" bgcolor="#FFFFEC">&nbsp;</td>
        </tr>
      </table></td>
	  </tr>
	<tr>
	  <td align="center" valign="top">&nbsp;</td>
	  <td align="center" valign="top">&nbsp;</td>
	  <td align="center" valign="top">&nbsp;</td>
	  </tr>
	<tr>
	  <td align="center" valign="top">&nbsp;</td>
	  <td align="center" valign="top">&nbsp;</td>
	  <td align="center" valign="top">&nbsp;</td>
	  </tr>
</table>
    </div>
    <br clear="all" />
  </div>
</div>
<br /><br />
<?php include 'include/footer.php' ?>
</body>
</html>
