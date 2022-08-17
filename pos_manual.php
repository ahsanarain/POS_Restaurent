<?php
//Session started and database connection  included here...
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
ini_set("date.timezone", "Asia/Karachi");
$maxID = "select max(order_id) oid from order_tab";
		$rID = mysqli_query($cn, $maxID) or die(mysqli_error($cn));
		$arrAuto1=array();
		while($data= mysqli_fetch_assoc($rID)){
			$arrAuto1[] = $data['oid'];
		}
		$maxID = $arrAuto1[0];
		if(!isset($maxID)){
			$maxID=1;
		}
		$file = ucwords(strtolower(str_replace ("_"," ",basename($_SERVER["SCRIPT_FILENAME"], '.php'))));
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$file?> Order</title>
<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<link href="css/styles.css" type="text/css" rel="stylesheet" />
<script>


$(document).ready(function(){
	$('.item').val('');
	$('.qty').val('1');
	$('.price').val('0');
	$('.total').val('0');
});
$(document).on('click','.addrow',function(){
	 
	 $('.qt tbody>tr:last').clone(true).insertAfter('.qt tbody>tr:last');
     
	 $('.qt tbody>tr:last').children().find('.item').val('');
	 $('.qt tbody>tr:last').children().find('.qty').val('1');
	 $('.qt tbody>tr:last').children().find('.price').val('0');
	 $('.qt tbody>tr:last').children().find('.total').val('0');

	 
	 return false;
});
$(document).on('click','.removerow',function(){
	var row = $(this).parent().parent().index();
	
	if(row != 0){
		$(this).parents("tr").remove();

	}
	else{
	 
	 $('.qt tbody>tr:last').children().find('.item').val('');
	 $('.qt tbody>tr:last').children().find('.qty').val('0');
	 $('.qt tbody>tr:last').children().find('.price').val('');
	 $('.qt tbody>tr:last').children().find('.total').val('0');
	 $('#summarytotal').val('0');
	 $('#servicecharge').val('0');
	 $('#amountreturn').val('0');
	}
});


function numbersonly(e){
    
    var unicode=e.charCode? e.charCode : e.keyCode;
    if(unicode!=8)
    {
        if(unicode>=48 && unicode <=57 || unicode == 9)
        {
            return true;
        }
	else
        {
            return false;
        }
    }
}
$(document).on('blur','.price',function(){
	qty   = parseInt($(this).parent().parent().find('.qty').val());
	price = parseInt($(this).parent().parent().find('.price').val());
	total = qty * price;
	$(this).parent().parent().find('.total').val(total);
	calculateSummary()
});

$(document).on('blur keypress','#amountpaid',function(e){
	var rcvd = $(this).val();
	var amt = $("#summarytotal").val();
	var rtrn = parseInt(rcvd) - parseInt(amt);
	if(e.which == '13'){
		$("#amountreturn").val(rtrn);
	}
		$("#amountreturn").val(rtrn);
	
});
$(document).on("blur keypress",'#servicecharge',function(e){
	if($(this).val()==''){
		$(this).val('0');
	}
	if(e.which == 13){
		calculateSummary();
	}
	else{
		calculateSummary();
	}
});
$(document).on('blur keypress','#discount',function(e){
	
	if($(this).val()==''){
		$(this).val('0');
	}
	if(e.which == 13){
		
		calculateSummary();
	}
	else{
		calculateSummary();
	}
});

function calculateSummary(){
	var sum=0;
	$('.total').each(function(){
		if($(this).val()!=''){
			sum = parseInt(sum) + parseInt($(this).val());
		}
	});
	var sc = $("#servicecharge").val();
	var discount = parseInt($("#discount").val());
	var famount = parseInt(sc) + parseInt(sum);
	var amt = famount - discount;
	$("#summarytotal").val(amt);
	
	
}
</script>
</head>

<body>
<?php include 'include/header.php' ?>

<div class="admin-greyBg">
  <div id="msg" align="center"><?php if(isset($_GET['msg'])) { echo $_GET['msg']; } ?></div>
  <div class="admin-wrapper">
    <div id="admin-leftNav" class="admin-fLeft">
<?php include 'include/left_nav.php' ?>
    </div>
    <div id="admin-body" class="admin-fRight">
		<h1> POS Manual Order </h1>
	<div class='section-to-print subitems'>	
	<form name='formqty' id='formqty' method='POST' action='possave.php'>
   <table border='0' width='32%' align='center'>
		<tr>
			<td colspan='2' align='left' valign='top'> 
				<img src="images/logo - small.png" width='80'><br>
				<font size='1'>
				Order #
				</font>
			<input type='text' name='orderno' id='orderno' size='5' value='<?=$maxID?>' readonly style='border:0px; text-align:right;'>
			
			</td>
			<td colspan='2' align='right' valign='top'>
			</td>
		</tr>
		<tr>
			<td><input value="<?=date("Y-m-d h:i:s") ?>" type='text' class='admin-inputBox' name='datetime' size="22" style='border:0px;'></td>
			<td> </td>
			<td> </td>
			<td align='right'>
			</td>
			
		</tr>
		<tr>
			<td colspan='2'>
			<input id='cusname'  name='cusname' value='CUSTOMER' type='text' class='admin-inputBox' style='border:0px' size="25"><br>
			<select name=sdtp class='sdtp' style='border:0px; font-size:11px;'>
						<option value='S'>Service</option>
						<option value='D'>Delivery</option>
						<option value='T'>Take Away</option>
					</select>
			</td>
			<td colspan='2' align='right'>
			<input value='0' placeholder='Phone #' style='border:0px' name='phno' type='text' class='admin-inputBox' size="16"  onkeypress="return numbersonly(event);"></td>
		</tr>
		<tr>
			<table border='0' width='32%' align='center' class='qt'>
				<tr bgcolor='wheat'>
				
					<th>Item</th>
					<th>Qty</th>
					<th>Price</th>
					<th class='no-print'>Total</th>
					<th class='no-print' width='10px'>&nbsp;</th>
				</tr>
			<tbody>
				<tr>
					
					<td>
					<input type='hidden' class='item_id' name=item_id[] value="">
					<input type='hidden' class='sub_item_id' name=sub_item_id[] value="">
					<input type='text' size='20' class='item'  name=item[]  value="">
					</td>
					<td align='right'>
					<input type='text' size='1' class='qty' style='text-align: right; ' name=qty[]  value='1' onKeyPress="return numbersonly(event);"></td>
					<td align='right'>
					<input type='text' size='1' class='price' style='text-align: right; ' name=price[]  value='0' onKeyPress="return numbersonly(event);">
					</td>
					<td>
					<input type='text' name=total[] class='total'  style='text-align: right; ' size='2' value='0' onKeyPress="return numbersonly(event);"> 
					</td>
					<td align='right' class='no-print'>
					<input type='button' value="+" style='border:0px; background-color:white;' class='addrow'>
					</td>
					<td class='no-print'>
					<input type='button' value="x" style='border:0px; background-color:white;' class='removerow'>
					</td>
				</tr>
				</tbody>
			</table>
			<center>
<textarea style='resize: none;' name='commen' cols='35' placeholder='Enter Comments, if required'>
</textarea></center><br>
			<table class='summary' width='40%' align='center'> 
				<tr>
					<td><b><font color='orange'>Amount</font><b></td>
					<td><input type='text' size='4' style='text-align: right; ' readonly name='summarytotal' value='0' id = 'summarytotal'></td>
					<td class='no-print'><!--<b><font color='orange'>Serv Chrg</font><b>--></td> 
					<td class='no-print'><input type='hidden' size='4' style='text-align: right; '  name='servicecharge' value='0' id = 'servicecharge' onKeyPress="return numbersonly(event);"></td>
					<td><b><font color='purple'>Discount</font><b></td>
					<td><input type='text' size='4' style='text-align: right; ' name='discount' value='0' id = 'discount' onKeyPress="return numbersonly(event);"></td>
				</tr>
				<tr>
					<td><b><font color='green'>Amt Paid</font></b></td>
					<td><input type='text' size='4' style='text-align: right; ' name='amountpaid' value='0' id = 'amountpaid' onKeyPress="return numbersonly(event);"></td>
					<td><b><font color='red'>Retrn Amt</font></b></td>
					<td><input type='text' size='4' style='text-align: right; ' readonly name='amountreturn' value='0' id = 'amountreturn'></td>
					<td class='no-print'><b><font color='black'>Amt Status</font><b></td>
					<td class='no-print'>
					<select style='border:0px;' name='amtstatus'>
						<option value='P'>Not Paid</option> <!-- P for Pending -->
						<option value='R'>Paid</option> <!-- R for Received -->
					</select>
					</td>
				</tr>	
			</table>	
		</tr>
		<tr>
			<th colspan="4" align="center">
			<font size='1'>
			<p align='center'>
			<?=$org_address?>
			</p>
			</font>
			</th>
		</tr>
   </table>
   <p align='center' class='no-print'>
		<input type="image" id='placeorder' name="submit" src="images/placeorder.png" width='50' border="0" alt="Submit" />
   </p>
   </form>
   </div>
		
    </div>
    <br clear="all" />
  </div>
</div>
<?php include 'include/footer.php' ?>

</body>
</html>
