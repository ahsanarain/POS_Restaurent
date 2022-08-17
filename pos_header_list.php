<?php
include('lib/iq.php');
include('Connections/cn.php');



$pendingOrders = "select * from order_tab where status = '1' ORDER BY ORDER_ID DESC";
$row = mysqli_query($cn, $pendingOrders) or die(mysqli_error($cn));
		$arrAuto=array();
		while($data= mysqli_fetch_assoc($row)){
			$arrAuto[] = $data;
		}
$noOfRec = count($arrAuto);

if(empty($arrAuto)){
    echo "<h1 align=center>No Orders At the moment</h1>";
} 

?>
<br>
<style>
.rcvdbtn{

	border-radius:4px;
	border:0px; 
	cursor:pointer; 
	background-color:#3a3e45; 
	color:white; 
	padding:3px;
	font-size:11px;
}
.rcvdbtn:hover{
 
	border-radius:4px;
	border:0px; 
	cursor:pointer; 
	background-color:white; 
	color:#3a3e45; 
	padding:3px;
	font-size:11px;
}
.edtOrderbtn{
	
	border-radius:4px;
	border:0px; 
	cursor:pointer; 
	background-color:#006600; 
	color:white; 
	padding:3px;
	font-size:11px;
}
.edtOrderbtn:hover{
	
	border-radius:4px;
	border:0px; 
	cursor:pointer; 
	background-color:#FF9900; 
	color:#000000; 
	padding:3px;
	font-size:11px;
}
.cancelbtn{
	border-radius:4px;
	border:0px; 
	cursor:pointer; 
	background-color:maroon; 
	color:yellow; 
	padding:3px;
	font-size:11px;
}
.cancelbtn:hover{ 
	border-radius:4px;
	border:0px; 
	cursor:pointer; 
	background-color:yellow; 
	color:maroon; 
	padding:3px;
	font-size:11px;
}


.prntbtnK{

	border-radius:4px;
	border:0px; 
	cursor:pointer; 
	background-color:#FFFF00; 
	color:black; 
	padding:3px;
	font-size:11px;
}
.prntbtnK:hover{
 
	border-radius:4px;
	border:0px; 
	cursor:pointer; 
	background-color:#000000; 
	color:white; 
	padding:3px;
	font-size:11px;
}

.prntbtnC{

	border-radius:4px;
	border:0px; 
	cursor:pointer; 
	background-color:#FFFF00; 
	color:black; 
	padding:3px;
	font-size:11px;
}
.prntbtnC:hover{
 
	border-radius:4px;
	border:0px; 
	cursor:pointer; 
	background-color:#000000; 
	color:white; 
	padding:3px;
	font-size:11px;
}


</style>
<table border = '0' cellpadding='0' cellspacing = '0' width='97%' align="center" class='table-hover' style="font-size:12px;">
    <thead>
    <tr>
        <td width="34"  class="admin-tbHdRow1">Sno</td>
        <td width="84"  class="admin-tbHdRow1">Order #</td>
        <td width="158"  class="admin-tbHdRow1">Date & Time</td>
        <td width="203"  class="admin-tbHdRow1">Customer</td>
        <td width="108"  class="admin-tbHdRow1">Phone#</td>
        <td width="141"  class="admin-tbHdRow1">Address</td>
        <td width="120"  class="admin-tbHdRow1">Pay Later On </td>
		<td width="251"  class="admin-tbHdRow1">Actions</td>
        <td  class="admin-tbHdRow1 admin-tbHdRow3" width='178'>Print</td>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach($arrAuto as $data){
	$bgcolor="";
		 if($data['amount_status']=='P')
		  $bgcolor="bgcolor='#FFE1FF'";
	  else
		  $bgcolor="bgcolor='#DFFFD5'";
        $ID = $data['order_id'];
        $qrysub = "SELECT ITEM,QTY FROM SUB_ORDER_TAB WHERE ORDER_ID = '$ID'";
        $rowsub = mysqli_query($cn, $qrysub) or die(mysqli_error($cn));
        $arrsub=array();
        while($data1= mysqli_fetch_assoc($rowsub)){
                $arrsub[] = $data1;
        }
        $str='';
        foreach($arrsub as $sub){
                $str .= $sub['ITEM']." Qty (".$sub['QTY'].")\n";
        }
                $str .= $data['comments'];
					
    ?>
    <tr title='<?=$str?>' <?=$bgcolor?>>
        <td class="admin-tbRow1" valign="top"><?=$noOfRec;?><?php $noOfRec--; ?></td> 
        <td class="admin-tbRow1" valign="top">Order #<?=$data['order_id']?></td>
        <td class="admin-tbRow1" valign="top"><?=date("d-m-Y H:i:s",strtotime($data['date_time']));?></td>
        <td class="admin-tbRow1" valign="top"><?=substr($data['customer_name'],0,12)?> &nbsp;&nbsp;&nbsp;Rs. 
		<?=(($data['amount']+$data['service_charge'])- $data['discount'])?>/-</td>
        <td class="admin-tbRow1" valign="top"><?=$data['phone']?></td>
        <td class="admin-tbRow1" valign="top"><?=$data['comments']?></td>
        <td class="admin-tbRow1" valign="center" align="center" <?=$data['plo']=="1"  ? "style='background-color:red; color:yellow;'" : '' ?>>
		<?=$data['plo']=="1" ? "<b>PLO</b>" : ""?>
		</td>
        <td class="admin-tbRow1" valign="top">
        <input type='button' class='rcvdbtn' act = '<?=$ID?>' value='Received'>
		<input type='button' class='edtOrderbtn' act = '<?=$ID?>' value='Edit Order' onclick=" window.open('pos_activity _edt.php?url_id=<?=$ID?>', '_blank');">
        <input type='button' class='cancelbtn' act = '<?=$ID?>' value='Cancel'>		</td>
		<td class="admin-tbRow1 admin-tbRow2" valign="top" align="center">
		<input type='button' class='prntbtnC' act = '<?=$ID?>' value='For Customer'>
		<input type='button' class='prntbtnK' act = '<?=$ID?>' value='For Kitchen'>        </td>
    </tr>
    <?php
    }
    ?>
    </tbody>
   </table>
 
   <script>
   $(document).on("click",".prntbtnK",function(){
   			var order_id = $(this).attr('act');
			var newWin = window.open('','','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=340,height=700');
			$.ajax({
        		type:'POST', 
        		url: 'forprint.php', 
        		data:{method:'printKitchen',order_id:order_id}, 
        		success: function(response) {
				newWin.document.write(response);
        		newWin.document.close();
        		newWin.focus();
        		newWin.print();
        		newWin.close();	
        }
    });
   });
   
   $(document).on("click",".prntbtnC",function(){
   			var order_id = $(this).attr('act');
			var newWin = window.open('','','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=340,height=700');
			$.ajax({
        		type:'POST', 
        		url: 'forprint.php', 
        		data:{method:'printCustomer',order_id:order_id}, 
        		success: function(response) {
				newWin.document.write(response);
        		newWin.document.close();
        		newWin.focus();
        		newWin.print();
        		newWin.close();	
				
        }
    });
   });
   </script>