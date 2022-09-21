<style>
.order{
		font-family:verdana;
		font-size:11px;
		border: 1px solid silver;
		border-collapse: collapse;
	}
</style>
<?php
include('Connections/cn.php');
include('lib/iq.php');
$pendingOrders = "select * from order_tab where status = '1' ORDER BY ORDER_ID DESC";
$row = mysqli_query($cn, $pendingOrders) or die(mysqli_error($cn));
		$arrAuto=array();
		while($data= mysqli_fetch_assoc($row)){
			$arrAuto[] = $data;
		}
$file = ucwords(strtolower(str_replace ("_"," ",basename($_SERVER["SCRIPT_FILENAME"], '.php'))));
?>
<title><?=$file?> For Kitchen</title>
<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<head>
<script>
setTimeout(function(){
   window.location.reload(1);
}, 5000);
</script>
</head>
<div id="msg" align="center">
<h3 align='center' style='font-family:verdana; color:maroon;'>Point Of Sale Order Page </h3>
</div>
<table border = '0' cellpadding='10' cellspacing = '10' >
	<tr>
	<?php 
		$count = 1;
		$comments = '';
		foreach($arrAuto as $data){
			$comments = $data['comments'];
	?>
		<td align='left' valign='top' bgcolor='#FFF4E6' style='border:1px; border-color:red;'>
			<table class='order' style='border:0px;' cellpadding='2' cellspacing='2'>
				<tr>
					<td>
					<font color="#333333" size='2'><b>
						<U>ORDER #<?=$data['order_id']?></U><br>
					</font>
					<font color='#cc0000' size='3'><b>
						<?= substr($data['customer_name'],0,11)?> <br>
					</font>
					<font color="#0066FF" size='3'><b>
						<?= substr($data['phone'],0,11)?>
					</font>
					</td>
				</tr>
				<?php 
					$ID = $data['order_id'];
					$qrysub = "select item,qty from sub_order_tab where order_id = '$ID'";
					$rowsub = mysqli_query($cn, $qrysub) or die(mysqli_error($cn));
					$arrsub=array();
					while($data= mysqli_fetch_assoc($rowsub)){
						$arrsub[] = $data;
					}
					foreach($arrsub as $sub){
					?>
					<tr>
						<td align='left' valign='top'>
						<?="<font size='4'>".$sub['ITEM']."</font> (<font color='#cc0000' size='2'><b>".$sub['QTY']."</font></b>)"?>
						</td>
					</tr>
					<?php
					}
					?>
					<tr>
						<td>
							<font color='#cc0000' size='3'>
							<b>
								<?=strtoupper($comments)?>
							</b>
							</font>
						</td>
					</tr>
			</table>
	  </td>
		<?php
		$count ++;
		if($count == 7){
		?>	
  </tr>
			<tr>
						
		<?php
			$count = 1;
			}
			
		?>
	<?php
		}
	?>
	</tr>
</table>