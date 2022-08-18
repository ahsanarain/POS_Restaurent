<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include ('lib/iq.php');
ini_set("date.timezone", "Asia/Karachi");

$query_rsMgtList = "SELECT * FROM items where item_status = '1' ORDER BY item_id ASC";
$rsMgtList = mysqli_query($cn, $query_rsMgtList) or die(mysqli_error($cn));
$row_rsMgtList = mysqli_fetch_assoc($rsMgtList);
$totalRows_rsMgtList = mysqli_num_rows($rsMgtList);

$query_rsauto = "SELECT customer_name,customer_id FROM customer";
$row_rsauto = mysqli_query($cn, $query_rsauto) or die(mysqli_error($cn));

$qmsg = "SELECT msg FROM qtmsg where status='1'";
$rmsg = mysqli_query($cn, $qmsg) or die(mysqli_error($cn));
$datamsg= mysqli_fetch_assoc($rmsg);
$arrAuto=array();
while($data= mysqli_fetch_assoc($row_rsauto)){
	$arrAuto[] = $data['customer_id'].'-'.$data['customer_name'];
}
$maxID = "SELECT MAX(ORDER_ID) oid from ORDER_TAB";
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
if(isset($_POST['method'])){
    if($_POST['method']=='pickcustomer'){
        $sqlCustomer = "select * from customer where customer_id = '".$_POST['customer_id']."'";
        $rsCustomer = mysqli_query($cn, $sqlCustomer) or die(mysqli_error($cn));
		$customer = mysqli_fetch_assoc($rsCustomer);
                echo json_encode($customer);
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

$tax_new = $rowtax['tax1'] ?? 0;

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
var counter=1;
$(document).on("click","#refresh",function(){
	location.reload(true);
});
$(document).ready(function(){
	startTime();
	$("body").hide().fadeIn(1000);
	
	});
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
	 
	 
	 $('.qt tbody>tr:last').children().find('.item_id').attr('name','record['+counter+'][item_id]item_id[]');
	 $('.qt tbody>tr:last').children().find('.sub_item_id').attr('name','record['+counter+'][sub_item_id]sub_item_id[]');
	 $('.qt tbody>tr:last').children().find('.item').attr('name','record['+counter+'][item]item[]');
	 $('.qt tbody>tr:last').children().find('.qty').attr('name','record['+counter+'][qty]qty[]');
	 $('.qt tbody>tr:last').children().find('.price').attr('name','record['+counter+'][price]price[]');
	 $('.qt tbody>tr:last').children().find('.total').attr('name','record['+counter+'][total]total[]');
	counter++;
	 return false;
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
$(document).on("blur","#cusname",function(){
 /*   if($("#cusname").val()!=""){
    var customer_id = $(this).val().split("-");
    customer_id = customer_id[0];
     $.ajax({
        type:'POST', 
        url: '<?=$_SERVER['PHP_SELF']?>', 
        data:{method:'pickcustomer',customer_id:customer_id}, 
        success: function(response) {
            if(response){
              var data = $.parseJSON(response);
	      if(data.customer_phone!="")
              $("#phno").val(data.customer_phone);
	      if(data.customer_address !="")
              $("#commen").val(data.customer_address);
		
               
            }
        }
    });
	}     */
    
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
					 
					 	$('.qt tbody>tr:last').children().find('.item_id').attr('name','record['+counter+'][item_id]item_id[]');
	 					$('.qt tbody>tr:last').children().find('.sub_item_id').attr('name','record['+counter+'][sub_item_id]sub_item_id[]');
						$('.qt tbody>tr:last').children().find('.item').attr('name','record['+counter+'][item]item[]');
						$('.qt tbody>tr:last').children().find('.qty').attr('name','record['+counter+'][qty]qty[]');
						$('.qt tbody>tr:last').children().find('.price').attr('name','record['+counter+'][price]price[]');
						$('.qt tbody>tr:last').children().find('.total').attr('name','record['+counter+'][total]total[]');
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
     
	 $('.qt tbody>tr:last').children().find('.item_id').attr('name','record['+counter+'][item_id]item_id[]');
	 $('.qt tbody>tr:last').children().find('.sub_item_id').attr('name','record['+counter+'][sub_item_id]sub_item_id[]');
	 $('.qt tbody>tr:last').children().find('.item').attr('name','record['+counter+'][item]item[]');
	 $('.qt tbody>tr:last').children().find('.qty').attr('name','record['+counter+'][qty]qty[]');
	 $('.qt tbody>tr:last').children().find('.price').attr('name','record['+counter+'][price]price[]');
	 $('.qt tbody>tr:last').children().find('.total').attr('name','record['+counter+'][total]total[]');
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
	
	
	
	var row=$('.qt tr:last');
	var len=$('.qt tr').length;
	var txt = $('.qt tbody>tr:last').children().find('.item').val();
	if(txt == '' && len == "2")
	{
		alert("Can't Place Empty Order Sorry !!!");
		return false;
	}
	calculateSummary();
    if(txt == '' && len != "2"){ 
		$('.qt tr:last').remove();
	}
        gonaPrint();
       
	if(confirm("Are you sure to place this Order")){
        
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
        var summarytotal = parseInt($("#summarytotal").val());
        var servicecharge = parseInt($("#servicecharge").val());
        var discount = parseInt($("#discount").val());
        var scc = parseInt($("#servicecharge").val());
        var amountreturn = parseInt($("#amountreturn").val());
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
 		var str = "<table width='288' border='0' align='center' cellpadding='0' cellspacing='0'>\
  <tr>\
<td align='center' valign='top'>\
			<style type='text/css'>\
            	body { margin-left: 0px; margin-top: 0px; margin-right: 0px; margin-bottom: 0px; }\
            	#newprint { width:288px; margin:auto; height:auto; border:0px solid red; }\
            	body,td,th { font-family: Verdana; font-size: 10px; }\
            	.small{ font-family:verdana; font-size:10px;}\
            </style>\
<table width='100%' border='0' align='center'>\
            <tr>\
            <td colspan='5' align='center' valign='top'><strong><img width='150px' src='images/logo-qt.png'></strong></td>\
			<tr>\
            <td colspan='3' align='left'><span class='small'>"+(cusname=='' ? 'Customer' : cusname)+"</span></td>\
            <td width='52' align='center'><span class='small'>"+sdtp+"</span></td>\
            <td width='130' align='right'><span class='small'>"+(phno=='' ? '-' : phno)+"</span></td>\
            </tr>\
			<tr>\
            <td colspan='3' align='left' ><span class='small'>"+orderno+"</span></td>\
            <td width='52' align='right' colspan='2'><span class='small'>"+datetime+"</span></td>\
            </tr>\
      </table>\
	    	<hr>\
            <table width='100%' border='0'>\
            <tr bgcolor='#F0F0F0'>\
            <td width='10'><strong>S#</strong></td>\
			<td width='100'><strong>Item</strong></td>\
            <td width='34' align='right'><strong>Qty</strong></td>\
            </tr>";
			str2 = "";
			var Tqty=0;
			var counter=1;
			for(i=0; i< item.length; i++){
                str2 +="<tr>\
						<td>"+counter+"</td>\
                		<td>"+item[i]+"</td>\
                		<td align='right'>("+qty[i]+")</td>\
                	</tr>";
					counter++;
					Tqty = parseInt(Tqty) + parseInt(qty[i]);
            }
			
			str += str2;
            str +="<tr>\
				<td align='center' colspan='3'><hr></td>\
			</tr>\
			<tr>\
				<td align='center'><strong></strong></td>\
				<td  align='center'><strong>Total Qty</strong></td>\
				<td align='right'><strong>("+Tqty+")</strong></td>\
			</tr>\
			</table>\
            <hr>\
            <table width='100%' border='0'>\
			<tr>\
     			<td colspan='2'>"+commen+"</td>\
            </tr>\
			<hr>\
 			<tr>\
          <td colspan='2' align='center' valign='top'><span class='small'><br /><?=$org_address?><br><strong>"+msg+"</strong><br>NTN : <?=$ntn?></span></td>\
           </tr>\
      </table>\
	</td>\
  </tr>\
</table>\
<p style='page-break-before: always'></p>";
    
	var win = window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=350,height=700');
	win.document.write(str);
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
  <div id="upr" style="overflow-x: scroll; height:125px; scrollbar-width:thin; ">
	<?php
		include("pos_header_2.php");
	?>
  </div>
  <div class="admin-wrapper">
   <table width="100%">
   <tr>
   <td>
	<a href="cms.php"><strong>Back to Dashboard</strong></a>
	</td>
	<td align='right'>
	  <img src="images/refresh.png" width="24" height="24" style="cursor:pointer;" alt="Refresh" text="Refresh" id="refresh">	
	  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  </td>
	</table>
    <div id="main">
   <div id="one" class='subitems'> 
<h1 align="center">Welcome to <?=$org_name?> (Point Of Sale)</h1><center>
<img src='images/PIZZ.JPG' WIDTH='50%'>
</center>
<br><br><br><br>
   </div>
   <div id="two" class='subitems section-to-print' style="width:400px !important;">
   <form name='formqty' id='formqty' method='POST' action='possave.php'>
  <table border='0' width='100%' align='center'>
  		<tr>
			<td colspan="3">
<input id='gsearch' name='gsearch' placeholder="Global Search" type='text' class='admin-inputBox' size="35" style='border:1px solid #f0f0f0; font-size:20px; height:30px; text-transform:uppercase;'>
			</td>
		</tr>
		<tr>
			<td colspan='2' align='left' valign='top' width='10%'> 
				
				<img src="images/logo-qt.png" width='130'>
			</td>
			<td colspan='2' align='left' valign='top'>
			<input type="text" name="orderno" id="orderno" value="<?php echo"Order #".$maxID; ?>" readonly size="22" style="font-size:10px; border:0px; text-align:right; width:110px; height:25px;"><br>
			<input type='text' name='datetime' id='datetime' size="22" style='font-size:10px; border:0px; text-align:right; width:110px; height:25px;'><br>
			<input id="uname" value="<?="User: ".$_SESSION['user_id']?>" type='text' size="22" style='font-size:10px; border:0px; text-align:right; width:110px; height:25px;'>
			</td>
			
		</tr>
		<tr>
			<td width='50%'>
                            <input id='cusname' name='cusname' placeholder="Customer Name" type='text' class='admin-inputBox' size="27" style='border:1px solid #f0f0f0; font-size:10px; height:23px;'>
			</td>
			<td>
			<select name=sdtp class='sdtp' style='border:1px solid #f0f0f0; font-size:11px;'>
						<option value='S'>Service</option>
						<option value='D'>Delivery</option>
						<option value='T'>Take Away</option>
					</select>
			</td>
			<td colspan='2' align='left' valign='top'>
			<input placeholder='Phone #' id="phno" name='phno' type='text' class='admin-inputBox' size="16" style='border:1px solid #f0f0f0; height:24px; font-size:12px;' onKeyPress="return numbersonly(event);"></td>
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
					<th width='10px'>&nbsp;</th>
				</tr>
			
			<tbody>
				<tr>
					
					<td>
					<input type='hidden' class='item_id' name="record[0][item_id]item_id[]" value="">
					<input type='hidden' class='sub_item_id' name="record[0][sub_item_id]sub_item_id[]" value="">
					<input type='text' size='20' style='border:1px solid #f0f0f0; font-size:14px; height:20px;' name="record[0][item]item[]" class='item'  value="">
					</td>
					<td align='right'>
					<input type='text' size='1'  style='border:1px solid #f0f0f0; font-size:14px; height:20px; text-align: right; ' name="record[0][qty]qty[]" class='qty' value='1' onKeyPress="return numbersonly(event);">
					</td>
					<td>
					<input type='button' value='+' class='inc inc-dec' style='background-color:#E7FEE0;'>
					</td>
					<td>
					<input type='button' value='-' class='dec inc-dec' style='background-color:#FFCCFF;'>
					</td>
					<td align='right'>
					<input type='text' size='2' style='border:1px solid #f0f0f0; font-size:14px; height:20px; text-align: right; ' name="record[0][price]price[]" class='price' value='0'>
					</td>
					<td>
					<input type='text' name="record[0][total]total[]" class='total'  style='border:1px solid #f0f0f0; font-size:14px; height:20px; text-align: right; ' size='3' value='0' readonly>
					</td>
					<td>
					<input type='button' value="+" style='height: 20px; width: 20px; border-radius: 50%; border: 0px solid white; background-color:red; color:white; float:left; cursor:pointer;' class='addrow'>
					</td>
					<td align='right'>
					<input type='button' value="X" style='height: 20px; width: 20px; border-radius: 50%; border: 0px solid white; background-color:yellow; color:black; float:left; cursor:pointer;' class='remove'>
					</td>
					
					
				</tr>
				</tbody>
			</table>
<textarea style="font-family: 'Calibri'; border:1px solid #f0f0f0; " id="commen" name='commen' cols='42' rows='1' placeholder='Enter Comments, if required'></textarea><br>
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
<input type="checkbox" name="plo">
</td>
</tr>
</table>
 
			<table class='summary' border='0' width='100%' align='center'> 
				<tr>
					<td><b><font color='black' size="2">TOTAL:</font><b></td>
					<td><b><input type='text' size='5' style='text-align: center; border:1px solid #f0f0f0; font-weight:bold;' readonly name='summarytotal' value='0' id = 'summarytotal'><b></td>
					<td> <b><font color='black'>SC:</font><b></td>
					<td><input type='text' size='4' style='text-align: right; border:1px solid #f0f0f0;'  name='servicecharge' value='0' id = 'servicecharge' onKeyPress="return numbersonly(event);"></td> 
					<td class='no-print'><font color='purple'>Discount</font></td>
					<td class='no-print'><input type='text' size='4' style='text-align: right; border:1px solid #f0f0f0;' name='discount' value='0' id = 'discount' onKeyPress="return numbersonly(event);"></td>
				</tr>
				<tr>
					<td class='no-print'><font color='black'>Amt PAID:</font></td>
					<td class='no-print'><input type='text' size='4' style='text-align: right; border:1px solid #f0f0f0;' name='amountpaid' value='0' id = 'amountpaid' onKeyPress="return numbersonly(event);"></td>
					<td class='no-print'><font color='black'>Amt RTRN:</font></td>
					<td class='no-print'><input type='text' size='4' style='text-align: right; border:1px solid #f0f0f0;' readonly name='amountreturn' value='0' id = 'amountreturn'></td>
					<td class='no-print'><font color='black'>Amt STATUS:</font></td>
					<td class='no-print'>
					<select style='border:1px solid #f0f0f0;' name='amtstatus' id="amtstatus">
						<option value='P'>Not Paid</option> <!-- P for Pending -->
						<option value='R'>Paid</option> <!-- R for Received -->
					</select>
					</td>
				</tr>	
			</table>		
		</tr>
		<tr>
			<th colspan="4" align="center"><br>
			<font size='1'>
			<p align='center'>
			<?=$org_address?>
			<?php /*?>thamesburger [dot] com || FB: thames [dot] burger <br><?php */?>
			 <br>
			<b><?=(isset($datamsg['msg'])?$datamsg['msg']:'')?></b>
			</p>
			</font>
			</th>
		</tr>
   </table>

   <p align='right' class='no-print'>
		<input type="image" id='placeorder' name="submit" src="images/placeorder.png" width='25' border="0" alt="Submit" />
   </p>
<input type="hidden" id="tax_new" value="<?=$tax_new?>">
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
</div>
</div>
<?php include 'include/footer.php' ?>
</body>
</html>