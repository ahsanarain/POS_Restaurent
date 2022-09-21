<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
ini_set("date.timezone", "Asia/Karachi");
$query_rsMgtList = "SELECT * FROM items where item_status = '1' ORDER BY item_id ASC";
$rsMgtList = mysqli_query($cn, $query_rsMgtList) or die(mysqli_error($cn));
$row_rsMgtList = mysqli_fetch_assoc($rsMgtList);
$totalRows_rsMgtList = mysqli_num_rows($rsMgtList);

$query_rsauto = "SELECT SNAME FROM STAFF_REG";
$row_rsauto = mysqli_query($cn, $query_rsauto) or die(mysqli_error($cn));

$arrAuto=array();
while($data= mysqli_fetch_assoc($row_rsauto)){
	$arrAuto[] = $data['SNAME'];
}
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

if(isset($_POST['method'])){
	if($_POST['method']=='picksubitems'){
		$itemID = $_POST['itemid'];
		$query_rsMgtList = "SELECT * FROM sub_items where sub_item_status = '1' and item_id = '$itemID' ORDER BY sub_item_id ASC";
		$rsMgtList = mysqli_query($cn, $query_rsMgtList) or die(mysqli_error($cn));
		$row_rsMgtList = mysqli_fetch_assoc($rsMgtList);
		$totalRows_rsMgtList = mysqli_num_rows($rsMgtList);
		$count = 1;
		$item = "<table class='subitems' border='0' align='left' cellpadding='7' cellspacing='7'><tr>";
				do{
					$name= $row_rsMgtList['sub_item_name'];
					$item .= "<td align='center' class='menu submenu' sub_item_id=".$row_rsMgtList['sub_item_id']." item_id=".$row_rsMgtList['item_id']." price=".$row_rsMgtList['price']." name='$name'>";
					$item .= "<img width='70' height='70' src=images/subitems/".$row_rsMgtList['sub_item_image'].">";
					$item .= "<br><b>".$row_rsMgtList['sub_item_name']."</b>";
					$item .= "<br><b><font color='red'>Rs. ".$row_rsMgtList['price']."/-</font></b>";
					$item .= "</td>";
					$count ++;
					if($count == 9){
						$item .="</tr>";
						$item .="<tr>";
						$count = 1;
					}
				} while ($row_rsMgtList = mysqli_fetch_assoc($rsMgtList));
		$item .="</tr></table>";
		
		echo $item;
		exit();
	}
}

if(isset($_POST['method'])){
	if($_POST['method']=='paymentMade'){
		$itemID = $_POST['itemid'];
		$updateQry = "update order_tab set status = '0', amount_status = 'R' where order_id = '".$itemID."'";
		mysqli_query($cn, $updateQry);		
		exit();
	}
}
if(isset($_POST['method'])){
	if($_POST['method']=='orderCancelled'){
		$itemID = $_POST['itemid'];
		$updateQry = "update order_tab set status = '0', amount_status = 'C' where order_id = '".$itemID."'";
		mysqli_query($cn, $updateQry);		
		exit();
	}
}



?>


<html>
<head>
<title><?=$org_name?></title>
<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<link href="css/styles.css" type="text/css" rel="stylesheet" />
<script>
$(document).ready(function(){
	$("body").hide().fadeIn(1000);
	
	});
$(document).on("keypress","#cusname",function(){
     
    var availableDesc = new Array;
    <?php 

	foreach($arrAuto as $auto){ 
		
	?>

             availableDesc.push('<?php echo strtoupper($auto); ?>');
    <?php } ?>
      
    $("#cusname").autocomplete({ 
		maxResults: 10,
		source: function(request, response) {
			var results = $.ui.autocomplete.filter(availableDesc, request.term);
			response(results.slice(0, this.options.maxResults));
		}
	});
});
$(document).on('blur','.qty',function(){
	qty   = parseInt($(this).parent().parent().find('.qty').val());
	price = parseInt($(this).parent().parent().find('.price').val());
	total = qty * price;
	$(this).parent().parent().find('.total').val(total);
	calculateSummary();

		
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

$(document).on('click','.mainItems',function(){
		var itemid = $(this).attr('itemid');
		$.ajax({
        type:'POST', 
        url: '<?=$_SERVER['PHP_SELF']?>', 
        data:{method:'picksubitems',itemid:itemid}, 
        success: function(response) {
			$("#one").html(response).fadeIn("slow");
        }
    });
});
$(document).on('click','.submenu',function(){
	var price = $(this).attr('price');
	var name = $(this).attr('name');
	var item_id = $(this).attr('item_id');
	var sub_item_id = $(this).attr('sub_item_id');
	
	
	//alert(price + ' ' + name + ' ' + item_id + ' ' + sub_item_id);
	
	$('.qt tbody>tr:last').children().find('.item_id').val(item_id);
	$('.qt tbody>tr:last').children().find('.sub_item_id').val(sub_item_id);	
	
	 $('.qt tbody>tr:last').children().find('.item').val(name);
	 $('.qt tbody>tr:last').children().find('.qty').val(1);
	 $('.qt tbody>tr:last').children().find('.price').val(price);
	 $('.qt tbody>tr:last').children().find('.total').val(price);
	 $('.qt tbody>tr:last').clone(true).insertAfter('.qt tbody>tr:last');
	
	 $('.qt tbody>tr:last').children().find('.item').val('');
	 $('.qt tbody>tr:last').children().find('.qty').val('');
	 $('.qt tbody>tr:last').children().find('.price').val('');
	 $('.qt tbody>tr:last').children().find('.total').val('');
	 $('.qt tbody>tr:last').children().find('.item_id').val('');
	 $('.qt tbody>tr:last').children().find('.sub_item_id').val('');
     
	 calculateSummary();
	 
	 return false;
	
});
$(document).on('click','.remove',function(){
	var row = $(this).parent().parent().index();
	
	if(row != 0){
		$(this).parents("tr").remove();
		calculateSummary();

	}
	else{
	 
	 $('.qt tbody>tr:last').children().find('.item').val('');
	 $('.qt tbody>tr:last').children().find('.qty').val('0');
	 $('.qt tbody>tr:last').children().find('.price').val('');
	 $('.qt tbody>tr:last').children().find('.total').val('0');
	 $('#summarytotal').val('0');
	 $('#servicecharge').val('0');
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

$(document).on('click','#placeorder',function(){
	var row = $('.qt tr').index();
	var txt = $('.qt').find('tr:last td:first').find("input").val();
	if(row==0 && txt == ""){
		alert("Can't Place Empty Order");
		return false;
	}
	calculateSummary();
	if($("#cusname").val()==""){
		alert("Enter Customer Name");
		$("#cusname").focus();
		return false;
	}
	var row = $('.qt tr').index();
       
	var txt = $('.qt').find('tr:last td:first').find("input").val();
        if(row!=0 && txt == ''){
		$('.qt tr:last').remove();
	}
	if(confirm("Are you sure to place this Order")){
	}else{return false;}
	
});
$(document).on('click','.rcvdbtn',function(){
	if(confirm("Payment Received and Order Served Successfully ?")){
	var itemid = $(this).attr('act');
		$.ajax({
        type:'POST', 
        url: '<?=$_SERVER['PHP_SELF']?>', 
        data:{method:'paymentMade',itemid:itemid}, 
        success: function(response) {
			$("#upr").load(location.href + " #upr");
        }
    });
	}
	else{
		return false;
	}
});
$(document).on('click','.cancelbtn',function(){
	if(confirm("Sure to Cancel this Order ?")){
	var itemid = $(this).attr('act');
		$.ajax({
        type:'POST', 
        url: '<?=$_SERVER['PHP_SELF']?>', 
        data:{method:'orderCancelled',itemid:itemid}, 
        success: function(response) {
			$("#upr").load(location.href + " #upr");
        }
    });
	}
	else{
		return false;
	}
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

$(function() {
    function callAjax(){
        $("#upr").load(location.href + " #upr");
    }
    setInterval(callAjax, 10000 );
});
$(function() {
    
    setInterval(function() {
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	var yyyy = today.getFullYear();
	var complete = dd+"-"+mm+"-"+yyyy;

   	var date = new Date().toLocaleTimeString();
        $('#timer').val(complete+" "+date);
    }, 1000);
});
</script>
</head>
<body>
<div id='upr' style='overflow-x:auto;'>
<?php include ("pos_header.php");?>
</div>
  <div class="admin-wrapper">
    <div id="main">
   <div id="one" class='subitems'> 
<h1>Welcome to <?=$org_name?> (Point Of Sale)</h1>
<br><center>
<img src='images/PIZZ.JPG' WIDTH='45%'>
</center>
<br><br>
<b>
Syed Muneeb Shah<br>
0092 333 9500 910
</b>
   
   </div>
   <div id="two" class='subitems section-to-print'>
   <form name='formqty' id='formqty' method='POST' action='possave.php'>
  <table border='0' width='100%' align='center'>
		<tr>
			<td colspan='2' align='left' valign='top' width='10%'> 
				<img src="images/logo-qt.png" width='120'>
			</td>
			<td colspan='2' align='left' valign='top'>
			<input type='text' name='orderno' id='orderno' size='5' value='<?="Order # ".$maxID?>' readonly size="22" style='font-size:10px; border:0px; text-align:right; width:110px; height:25px;'><br>
<input id='timer' type='text' name='datetime' size="22" style='font-size:10px; border:0px; text-align:right; width:110px; height:25px;'><br>
			<input value="<?="User: ".$_SESSION['username']?>" type='text' size="22" style='font-size:10px; border:0px; text-align:right; width:110px; height:25px;'>
			</td>
			
		</tr>
		<tr>
			<td width='50%'>
			<input id='cusname'  name='cusname' value='CUSTOMER' type='text' class='admin-inputBox' size="20" style='border:0px; font-size:10px; height:23px;'>
			</td>
			<td>
			<select name=sdtp class='sdtp' style='border:0px; font-size:11px;'>
						<option value='S'>Service</option>
						<option value='D'>Delivery</option>
						<option value='T'>Take Away</option>
					</select>
			</td>
			<td colspan='2' align='left' valign='top'>
			<input value='0' placeholder='Phone #' name='phno' type='text' class='admin-inputBox' size="16" style='border:0px; height:22px; font-size:10px;' onKeyPress="return numbersonly(event);"></td>
		</tr>
		<tr>
			<table border='0' width='100%' class='qt'>
				<tr bgcolor='wheat' >
			
					<th align='left'>Item</th>
					<th align='right'>Qty</th>
					<th align='right'>Price</th>
					<th>Total</th>
					<th width='10px'>&nbsp;</th>
				</tr>
			<tbody>
				<tr>
					
					<td>
					<input type='hidden' class='item_id' name=item_id[] value="">
					<input type='hidden' class='sub_item_id' name=sub_item_id[] value="">
					<input type='text' size='25' style='border:0px; font-size:13px; height:18px;' name=item[] class='item' readonly value="">
					</td>
					<td align='right'>
					<input type='text' size='1' style='border:0px; font-size:13px; height:18px; text-align: right; ' name=qty[] class='qty' value='1' onKeyPress="return numbersonly(event);"></td>
					<td align='right'>
					<input type='text' size='1' style='border:0px; font-size:13px; height:18px; text-align: right; ' name=price[] class='price' value='0' readonly>
					</td>
					<td>
					<input type='text' name=total[] class='total'  style='border:0px; font-size:12px; height:16px; text-align: right; ' size='2' value='0' readonly>
					</td>
					<td align='right'>
					<input type='button' value="X" style='border:0px; background-color:white; font-size:11px; height:16px;' class='remove'>
					</td>
				</tr>
				</tbody>
			</table>
<textarea style="font-family:Calibri" name='commen' cols='42' rows='3' placeholder='Enter Comments, if required'>
</textarea><br>
			<table class='summary' border='0' width='100%' align='center'> 
				<tr>
					<td><b><font color='black'>Amount</font><b></td>
					<td><input type='text' size='4' style='text-align: right;' readonly name='summarytotal' value='0' id = 'summarytotal'></td>
					<td><b><font color='black'>Serv Chrg</font><b></td>
					<td><input type='text' size='4' style='text-align: right;'  name='servicecharge' value='0' id = 'servicecharge' onKeyPress="return numbersonly(event);"></td>
					<td class='no-print'><b><font color='purple'>Discount</font><b></td>
					<td class='no-print'><input type='text' size='4' style='text-align: right;' name='discount' value='0' id = 'discount' onKeyPress="return numbersonly(event);"></td>
				</tr>
				<tr>
					<td><b><font color='black'>Amt Paid</font></b></td>
					<td><input type='text' size='4' style='text-align: right;' name='amountpaid' value='0' id = 'amountpaid' onKeyPress="return numbersonly(event);"></td>
					<td><b><font color='black'>Retrn Amt</font></b></td>
					<td><input type='text' size='4' style='text-align: right;' readonly name='amountreturn' value='0' id = 'amountreturn'></td>
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
			16-A, Spogmay Plaza, University Town, Peshawar.<br>
			thamesburger [dot] com || FB: thames [dot] burger <br>
			091-5702182, 03428114700. 
			</p>
			</font>
			</th>
		</tr>
   </table>

   <p align='right' class='no-print'>
		<input type="image" id='placeorder' name="submit" src="images/placeorder.png" width='25' border="0" alt="Submit" />
   </p>
   </form>
   </div>
   <div id="three">
	<table class='subitems' border='0' cellpadding='2px' cellspacing='2px' align='center'>
	<tr>
   <?php
   do {
   ?>
   <td align='center' class='menu'>
   <a href='#' class='mainItems' itemid = "<?=$row_rsMgtList['item_id'];?>">
   <img width="40" height="40" src="images/items/<?php echo $row_rsMgtList['item_image']; ?>">
   <br>
   <b>
   <?php echo $row_rsMgtList['item_name']; ?>
   </b>
   </a>
   </td>
   <?php } while ($row_rsMgtList = mysqli_fetch_assoc($rsMgtList)); ?>
   </tr>
   </table>
   </div>
   <div id="four">
		 
   </div>
</div>
</div>
<?php include 'include/footer.php' ?>
</body>
</html>