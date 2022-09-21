<?php
//Session started and database connection  included here...
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php'); 
if(!empty($_POST['txtdate'])){
	$string = explode('-',str_replace(' ', '', $_POST['txtdate']));
	$from = $string[0];
	$to = $string[1];
	
	$from = str_replace('/', '-', $from);
	$to = str_replace('/','-',$to);
	
	$fromto = "Point Of Sale Report from <font color='green'>'".$from."'</font> to <font color='green'>'".$to."'</font>";
	$afromto = $from.'~'.$to;
	$from = $from;
	$from = date("Y-m-d", strtotime($from));
	
	$to = $to;
	$to = date("Y-m-d", strtotime($to));

	$query_rsFilterDisplay = "SELECT
								SUM(amount) AS AMOUNT,
								SUM(service_charge) AS SERVICE_CHARGE,
								SUM(discount) AS DISCOUNT,
                                                                ROUND(AVG(tax1),0) AS TAX1,
                                                                ROUND(AVG(tax2),0) AS TAX2,
																ROUND(AVG(tax3),0) AS TAX3
								from
								order_tab 
								where
								amount_status not in ('C','P') and 
								date(date_time) 
								between  '$from' and '$to'";
        
        
	
	$rsFilterDisplay = mysqli_query($cn, $query_rsFilterDisplay) or die(mysqli_error($cn));
	$row_rsFilterDisplay = mysqli_fetch_assoc($rsFilterDisplay);
    $totalRows_rsFilterDisplay = mysqli_num_rows($rsFilterDisplay);
	$tr = $totalRows_rsFilterDisplay;
        
        
        
        
        $tx1 = $row_rsFilterDisplay['TAX1'];
        $tx2 = $row_rsFilterDisplay['TAX2'];
		$tx3 = $row_rsFilterDisplay['TAX3'];
        
       	
	///////////////////////////////////////////////////////////////////////////////////////////////////
	
	$query_rsFilterDisplay1 = "SELECT
								SUM(exp_amount) AS AMOUNT
								FROM
								exp_tab 
								WHERE 
								DATE(exp_date) 
								BETWEEN  '$from' and '$to'";
	
	
       
        
    $rsFilterDisplay1 = mysqli_query($cn, $query_rsFilterDisplay1) or die(mysqli_error($cn));
	$row_rsFilterDisplay1 = mysqli_fetch_assoc($rsFilterDisplay1);
	$totalRows_rsFilterDisplay1 = mysqli_num_rows($rsFilterDisplay1);
	$tr1 = $totalRows_rsFilterDisplay1;
	
	
	
	///////////////////////  For 15% Tax ///////////////////////////////////
	
	$amount = (isset($row_rsFilterDisplay['AMOUNT']))? $row_rsFilterDisplay['AMOUNT'] : '0';
	
	$tax1 = round($amount * $tx1 / 100,0);
        $tax1 = ($tax1<0) ? "0" : $tax1;
        
        $tax1 = round($tax1,0);
        
       
        
	$tax2 = round($amount * $tx2 / 100,0);
	$tax2 = ($tax2<0) ? "0" : $tax2;
        
        $tax2 = round($tax2,0);
		
		
		
	$tax3 = round($amount * $tx3 / 100,0);
	$tax3 = ($tax3<0) ? "0" : $tax3;
        
    $tax3 = round($tax3,0);
        
        
        
	$discount = (isset($row_rsFilterDisplay['DISCOUNT']))? $row_rsFilterDisplay['DISCOUNT'] : '0';
	$total_exp = (isset($row_rsFilterDisplay1['AMOUNT']))? $row_rsFilterDisplay1['AMOUNT'] : '0';
	
	$amount = round($amount - ($tax1 + $tax2 + $tax3),0);
	
	
	
	///////////////////////////////////////////////////////////////////////
	
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Daily Cash Summary Report</title>
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
$('#exptable').dataTable({
       "bPaginate": false,
		"bLengthChange": false,
		"bFilter": true,
		"bInfo": false,
		"bAutoWidth": false
   });
	$("#txtdate").datepick({
                    rangeSelect: true,
                    dateFormat: 'dd/mm/yyyy',
                    monthsToShow: 2,
                    maxDate: 1,
                    
                });
	
});

</script>

</head>

<body>
<?php include 'include/header.php' ?>

<div class="admin-greyBg">


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
	<h1>Daily Cash Summary Report</h1>
	<form action="pos_sale_summary.php" method="POST">
		 <input autocomplete="off" name="txtdate" type="text" id="txtdate" size="25" class="admin-inputBox">
		 <input type="submit" value="Filter Now" class="admin-button">
	 </form>
	<?php 
     
	if(isset($row_rsFilterDisplay)){
           
            $dt = explode("~",$afromto);
            $dtfrm = strtotime($dt[0]);
            $dtto = strtotime($dt[1]);
           
            echo "<br>";    
            
	?>
	<table width="100%" border="0" cellpadding="2" cellspacing="2">
	  <tr>
		<td width="16%" align="center" valign="top">
			<table width="150"  border="0">
			  <tr>
				<td align='center' valign='center' height="80" bgcolor="#FDE8FA">
				<font color='red' face='verdana' style='font-size:20px'>
				Rs. <?=$amount?>/-				</font>				</td>
			  </tr>
			  <tr>
				<th bgcolor="#FDE8FA"><h3>Total Sale</h3></th>
			  </tr>
		  </table>		</td>
		<td width="16%" align="center" valign="top"><table width="150"  border="0">
          <tr>
            <td align='center' valign='center' height="80" bgcolor="#EEEEEE"><font color='black' face='verdana' style='font-size:20px'> Rs.
              <?=$tax1;?>
              /- </font> </td>
          </tr>
          <tr>
            <th bgcolor="#EEEEEE"><h3>Tax <?=$tx1?>%</h3></th>
          </tr>
        </table></td>
		<td width="16%" align="center" valign="top"><table width="150"  border="0">
          <tr>
            <td align='center' valign='center' height="80" bgcolor="#EEEEEE"><font color='black' face='verdana' style='font-size:20px'> Rs.
              <?=$tax3;?>
              /- </font> </td>
          </tr>
          <tr>
            <th bgcolor="#EEEEEE"><h3>Tax
              <?=$tx3?>
              %</h3></th>
          </tr>
        </table></td>
		<td width="20%" align="center" valign="top">
			<table width="150"  border="0">
			  <tr>
				<td align='center' valign='center' height="80" bgcolor="#E1FCD8">
				<font color='green' face='verdana' style='font-size:20px'>
				Rs. <?=$row_rsFilterDisplay['SERVICE_CHARGE']?>/-				</font>				</td>
			  </tr>
			  <tr>
				<th bgcolor="#E1FCD8"><h3>Service Charge</h3></th>
			  </tr>
		  </table>		</td>
		<td width="16%" align="center" valign="top">
			<table width="150" border="0">
			  <tr>
				<td align='center' valign='center' height="80" bgcolor="#E6E9FB">
				<font color='blue' face='verdana' style='font-size:20px'>
				Rs. <?=$discount?>/-				</font>				</td>
			  </tr>
			  <tr>
				<th bgcolor="#E6E9FB"><h3>Total Discount</h3></th>
			  </tr>
		  </table>		</td>
		<td width="16%" align="center" valign="top">
			<table width="150"  border="0">
			  <tr>
				<td align='center' valign='center' height="80" bgcolor="#FFFF99">
				<font color='FF6600' face='verdana' style='font-size:20px'>
				Rs. <?=$total_exp?>/-				</font>				</td>
			  </tr>
			  <tr>
				<th bgcolor="#FFFF99"><h3>Total Expanse</h3></th>
			  </tr>
		  </table>		</td>
		</tr>
	</table>
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