<style>
.rcvdbtn{

	border-radius:4px;
	border:0px; 
	cursor:pointer; 
	background-color:#3a3e45; 
	color:white; 
	padding:3px;
	font-size:10px;
}
.rcvdbtn:hover{
 
	border-radius:4px;
	border:0px; 
	cursor:pointer; 
	background-color:white; 
	color:#3a3e45; 
	padding:3px;
	font-size:10px;
}
.edtOrderbtn{
	
	border-radius:4px;
	border:0px; 
	cursor:pointer; 
	background-color:#006600; 
	color:white; 
	padding:3px;
	font-size:10px;
}
.edtOrderbtn:hover{
	
	border-radius:4px;
	border:0px; 
	cursor:pointer; 
	background-color:#FF9900; 
	color:#000000; 
	padding:3px;
	font-size:10px;
}
.cancelbtn{
	border-radius:4px;
	border:0px; 
	cursor:pointer; 
	background-color:maroon; 
	color:yellow; 
	padding:3px;
	font-size:10px;
}
.cancelbtn:hover{ 
	border-radius:4px;
	border:0px; 
	cursor:pointer; 
	background-color:yellow; 
	color:maroon; 
	padding:3px;
	font-size:10px;
}


.prntbtnK{

	border-radius:4px;
	border:0px; 
	cursor:pointer; 
	background-color:#FFFF00; 
	color:black; 
	padding:3px;
	font-size:10px;
}
.prntbtnK:hover{
 
	border-radius:4px;
	border:0px; 
	cursor:pointer; 
	background-color:#000000; 
	color:white; 
	padding:3px;
	font-size:10px;
}

.prntbtnC{

	border-radius:4px;
	border:0px; 
	cursor:pointer; 
	background-color:#FFFF00; 
	color:black; 
	padding:3px;
	font-size:10px;
}
.prntbtnC:hover{
 
	border-radius:4px;
	border:0px; 
	cursor:pointer; 
	background-color:#000000; 
	color:white; 
	padding:3px;
	font-size:10px;
}
</style>
<?php
	if(isset($_POST['method'])){
	if($_POST['method']=='orderCancelled'){
		$itemID = $_POST['itemid'];
		$updateQry = "UPDATE ORDER_TAB SET STATUS = '0', AMOUNT_STATUS = 'C' WHERE ORDER_ID = '".$itemID."'";
		mysqli_query($cn, $updateQry);		
		exit();
	}
}


if(isset($_POST['method'])){
	if($_POST['method']=='paymentMade'){
	$date = date('Y-m-d H:i:s');
		$itemID = $_POST['itemid'];
		$updateQry = "UPDATE ORDER_TAB SET STATUS = '0', AMOUNT_STATUS = 'R', order_rec_date = '".$date."'  WHERE ORDER_ID = '".$itemID."'";
		mysqli_query($cn, $updateQry);	
		
		 $sql = "select sub_item_id, sum(qty) as qty from sub_order_tab where order_id = '$itemID' order by qty";
                $row_rsauto = mysqli_query($cn, $sql) or die(mysqli_error($cn));

                $arrAuto=array();
                while($data= mysqli_fetch_assoc($row_rsauto)){
                        $arrAuto[] = $data;
                }
                $sql = "select 
                        a.sub_item_ing_id,
                        a.amt
                        from
                        sub_item_ing a,
                        stock b
                        where
                        a.sub_item_ing_id = b.sub_item_ing_id
                        and a.sub_item_id = '".$arrAuto[0]['sub_item_id']."'
                        ";
                $rowrsauto=mysqli_query($cn, $sql,$cn);
               $arrAuto1=array();
                while($data1= mysqli_fetch_assoc($rowrsauto)){
                        $arrAuto1[] = $data1;
                }
                for($i=1; $i<=$arrAuto[0]['qty']; $i++){
                    foreach($arrAuto1 as $data){
                        $sql="
                                update 
                                stock 
                                SET 
                                qty_in = qty_in - ".$data['amt']."  
                                 where sub_item_ing_id = '".$data['sub_item_ing_id']."'
                             ";
                    //    print_r($sql);
                        mysqli_query($cn, $sql); 
                    }
                }
			
		exit();
	}
}

?>

<?php
$pendingOrders = "select * from order_tab where status = '1' and plo='0' ORDER BY ORDER_ID DESC";
$row = mysqli_query($cn, $pendingOrders) or die(mysqli_error($cn));
		$arrAuto=array();
		while($data= mysqli_fetch_assoc($row)){
			$arrAuto[] = $data;
		}
if(empty($arrAuto)){
    echo "<h1 align=center>No Orders At the moment</h1>";
}                
?>

<table border = '0' cellpadding='3' cellspacing = '3' align="center">
	
	<?php 
                $row=0;
				
		foreach($arrAuto as $data){
                    
                    if($row==200){
                        echo "<tr>";
                        $row=0;
                    }
                    
	?>
            
            
		<td align='left' valign='top' bgcolor='#f0f0f0' style='border:1px; border-color:red;'>
                        <?php
                              if($data['amount_status']=='P')
                                  $bgcolor="bgcolor='#FFE1FF'";
                              else
                                  $bgcolor="bgcolor='#DFFFD5'";
                        ?>
			<table class='summary' <?=$bgcolor?> border="0" width="190px" cellpadding='2' cellspacing='2'>
					<td align='center'>
					<?php if($data['amount_status']=='P'){ ?>
					<font color='red' size='1'>
					<?php
					}else{
					?>
					<font color='green' size='1'>
					<?php
					}
					?>
					
						<U>Ordr #<?= $data['order_id']?></U><br>
                                                <font size="1" face="verdana" color="black"><?=date("d-m-Y H:i:s",strtotime($data['date_time']));?></font><br>
						<?=substr($data['customer_name'],0,12)?>: <b><font size='3'><?=(($data['amount']+$data['service_charge'])- $data['discount'])?></font></b> Rs<br>
                                                <?php
												if(!empty($data['phone'])){
												?>
												<font color='black'>P#<?=$data['phone']?></font><br>
												<?php
												}
												?>
                                              <!--  Add: <?=$data['comments']?><br>   --> 
							
					</font>
					</td>
				</tr>
				<?php 
					$ID = $data['order_id'];
					$qrysub = "SELECT ITEM,QTY FROM SUB_ORDER_TAB WHERE ORDER_ID = '$ID'";
					$rowsub = mysqli_query($cn, $qrysub) or die(mysqli_error($cn));
					$arrsub=array();
					while($data= mysqli_fetch_assoc($rowsub)){
						$arrsub[] = $data;
					}
					$str='';
					foreach($arrsub as $sub){
						$str .= $sub['ITEM']." Qty (".$sub['QTY'].")\n";
					}
						$str .= $data['comments'] ?? '';
					?>
					<tr>
						<th title='<?=$str?>'>
<input type='button' class='rcvdbtn' act = '<?=$ID?>' value='Rec'> 
<input type='button' class='cancelbtn' act = '<?=$ID?>' value='Can'>
<input type='button' class='edtOrderbtn' act = '<?=$ID?>' value='Edt' onclick=" window.open('pos_activity _edt.php?url_id=<?=$ID?>', '_blank');">
<input type='button' class='prntbtnC' act = '<?=$ID?>' value='Prnt Cus'>
<input type='button' class='prntbtnK' act = '<?=$ID?>' value='Prnt Kch'>
						</th>
					</tr>
			</table>
		</td>
	<?php
                    $row++;
		}
	?>
	</tr>
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
   
   $(document).on("click",".cancelbtn",function(){
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
   </script>