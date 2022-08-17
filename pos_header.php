<style>
.rcvdbtn{
	float:left; 
	border-radius:4px;
	border:0px; 
	cursor:pointer; 
	background-color:#3a3e45; 
	color:white; 
	padding:3px;
}
.rcvdbtn:hover{
	float:left; 
	border-radius:4px;
	border:0px; 
	cursor:pointer; 
	background-color:white; 
	color:#3a3e45; 
	padding:3px;
}
.cancelbtn{
	float:right; 
	border-radius:4px;
	border:0px; 
	cursor:pointer; 
	background-color:maroon; 
	color:yellow; 
	padding:3px;
}
.cancelbtn:hover{
	float:right; 
	border-radius:4px;
	border:0px; 
	cursor:pointer; 
	background-color:yellow; 
	color:maroon; 
	padding:3px;
}
</style>
<?php
include('lib/iq.php');
$pendingOrders = "select * from order_tab where status = '1' ORDER BY ORDER_ID DESC";
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
                    
                    if($row==8){
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
			<table class='summary' <?=$bgcolor?> border="0" width="148px" cellpadding='3' cellspacing='3'>
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
						$str .= $data['comments'];
					?>
					<tr>
						<th title='<?=$str?>'>
<input type='button' class='rcvdbtn' act = '<?=$ID?>' value='Received'> 
&nbsp;
<input type='button' class='cancelbtn' act = '<?=$ID?>' value='Cancel'>
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