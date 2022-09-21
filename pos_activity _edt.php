<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include ('lib/iq.php');
ini_set("date.timezone", "Asia/Karachi");


/*----------------------------------------------------*/

$url_id = "";
$ucustomer_name = "";
$uorder_type = "";
$uphone = "";
$uorder_id = "";
$ucomments = "";
$udiscount = 0;
$uamount_status = "";
$uservice_charge = 0;
$usummarytotal = 0;
$arrUpdOrder=array();


if(isset($_GET['url_id'])){
$url_id = $_GET['url_id'];

$qUpdOrder = "SELECT
					ot.customer_name,
					ot.order_type,
					ot.phone,
					sot.sub_order_id,
					sot.order_id,
					sot.item_id,
					sot.sub_item_id,
					sot.item,
					sot.qty,
					sot.price,
					sot.total,
					ot.comments,
					ot.service_charge,
					ot.discount,
					ot.amount_status,
					ot.plo
					FROM
					order_tab ot,
					sub_order_tab sot
					WHERE
					ot.order_id = sot.order_id
					and ot.order_id = '".$url_id."'
					";
$rsUpdOrder = mysqli_query($cn, $qUpdOrder) or die(mysqli_error($cn));

$totalRows_UpdOrder = mysqli_num_rows($rsUpdOrder);


		while($data= $rowUpdOrder = mysqli_fetch_assoc($rsUpdOrder)){
			$arrUpdOrder[] = $data;
		}
$ucustomer_name = $arrUpdOrder[0]['customer_name'];
$uorder_type = $arrUpdOrder[0]['order_type'];
$uphone = $arrUpdOrder[0]['phone'];
$uorder_id = $arrUpdOrder[0]['order_id'];
$ucomments = $arrUpdOrder[0]['comments'];
$udiscount = $arrUpdOrder[0]['discount'];
$uamount_status = $arrUpdOrder[0]['amount_status'];
$uservice_charge = $arrUpdOrder[0]['service_charge']; 
$uplo = $arrUpdOrder[0]['plo'];
}
/*----------------------------------------------------*/

$query_rsMgtList = "SELECT * FROM items where item_status = '1' ORDER BY item_id ASC";
$rsMgtList = mysqli_query($cn, $query_rsMgtList) or die(mysqli_error($cn));
$row_rsMgtList = mysqli_fetch_assoc($rsMgtList);
$totalRows_rsMgtList = mysqli_num_rows($rsMgtList);


$qmsg = "SELECT msg FROM qtmsg where status='1'";
$rmsg = mysqli_query($cn, $qmsg) or die(mysqli_error($cn));
$datamsg= mysqli_fetch_assoc($rmsg);

$maxID = "select max(order_id) oid from order_tab";
		$rID = mysqli_query($cn, $maxID) or die(mysqli_error($cn));
		$arrAuto1=array();
		while($data= mysqli_fetch_assoc($rID)){
			$arrAuto1[] = $data['oid'];
		}
		$maxID = $arrAuto1[0]+1;
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

$q = "SELECT sub_item_id, upper(sub_item_name) sub_item_name from sub_items where sub_item_status = '1' order by sub_item_name asc";
$r = mysqli_query($cn, $q) or die(mysqli_error($cn));
$aA=array();
while($d= mysqli_fetch_assoc($r)){
	$aA[] = $d['sub_item_id'].'-'.$d['sub_item_name'];
}

if(isset($_POST['method'])){
    if($_POST['method']=='pickitems'){
    	$sqlItems = "select 
						sub_item_id,
						item_id,
						sub_item_name,
						price from sub_items 
					where 
						sub_item_id = '".$_POST['item_id']."' 
						and sub_item_status = '1'";
        $rsItems = mysqli_query($cn, $sqlItems) or die(mysqli_error($cn));
		$items = mysqli_fetch_assoc($rsItems);
                echo json_encode($items);
        exit();
    }
}


$query_tax = "SELECT tax1 FROM tax_tab where status = '1' ";
$rstax = mysqli_query($cn, $query_tax) or die(mysqli_error($cn));
$rowtax = mysqli_fetch_assoc($rstax);
$totalRows_rsMgtList = mysqli_num_rows($rstax);

$tax_new = $rowtax['tax1'];

$file = ucwords(strtolower(str_replace ("_"," ",basename($_SERVER["SCRIPT_FILENAME"], '.php'))));
?>


<html>
<head>
<title><?=$file?></title>
<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<link href="css/styles.css" type="text/css" rel="stylesheet" />
<style>
.ui-widget-content{
font-size:20px;
}
</style>
<script>

var counter = 1;

$(document).on('click','.addrow',function(){
	 var row=$(this).parent().parent();
	
	var item = row.children().eq(0).find('.item').val();
	if(item==""){
		alert("Please Add Item First");
		return false;
	}
	 
	 $('.qt tbody>tr:last').clone(true).insertAfter('.qt tbody>tr:last');
     
	 $('.qt tbody>tr:last').children().find('.item').val('');
	 $('.qt tbody>tr:last').children().find('.qty').val('1');
	 $('.qt tbody>tr:last').children().find('.price').val('0');
	 $('.qt tbody>tr:last').children().find('.total').val('0');

	 $('.qt tbody>tr:last').children().find('.item_id').attr('name','manual['+counter+'][item_id]item_id[]');
	 $('.qt tbody>tr:last').children().find('.sub_item_id').attr('name','manual['+counter+'][sub_item_id]sub_item_id[]');
	 $('.qt tbody>tr:last').children().find('.item').attr('name','manual['+counter+'][item]item[]');
	 $('.qt tbody>tr:last').children().find('.qty').attr('name','manual['+counter+'][qty]qty[]');
	 $('.qt tbody>tr:last').children().find('.price').attr('name','manual['+counter+'][price]price[]');
	 $('.qt tbody>tr:last').children().find('.total').attr('name','manual['+counter+'][total]total[]');
	
	 counter++;
	 
	 return false;
});
$(document).ready(function(){
	startTime();
	$("body").hide().fadeIn(1000);
calculateSummary();	
	});

$(document).on("keypress","#gsearch",function(eve){ 
    if(eve.which !=13){
		var availableDesc = new Array;
		<?php 
		foreach($aA as $auto){ 
		?>
				 availableDesc.push('<?php echo strtoupper($auto); ?>');
		<?php } ?>
		  
		$("#gsearch").autocomplete({ 
			maxResults: 10,
			source: function(request, response) {
				var results = $.ui.autocomplete.filter(availableDesc, request.term);
				response(results.slice(0, this.options.maxResults));
			}
		});
	}else{
		if($("#gsearch").val()=="")
			return false;
		if($("#gsearch").val()!=""){
    		var item_id = $(this).val().split("-");
    		item_id = item_id[0];
			var price,name,item_id,sub_item_id;
			$.ajax({
				type:'POST', 
				url: '<?=$_SERVER['PHP_SELF']?>', 
				data:{method:'pickitems',item_id:item_id}, 
				success: function(response){
						var data = $.parseJSON(response);
						console.log(data.price);
						if(data.price == undefined){
							$("#gsearch").val("");
							$("#gsearch").focus();
							return false;
						}
						price = data.price;
						name = data.sub_item_name;
						item_id = data.item_id;
						sub_item_id = data.sub_item_id;
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
					 
						$('.qt tbody>tr:last').children().find('.item_id').attr('name','manual['+counter+'][item_id]item_id[]');
	 					$('.qt tbody>tr:last').children().find('.sub_item_id').attr('name','manual['+counter+'][sub_item_id]sub_item_id[]');
					 	$('.qt tbody>tr:last').children().find('.item').attr('name','manual['+counter+'][item]item[]');
					 	$('.qt tbody>tr:last').children().find('.qty').attr('name','manual['+counter+'][qty]qty[]');
					 	$('.qt tbody>tr:last').children().find('.price').attr('name','manual['+counter+'][price]price[]');
					 	$('.qt tbody>tr:last').children().find('.total').attr('name','manual['+counter+'][total]total[]');
						counter++;
				
					 calculateSummary();
					 	$("#gsearch").val("");
						return false;
				}
			});			
			return false;
		}
	}
});

$(document).on("keypress",".item",function(){
	if(($(this).closest("tr").is(":last-child"))){
			 
	 $('.qt tbody>tr:last').clone(true).insertAfter('.qt tbody>tr:last');
     
	 $('.qt tbody>tr:last').children().find('.item').val('');
	 $('.qt tbody>tr:last').children().find('.qty').val('1');
	 $('.qt tbody>tr:last').children().find('.price').val('0');
	 $('.qt tbody>tr:last').children().find('.total').val('0');
	 
	 
	 $('.qt tbody>tr:last').children().find('.item_id').attr('name','record['+counter+'][item_id]item_id[]');
	 $('.qt tbody>tr:last').children().find('.sub_item_id').attr('name','record['+counter+'][sub_item_id]sub_item_id[]');
	 $('.qt tbody>tr:last').children().find('.item').attr('name','record['+counter+'][item]item[]');
	 $('.qt tbody>tr:last').children().find('.qty').attr('name','record['+counter+'][qty]qty[]');
	 $('.qt tbody>tr:last').children().find('.price').attr('name','record['+counter+'][price]price[]');
	 $('.qt tbody>tr:last').children().find('.total').attr('name','record['+counter+'][total]total[]');
	counter++;
	}
	
	
});



$(document).on('blur','.qty',function(){
	qty   = parseInt($(this).parent().parent().find('.qty').val());
	price = parseInt($(this).parent().parent().find('.price').val());
	total = qty * price;
	$(this).parent().parent().find('.total').val(total);
	calculateSummary();

		
});

$(document).on('blur','.price',function(){
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
	if($(this).val()=="" || $(this).val()=="0"){
            $("#amountreturn").val('0');
        }

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
     
	 	$('.qt tbody>tr:last').children().find('.item_id').attr('name','manual['+counter+'][item_id]item_id[]');
	 	$('.qt tbody>tr:last').children().find('.sub_item_id').attr('name','manual['+counter+'][sub_item_id]sub_item_id[]');
	 	$('.qt tbody>tr:last').children().find('.item').attr('name','manual['+counter+'][item]item[]');
	 	$('.qt tbody>tr:last').children().find('.qty').attr('name','manual['+counter+'][qty]qty[]');
	 	$('.qt tbody>tr:last').children().find('.price').attr('name','manual['+counter+'][price]price[]');
	 	$('.qt tbody>tr:last').children().find('.total').attr('name','manual['+counter+'][total]total[]');
		counter++;
	 
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
	if($("#summarytotal").val()=="NaN"){
        alert("ERROR IN BILL");
        return false;
    }
	var row = $('.qt tr').index();
	var txt = $('.qt').find('tr:last td:first').find("input").val();
	if(row==0 && txt == '')
	{
		alert("Can't Place Empty Order Sorry !!!");
		return false;
	}
	calculateSummary();
        if(row!=0 && txt == ''){
		$('.qt tr:last').remove();
	}
       // gonaPrint();
       
	if(confirm("Sure to modify this Bill ...")){
        
	}else{return false;}
	
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
$(document).on('blur keypress','#discount, #summarytotal #servicecharge ,#amountpaid ,amountreturn',function(){
    if($(this).val()=="" || $(this).val()== "NaN"){
        $(this).val('0');
    }
});
$(document).on('click','.inc',function(){
	var row=$(this).parent().parent();
	
	var item = row.children().eq(0).find('.item').val();
	if(item==""){
		alert("Please Add Item First");
		return false;
	}
	var vl = row.children().eq(1).children().val();
	vl++;
	row.children().eq(1).children().val(vl);
	
	
	qty   = parseInt($(this).parent().parent().find('.qty').val());
	price = parseInt($(this).parent().parent().find('.price').val());
	total = qty * price;
	$(this).parent().parent().find('.total').val(total);
	calculateSummary();
	
});
$(document).on('click','.dec',function(){
	var row=$(this).parent().parent();
	
	var item = row.children().eq(0).find('.item').val();
	if(item==""){
		alert("Please Add Item First");
		return false;
	}
	var vl = row.children().eq(1).children().val();
	if(vl>1)
	{
		vl--;
		row.children().eq(1).children().val(vl);
	}
	
	qty   = parseInt($(this).parent().parent().find('.qty').val());
	price = parseInt($(this).parent().parent().find('.price').val());
	total = qty * price;
	$(this).parent().parent().find('.total').val(total);
	calculateSummary();
	
});


function gonaPrint(){
     	var item=[];
        var qty=[];
        var price=[];
        var total =[];
        var finalArr=[];
        var summarytotal = $("#summarytotal").val();
        var servicecharge = $("#servicecharge").val();
        var discount = $("#discount").val();
        var scc = $("#servicecharge").val();
        var amountreturn = $("#amountreturn").val();
        var amtstatus = $("#amtstatus option:selected").text();
        var orderno = $("#orderno").val();
        var cusname = $("#cusname").val();
        var sdtp = $(".sdtp option:selected").text();
        var datetime = $("#datetime").val();
        var uname = $("#uname").val();
        var phno = $("#phno").val();
        var commen= $("#commen").val();

		var tax_new = $("#tax_new").val();
        
        $(".item").each(function(){
           item.push($(this).val()); 
        });
        $(".qty").each(function(){
           qty.push($(this).val()); 
        });
        $(".price").each(function(){
           price.push($(this).val()); 
        });
        $(".total").each(function(){
           total.push($(this).val()); 
        });
        for(i=0; i< item.length; i++){
            finalArr.push(item[i]+"-"+qty[i]+"-"+price[i]+"-"+total[i]);
        };
        var msg = "<?=(isset($datamsg['msg'])?$datamsg['msg']:'')?>";
        var str = "<style type='text/css'>";
            str+="body { margin-left: 0px; margin-top: 0px; margin-right: 0px; margin-bottom: 0px; }";
            str+="#newprint { width:288px; height:auto; border:0px solid red; }";
            str+="body,td,th { font-family: Verdana; font-size: 9px; }";
            str+=".small{ font-family:verdana; font-size:9px;}";
            str+="</style>";
            str+="<div id='newprint'>";
            str+="<table width='288' border='0' align='center'>";
            str+="<tr>";
            str+="<td colspan='3' rowspan='3' align='left' valign='top'><strong><img width=150px src='images/logo-qt.png'></strong></td>";
            str+="<td colspan='2' align='right'><span class='small'>"+orderno+"</span></td>";
            str+="</tr>";
            str+="<tr>";
            var dt = datetime.split(" ");
            var tim = dt[1];
            var dat = dt[0].split("-");
            var y = dat[0];
            var m = dat[1];
            var d = dat[2];
            var mix = d+"-"+m+"-"+y+" "+tim;
            str+="<td colspan='2' align='right'><span class='small'>"+mix+"</span></td>";
            str+="</tr>";
            str+="<tr>";
            str+="<td colspan='2' align='right'><span class='small'>"+uname+"</span></td>";
            str+="</tr>";
            str+="<tr>";
            str+="<td colspan='3'><span class='small'>"+cusname+"</span></td>";
            str+="<td width='52'><span class='small'>"+sdtp+"</span></td>";
            str+="<td width='130' align='right'><span class='small'>"+phno+"</span></td>";
            str+="</tr>";
            str+="</table>";
	    str+="<hr>";
            str+="<table width='288' border='0'>";
            str+="<tr bgcolor='#F0F0F0'>";
            str+="<td width='100'><strong>Item</strong></td>";
            str+="<td width='34' align='right'><strong>Qty</strong></td>";
            str+="<td width='60' align='right'><strong>Price</strong></td>";
            str+="<td width='73' align='right'><strong>Total</strong></td>";
            str+="</tr>";
            for(i=0; i< item.length; i++){
                str+="<tr>";
                str+="<td>"+item[i]+"</td>";
                str+="<td align='right'>"+qty[i]+"</td>";
                str+="<td align='right'>"+price[i]+"</td>";
                str+="<td align='right'>"+total[i]+"</td>";
                str+="</tr>";
            }
	    var perper = ((summarytotal*tax_new)/100);
            var edt    =  summarytotal - perper;
            str+="</table>";
            str+="<hr>";
            str+="<table width='288' border='0'>";
	    str+="<tr>";
            str+="<td colspan='5'>"+commen+"</td>";
            str+="</tr>";
	    str+="<tr>";
            str+="<td colspan='6' align='center' valign='top'>";
		str+="<hr>";
	    str+="</td>";
            str+="</tr>";
            str+="<tr>";
            str+="<td colspan='6' align='center' valign='top'>";
		str+="<hr>";
	    str+="</td>";
            str+="</tr>";
            str+="<tr>";
            str+="<td width='40'>&nbsp;</td>";
          
            str+="<td width='55' colspan='4' align='right'><strong>Gross Total : </strong></td>";
           str+="<td align='right' width='54'><font size='2'>"+((parseInt(edt)+parseInt(discount)) - scc) +"/-</font></td>";
            str+="</tr>";
            str+="<tr>";
            str+="<td><strong>"+(amtstatus.toUpperCase())+"</strong></td>";
            str+="<td>&nbsp;</td>";
            str+="<td colspan='3' align='right'><strong> SC "+tax_new+":</strong></td>";
            str+="<td align='right'><font size='2'>"+scc+"/-</font></td>";
            str+="</tr>";
	    str+="<tr>";
            str+="<td><strong></strong></td>";
	    str+="<td colspan='4' align='right'><strong>Discount:</strong></td>";
            str+="<td align='right'><font size='2'>"+discount+"/-</font></td>";
            str+="</tr>";
	    str+="<tr>";
		str+="<td><strong></strong></td>";
	    str+="<td colspan='4' align='right'><strong>Net Total:</strong></td>";
	    str+="<td align='right'><font size='2'>"+summarytotal+"/-</font></td>";
            
            str+="</tr>";
 
           str+="<tr>";
            str+="<td colspan='6' align='center' valign='top'><span class='small'><br /><?=$org_address?><br><strong>"+msg+"</strong><br>NTN : 1530210008565</span></td>";
            str+="</tr>";

            str+="</table>";
            str+="</div>";
            str+="<p style='page-break-before: always'></p>";
			
	var hold = str;
	str = "";
	var item=[];
        var qty=[];
        var price=[];
        var total =[];
        var finalArr=[];
        var summarytotal = $("#summarytotal").val();
        var servicecharge = $("#servicecharge").val();
        var discount = $("#discount").val();
        var scc = $("#servicecharge").val();
        var amountreturn = $("#amountreturn").val();
        var amtstatus = $("#amtstatus option:selected").text();
        var orderno = $("#orderno").val();
        var cusname = $("#cusname").val();
        var sdtp = $(".sdtp option:selected").text();
        var datetime = $("#datetime").val();
        var uname = $("#uname").val();
        var phno = $("#phno").val();
        var commen= $("#commen").val();

		var tax_new = $("#tax_new").val();
        
        $(".item").each(function(){
           item.push($(this).val()); 
        });
        $(".qty").each(function(){
           qty.push($(this).val()); 
        });
        $(".price").each(function(){
           price.push($(this).val()); 
        });
        $(".total").each(function(){
           total.push($(this).val()); 
        });
        for(i=0; i< item.length; i++){
            finalArr.push(item[i]+"-"+qty[i]+"-"+price[i]+"-"+total[i]);
        };
        var msg = "<?=(isset($datamsg['msg'])?$datamsg['msg']:'')?>";
        var str = "<style type='text/css'>";
            str+="body { margin-left: 0px; margin-top: 0px; margin-right: 0px; margin-bottom: 0px; }";
            str+="#newprint { width:288px; height:auto; border:0px solid red; }";
            str+="body,td,th { font-family: Verdana; font-size: 9px; }";
            str+=".small{ font-family:verdana; font-size:9px;}";
            str+="</style>";
            str+="<div id='newprint'>";
            str+="<table width='288' border='0' align='center'>";
            str+="<tr>";
            str+="<td colspan='6' align='center' valign='top'>";
			str+="<strong>FOR KITCHEN</strong>";
	    	str+="</td>";
            str+="</tr>";
			str+="<tr>";
            str+="<td colspan='3' rowspan='3' align='left' valign='top'><strong><img width=150px src='images/logo-qt.png'></strong></td>";
            str+="<td colspan='2' align='right'><span class='small'>"+orderno+"</span></td>";
            str+="</tr>";
            str+="<tr>";
            var dt = datetime.split(" ");
            var tim = dt[1];
            var dat = dt[0].split("-");
            var y = dat[0];
            var m = dat[1];
            var d = dat[2];
            var mix = d+"-"+m+"-"+y+" "+tim;
            str+="<td colspan='2' align='right'><span class='small'>"+mix+"</span></td>";
            str+="</tr>";
            str+="<tr>";
            str+="<td colspan='2' align='right'><span class='small'>"+uname+"</span></td>";
            str+="</tr>";
            str+="<tr>";
            str+="<td colspan='3'><span class='small'>"+cusname+"</span></td>";
            str+="<td width='52'><span class='small'>"+sdtp+"</span></td>";
            str+="<td width='130' align='right'><span class='small'>"+phno+"</span></td>";
            str+="</tr>";
            str+="</table>";
	    str+="<hr>";
            str+="<table width='288' border='0'>";
            str+="<tr bgcolor='#F0F0F0'>";
            str+="<td width='100'><strong>Item</strong></td>";
            str+="<td width='34' align='right'><strong>Qty</strong></td>";
            str+="<td width='60' align='right'><strong>Price</strong></td>";
            str+="<td width='73' align='right'><strong>Total</strong></td>";
            str+="</tr>";
            for(i=0; i< item.length; i++){
                str+="<tr>";
                str+="<td>"+item[i]+"</td>";
                str+="<td align='right'>"+qty[i]+"</td>";
                str+="<td align='right'>---</td>";
                str+="<td align='right'>---</td>";
                str+="</tr>";
            }
	    
            str+="</table>";
           
            str+="<table width='288' border='0'>";
	    str+="<tr>";
            str+="<td colspan='5'>"+commen+"</td>";
            str+="</tr>";
	        str+="<tr>";
            str+="<td colspan='6' align='center' valign='top'>";
		str+="<hr>";
	    str+="</td>";
            str+="</tr>";
	    
           str+="<tr>";
            str+="<td colspan='6' align='center' valign='top'><span class='small'><br /><?=$org_address?><br><strong>"+msg+"</strong><br>NTN : 1530210008565</span></td>";
            str+="</tr>";

            str+="</table>";
            str+="</div>";
            str+="<p style='page-break-before: always'></p>";
	
			
    var win = window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=320,height=700');
	
	

	win.document.write(hold+str);
	win.print();
    win.close();
	

}

function startTime() {

    var today = new Date();
    var h = today.getHours();
    var m = today.getMinutes();
    var s = today.getSeconds();
    var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	var yyyy = today.getFullYear();
    if (h > 12) {
    	h -= 12;
	} else if (h === 0) {
   		h = 12;
	}
    m = checkTime(m);
    s = checkTime(s);
    
    if(dd<10) {
    	dd='0'+dd
	} 
	if(mm<10) {
    	mm='0'+mm
	}
   	if(h<10){
    	h='0'+h
    }

	dt = yyyy+'-'+mm+'-'+dd;
    
    
    $("#datetime").val(dt+ " " + h + ":" + m + ":" + s);
    var t = setTimeout(startTime, 500);
}
function checkTime(i) {
    if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
    return i;
}

</script>
</head>
<body>
  <div class="admin-wrapper">
	<p>
	<a href="cms.php"><strong>Back to Dashboard</strong></a>
	</p>
	<div id="main">
   <div id="one" class='subitems'> 
<h1 align="center">Welcome to <?=$org_name?> (Point Of Sale)</h1><center>
<img src='images/PIZZ.JPG' WIDTH='70%'>
</center>
<br><br><br><br>
<b>
Syed IQtidar Shah<br>
<a href="http://www.syediqtidarshah.com" target="_blank">syediqtidarshah.com</a><br>
03321584030
</b>
   
   </div>
   <div id="two" class='subitems section-to-print'>
   <form name='formqty' id='formqty' method='POST' action='posedit.php'>
  <table border='0' width='100%' align='center'>
  		<tr>
			<td colspan="3">
<!--
<input id='gsearch' name='gsearch' placeholder="Global Search" type='text' class='admin-inputBox' size="35" style='border:1px solid #f0f0f0; font-size:20px; height:30px; text-transform:uppercase;'>			
-->
</td>
		</tr>
		<tr>
			<td colspan='2' align='left' valign='top' width='10%'> 
				
				<img src="images/logo-qt.png" width='130'>			</td>
			<td colspan='2' align='left' valign='top'>
		<input type='text' name='orderno' id='orderno' value='<?php echo"Order #".$maxID; ?>' readonly size="22" style='font-size:10px; border:0px; text-align:right; width:110px; height:25px;'><br>
			<input type='text' name='datetime' id='datetime' size="22" style='font-size:10px; border:0px; text-align:right; width:110px; height:25px;'><br>
			<input id="uname" value="<?="User: ".$_SESSION['user_id']?>" type='text' size="22" style='font-size:10px; border:0px; text-align:right; width:110px; height:25px;'>			</td>
		</tr>
		<tr>
			<td width='50%'>
<input id='cusname' name='cusname' placeholder="Customer Name" type='text' class='admin-inputBox' size="27" style='border:1px solid #f0f0f0; font-size:10px; height:23px;' value="<?=$ucustomer_name?>">			</td>
			<td>
			<select name=sdtp class='sdtp' style='border:1px solid #f0f0f0; font-size:11px;'>
						<option value='S' <?=$uorder_type == "S" ? 'selected=selected' : '' ?>>Service</option>
						<option value='D' <?=$uorder_type == "D" ? 'selected=selected' : '' ?>>Delivery</option>
						<option value='T' <?=$uorder_type == "T" ? 'selected=selected' : '' ?>>Take Away</option>
					</select>			</td>
			<td colspan='2' align='left' valign='top'>
			<input placeholder='Phone #' id="phno" name='phno' type='text' class='admin-inputBox' size="16" style='border:1px solid #f0f0f0; height:24px; font-size:12px;' onKeyPress="return numbersonly(event);" value=<?=$uphone?>></td>
		</tr>
		<tr>
			<table border='0' width='100%' class='qt'>
				
				<tr bgcolor='wheat' >
					<th align='left'>Item</th>
					<th align='right'>Qty</th>
					<th>&nbsp;</th>
					<th>&nbsp;</th>
					<th align='right'>Price</th>
					<th>Total</th>
					<th width='10px'>&nbsp;</th>
					<th width ='10px'>&nbsp;</th>
				</tr>
			
			<tbody>
			<?php
			 $i=-1;
			 foreach($arrUpdOrder as $Udata){ 
			$i++;
			 ?>
				<tr>
					
					<td>
					<input type='hidden' class='sub_order_id' name="record[<?=$i?>][sub_order_id]sub_order_id[]" value="<?=$Udata['sub_order_id']?>">
					<input type='hidden' class='item_id' name="record[<?=$i?>][item_id]item_id[]" value="<?=$Udata['item_id']?>">
					<input type='hidden' class='sub_item_id' name="record[<?=$i?>][sub_item_id]sub_item_id[]" value="<?=$Udata['sub_item_id']?>">
					<input type='text' size='20' style='border:1px solid #f0f0f0; font-size:14px; height:20px;' name="record[<?=$i?>][item]item[]" class='item'  value="<?=$Udata['item']?>">					</td>
					<td align='right'>
<input type='text' size='1'  style='border:1px solid #f0f0f0; font-size:14px; height:20px; text-align: right;' name="record[<?=$i?>][qty]qty[]" class='qty'  onKeyPress="return numbersonly(event);" value="<?=$Udata['qty']?>">					</td>
					<td>
					<input type='button' value='+' class='inc inc-dec' style='background-color:#E7FEE0;'>					</td>
					<td>
					<input type='button' value='-' class='dec inc-dec' style='background-color:#FFCCFF;'>					</td>
					<td align='right'>
					<input type='text' size='2' style='border:1px solid #f0f0f0; font-size:14px; height:20px; text-align: right; ' name="record[<?=$i?>][price]price[]" class='price' value='<?=$Udata['price']?>'>					</td>
					<td>
					<input type='text' name="record[<?=$i?>][total]total[]" class='total'  style='border:1px solid #f0f0f0; font-size:14px; height:20px; text-align: right; ' size='3' value='<?=($Udata['price']*$Udata['qty'])?>' readonly>					</td>
					<td align="right">
					<input type='button' value="+" style='height: 20px; width: 20px; border-radius: 50%; border: 0px solid white; background-color:grey; color:white; float:left;'>					</td>
					<td align='right'>
					<input type='button' value="X"  style='border:0px; background-color:white; font-size:11px; height:16px;'>					</td>
				</tr>
				<?php 
				$usummarytotal = $usummarytotal +  ($Udata['price']*$Udata['qty']);
				 } ?>
				
				<tr>
					
					<td>
					
					<input type='hidden' class='item_id' name="manual[0][item_id]item_id[]" value="">
					<input type='hidden' class='sub_item_id' name="manual[0][sub_item_id]sub_item_id[]" value="">
					<input type='text' size='20' style='border:1px solid #f0f0f0; font-size:14px; height:20px;' name="manual[0][item]item[]" class='item'  value="">					</td>
					<td align='right'>
					<input type='text' size='1' readonly="readonly" style="border:1px solid #f0f0f0; font-size:14px; height:20px; text-align: right;" name="manual[0][qty]qty[]" class='qty' value='1' onKeyPress="return numbersonly(event);">					</td>
					<td>
					<input type='button' value='+' class='inc inc-dec' style='background-color:#E7FEE0;'>					</td>
					<td>
					<input type='button' value='-' class='dec inc-dec' style='background-color:#FFCCFF;'>					</td>
					<td align='right'>
					<input type='text' size='2' style='border:1px solid #f0f0f0; font-size:14px; height:20px; text-align: right; ' name="manual[0][price]price[]" class='price' value='0' >					</td>
					<td>
					<input type='text' name="manual[0][total]total[]" class='total'  style='border:1px solid #f0f0f0; font-size:14px; height:20px; text-align: right; ' size='3' value='0' readonly>					</td>
					<td>
					<input type='button' value="+" style='height: 20px; width: 20px; border-radius: 50%; border: 0px solid white; background-color:red; color:white; float:left; cursor:pointer;' class='addrow'>					</td>
					<td align='right'>
					<input type='button' value="X" style='border:0px; background-color:white; cursor:pointer; font-size:11px; height:16px;' class='remove'>					</td>
				</tr>
				</tbody>
			</table>
<textarea style="font-family: 'Calibri'; border:1px solid #f0f0f0; " id="commen" name='commen' cols='42' rows='3' placeholder='Enter Comments, if required'><?=$ucomments?></textarea><br>
<table border="0" width="100%" bgcolor="#FFFFCC">
<tr>
<td>
<font size="1" color="red">
<strong>
Pay Later On
</strong>
</font>
</td>
<td align="left">
<input type="checkbox" name="plo" <?=$uplo=="1" ? "checked" : "" ?>>
</td>
</tr>
</table>
			<table class='summary' border='0' width='100%' align='center'> 
				<tr>
					<td><b><font color='black' size="2">TOTAL:</font><b></td>
					<td><b><input type='text' size='5' style='text-align: center; border:1px solid #f0f0f0; font-weight:bold;' readonly name='summarytotal' value='<?=$usummarytotal?>' id = 'summarytotal'><b></td>
					<td><b><font color='black'>SC:</font><b></td>
					<td><input type='text' size='4' style='text-align: right; border:1px solid #f0f0f0;'  name='servicecharge' value='<?=$uservice_charge?>' id = 'servicecharge' onKeyPress="return numbersonly(event);"></td> 
					<td class='no-print'><font color='purple'>Discount</font></td>
					<td class='no-print'><input type='text' size='4' style='text-align: right; border:1px solid #f0f0f0;' name='discount' value='<?=$udiscount?>' id = 'discount' onKeyPress="return numbersonly(event);"></td>
				</tr>
				<tr>
					<td class='no-print'><font color='black'>Amt PAID:</font></td>
					<td class='no-print'><input type='text' size='4' style='text-align: right; border:1px solid #f0f0f0;' name='amountpaid' value='0' id = 'amountpaid' onKeyPress="return numbersonly(event);"></td>
					<td class='no-print'><font color='black'>Amt RTRN:</font></td>
					<td class='no-print'><input type='text' size='4' style='text-align: right; border:1px solid #f0f0f0;' readonly name='amountreturn' value='0' id = 'amountreturn'></td>
					<td class='no-print'><font color='black'>Amt STATUS:</font></td>
					<td class='no-print'>
					<select style='border:1px solid #f0f0f0;' name='amtstatus' id="amtstatus">
						<option value='P' <?=$uamount_status == "P" ? 'selected=selected' : ''?>>Not Paid</option> <!-- P for Pending -->
						<option value='R' <?=$uamount_status == "R" ? 'selected=selected' : ''?>>Paid</option> <!-- R for Received -->
					</select>					</td>
				</tr>	
			</table>		
		</tr>
		<tr>
			<th colspan="4" align="center"><br>
			<font size='1'>
			<?=$org_address?>
			<p align='center'><?php /*?>thamesburger [dot] com || FB: thames [dot] burger <br><?php */?>
			 <br>
			<b><?=(isset($datamsg['msg'])?$datamsg['msg']:'')?></b>			</p>
			</font>			</th>
		</tr>
   </table>

   <p align='right' class='no-print'>
		<input type="image" id='placeorder' name="submit" src="images/placeorder.png" width='25' border="0" alt="Submit" />
   </p>
<input type="hidden" id="tax_new" value="<?=$tax_new?>">
   <input type="hidden" value="<?=$uorder_id?>" name="HORDERID">
   </form>
   </div>
   <div id="three">
	<table class='subitems' border='0' cellpadding='2px' cellspacing='2px' align='center'>
	<tr>
   <?php
   do {
   ?>
   <td align='center' class='menu' width="70px">
   <a href='#' class='mainItems' itemid = "<?=$row_rsMgtList['item_id'];?>">
   <img width="40" height="40" src="images/items/<?php echo $row_rsMgtList['item_image']; ?>">
   <br>
   <b>
   <?php echo strtoupper($row_rsMgtList['item_name']); ?>
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