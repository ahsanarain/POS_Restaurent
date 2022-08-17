<?php
	include("Connections/cn.php");
	include("lib/iq.php");
        $mon="";
        $year="";
        if(isset($_REQUEST['mon'])){
            $dt=explode("-",$_REQUEST['mon']);
            $mon=$dt[1];
            $year = $dt[2];
        }
        
	$sql1 = "	
	SELECT
	distinct(DATE_FORMAT(`attendance_date`,'%d')) as days
	FROM 
	`staff_attendance` where DATE_FORMAT(`attendance_date`,'%m') = '$mon' and "
                . "DATE_FORMAT(`attendance_date`,'%Y') = '$year' order by `attendance_date` ASC";
	
	$result1 = mysqli_query($cn, $sql1);
	$dataArr1 = array();
	while($data1 = mysqli_fetch_assoc($result1)){
		$dataArr1[] = $data1;
	}
        		
	$sql2 = "SELECT sr.sname,sa.*
		from
		staff_reg sr,
		staff_attendance sa
		where 
		sr.sid = sa.sid
		and sr.staff_status = '1'

		group by sr.sname
		order by sr.sid asc";
	$result2 = mysqli_query($cn, $sql2);
	$dataArr2 = array();
	while($data2 = mysqli_fetch_assoc($result2)){
		$dataArr2[] = $data2;
	}
?>
<html>
    <title> Attendance Vs Salary Report</title>
<head>
<style>
body, table, td, th {
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:11px;
    border: 1px thin black;
	border-collapse: collapse;
}
.notfirst:hover {
    background-color: yellow;
}

</style>
<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/tab.js"></script>
<link rel="stylesheet" type="text/css" href="css/file.css" media="all"></link>
<link rel="stylesheet" type="text/css" href="css/tablestyle.css" media="all"></link>
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css" media="all"></link>
<link rel="stylesheet" type="text/css" href="css/styles.css" media="all"></link>
<script>
$(document).ready(function(){
		
		$("#mon").datepicker({
                    dateFormat: 'dd-mm-yy'
                });
            });
</script>
</head>
<body>
<?php include 'include/header.php' ?>

<div class="admin-greyBg">
  <div class="admin-wrapper">
    <div id="admin-leftNav" class="admin-fLeft">
<?php include 'include/left_nav.php' ?>
    </div>
    <div id="admin-body" class="admin-fRight" style="overflow: auto; overflow-y: hidden;">
	<h1>Attendance vs Salary Detail </h1>
	Select Date to Proceed:
	<form name='frmsearch' action='salaryCalc.php'>
	 <input type="text" name='mon' id='mon' value="<?=Date('d-m-Y')?>" class='admin-inputbox' onChange="this.form.submit()">
	</form>
<?php
	if(!empty($mon)){
	echo "<h1 align='center' style='color:green'>Attendance For the Month  of ".$mon."-".$year."<h1>";	
?>
<table border="1" cellpadding="2" cellspacing="2" width="100%">
	<tr bgcolor='#F0F0F0'>
		<th colspan="4">&nbsp;
			
		</th>
		<th colspan="<?=sizeof($dataArr1)+5;?>">
			Days
		</th>
	</tr>
	<tr>
		<th>
			Name
		</th>
		<?php
			
		foreach($dataArr1 as $data1){
				
		?>
		<th>
			<?="D ".$data1['days'];?>
		</th>
		<?php
		}
		?>
		<th>
			Total SC
		</th>
		<th>
			Total Fin
		</th>
		<th>
			Total Adv
		</th>
		<th>
			Final Sal
		</th>
	</tr>
	<?php
	foreach($dataArr2 as $data2){
		$total_sc = 0;
		$total_sal= 0;
		$total_fin= 0;
		$total_adv = 0;
		
		$final_salary = 0;

	?>
		<tr title="<?='ID('.$data2['sid'].')'.$data2['sname'];?>" class="notfirst">
			<td><?=$data2['sname'];?>

			</td>
			<?php
			
			foreach($dataArr1 as $data1){
				
				$sql3="select `ssalary`,`attendance_status`, `attendance_today_service_charge`,`fine`,`advanced`,`service_charge_current`,`attendance_percent`,`attendance_date` from staff_attendance where sid='".$data2['sid']."' and DATE_FORMAT(`attendance_date`,'%m') = '$mon' and DATE_FORMAT(`attendance_date`,'%Y') = '$year'  and DATE_FORMAT(`attendance_date`,'%d') = '".$data1['days']."'";	 
				
				$result3 = mysqli_query($cn, $sql3);
					$dataArr3 = array();
					while($data3 = mysqli_fetch_assoc($result3)){
						$dataArr3[] = $data3;
					}
					if(!isset($dataArr3[0]['ssalary'])){
						$changedSal = 0;
					}
					else{
						$changedSal = $dataArr3[0]['ssalary'];
					}
					if(!isset($dataArr3)){
					echo "<br><br><h3 align='center' style='color:red;'>Sorry No Record Exists for month ".$_GET['mon']."</h3><br>";
					}
					
					if(!isset($dataArr3[0]['attendance_today_service_charge']) || !isset($dataArr3[0]['service_charge_current']) || !isset($dataArr3[0]['fine']) || !isset($dataArr3[0]['advanced']) || !isset($dataArr3[0]['attendance_status'])){
					
													

							$atsc = 0;					
							$tcs= 0;
							$fin = 0;
							$adv = 0;
							$atten = 0;
							$total_sc += $atsc;
							
							$total_sal = $total_sc + $changedSal;	
							
							$total_fin = $total_fin + 0;
							$total_adv = $total_adv + 0;
							
							$final_salary = $total_sal - ($total_fin + $total_adv);
							
							$percent = 0;
					}
					else{
							
							
							$atsc = $dataArr3[0]['attendance_today_service_charge'];					
							$tcs=$dataArr3[0]['service_charge_current'];
							$fin = $dataArr3[0]['fine'];
							$adv = $dataArr3[0]['advanced'];
							$atten = $dataArr3[0]['attendance_status'];
							$total_sc += $atsc;
							
							$total_sal = $total_sc + $changedSal;	
							
							$total_fin = $total_fin + $dataArr3[0]['fine'];
							$total_adv = $total_adv + $dataArr3[0]['advanced'];
							
							$final_salary = $total_sal - ($total_fin + $total_adv);
							
							$percent = $dataArr3[0]['attendance_percent'];

					}
					
			?>
			<td align="right">
				<table border='1' width='100%'>
				<tr bgcolor='#F0F0F0'>
					<th>
						A
					</th>
					<th>
						F
					</th>
					<th>
						Ad
					</th>
					<th>
						%
					</th>
					<th>
						TS
					</th>
				</tr>
				<tr>
					<td align="center" <?= ($atten=="A")? "bgcolor='red'": "bgcolor='white'"?>>
							<?=$atten?>
					</td>
					<td align="right" <?= ($fin>0)? "bgcolor='lightpink'": "bgcolor='white'"?>>
							<?=$fin?>
					</td>
					<td align="right" <?= ($adv>0)? "bgcolor='lightgreen'": "bgcolor='white'"?>>
							<?=$adv?>
					</td>
					<td align="right">
							<?=$percent?>
					</td>
					<td align="right">
							<?=$atsc.'/'.$tcs?>
					</td>
				</tr>
				</table>
			</td>
			
			<?php
			}
			?>
			<th align="right">
				<?=$total_sc?>
			</th>
			<th align="right" bgcolor='lightpink'>
				<?=$total_fin?>
			</th>
			<th align="right" bgcolor='lightgreen'>
				<?=$total_adv?>
			</th>
			<th align="right">
				<?=$final_salary?>
			</th>
		</tr>
	<?php
	}
	?>
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