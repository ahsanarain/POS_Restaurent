<?php
	include("Connections/cn.php");
	include("lib/iq.php");
	require_once('sessions/mysessionscript.php');	
	
	$dataArr[] = array();
	$func='';
	if(isset($_REQUEST['txtdate'])){
						$date = $_REQUEST['txtdate'];
						$date=date_create($date);
						$date=date_format($date,"Y-m-d");
	$sql = "select count(*) cc from staff_attendance where attendance_date ='".$date."'";
	}else{
		$sql = "select count(*) cc from staff_attendance where attendance_date ='".date("Y-m-d")."'";
	}
	$result = mysqli_query($cn, $sql);
	$data = mysqli_fetch_assoc($result);
	
	$res = $data['cc'];
	$style = "style='display:block;'";
	
	if(!empty($_POST['saveAttendance'])){
		if($_POST['saveAttendance']=="yes"){
			
					
			$date = $_POST['today_date'];
			$date=date_create($date);
			$date=date_format($date,"Y-m-d");
			
			$servic = $_POST['srvs'];
			$status = $_POST['status'];
			$advanc = $_POST['advanced'];
			$fine   = $_POST['fine'];
			$sal    = $_POST['sal'];
			
			$csc=$current_service_charge = $_POST['service_charge_current'];
			
			$tservice = $_POST['today_service_charge'];
			
			$finalArr = array();
			$sql;
			foreach($_POST['id'] as $id){
		//	$finalArr[$id] = $servic[$id].'~'.$status[$id].'~'.$tservice[$id].'~'.$advanc[$id].'~'.$fine[$id];    
				$sql="";
$sql= "insert into `staff_attendance` ";
$sql.= "(`sid`,`attendance_date`,`attendance_status`,`attendance_percent`,`service_charge_current`,`advanced`,`fine`,`attendance_today_service_charge`,`ssalary`)";
$sql.= " values ";
$sql.= " ('".$id."','".$date."','".$status[$id]."','".$servic[$id]."','".$csc."','".$advanc[$id]."','".$fine[$id]."','".$tservice[$id]."','".$sal[$id]."')";
$result = mysqli_query($cn, $sql);
				$insertGoTo = "register.php";
				  if (isset($_SERVER['QUERY_STRING'])) {
					$insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
					$insertGoTo .= $_SERVER['QUERY_STRING'];
				  }
				  header(sprintf("Location: %s", $insertGoTo));
			}
		}
	}
	
	if(!empty($_POST['method'])){
		$func = $_POST['method'];
	}	
	if($func== 'registerEmployee'){
	
		$sql = "insert into `staff_reg` (`sname`,`spno`,`sdesig`,`ssalary`,`service`,`saddress`)
				values
				('".strtoupper($_POST['name'])."','".$_POST['phone']."','".strtoupper($_POST['designation'])."',".$_POST['salary'].",'".$_POST['service']."','".strtoupper($_POST['address'])."')";
				
				$result = mysqli_query($cn, $sql);
	}
	$list='';
	
	if($func=='showlist')	
	{
		$dataArr = listData();
		exit();
	}
	
	function listData($dt){
		
		$sql=/*"select
					a.sid,
					a.sname,
					a.sdesig,
					a.ssalary,
					a.service,
								
					b.attendance_status,
					b.advanced,
					b.fine,
					b.service_charge_current,
					b.attendance_percent,
					b.attendance_today_service_charge,
					b.attendance_date
					
					
					from
					
					staff_reg a,
					staff_attendance b
					
					where
					
					a.sid = b.sid
					and
					b.attendance_date = '".$dt."'" */
					"select * from staff_reg where staff_status = '1'";
				
				$result = mysqli_query($cn, $sql);
				$dataArr = array();
				while($data = mysqli_fetch_assoc($result)){
					$dataArr[] = $data;
				}
				
				return $dataArr;
	}


?>
<html>
<head>
<title>Employees Attendance</title>
<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/tab.js"></script>
<link rel="stylesheet" type="text/css" href="css/file.css" media="all"></link>
<link rel="stylesheet" type="text/css" href="css/tablestyle.css" media="all"></link>

<link rel="stylesheet" type="text/css" href="css/jquery-ui.css" media="all"></link>
<style>
.srvs{
	border:0px; 
	text-align:right; 
	color:red; 
	font-size:16px;
}
.sal_{
	border:0px; 
	text-align:right; 
	color:white; 
	font-size:16px;
}
.sum_style{
	border:0px; 
	text-align:right; 
	color:green; 
	font-size:16px;
}
.row_selected{
	background-color:yellow;
}
</style>
<script>
$(document).on('click','#logout',function(){
	window.location.href = "index.php";
});
/////////////////////// DOCUMENT READY //////////////////////////////	
	$(document).ready(function(){
            
            
		
		$("#txtdate").datepicker({dateFormat: 'dd-mm-yy'});
		$("#datepicker").val($("#txtdate").val());
                $("#txtdate").change(function(){
                
                $("#datepicker").val($("#txtdate").val());
            });
		
		<?php
					if(isset($_REQUEST['txtdate'])){
						$date = $_REQUEST['txtdate'];
						$date=date_create($date);
						$date=date_format($date,"Y-m-d");
					
						$dataArr=listData($date);
					}
					else{
						$date = date("Y-m-d");
						$date=date_create($date);
						$date=date_format($date,"Y-m-d");
						
						$dataArr=listData($date);
					}
		?>		
		$("#service_total").val(calServiceCharge());
		$("#salary_total").val(calSalary());
		$("#sum_service_total").val(calTotalSC());
		calIndividualServiceCharge();
		$("#service_charge_current").focus();
	});

//////////////////////DOCUMENT READY ENDS///////////////////////////////////////////

////////////////////// SERVICE CHARGE VALUE CHANGE /////////////////////////////////
	$(document).on('blur','#service_charge_current',function(e){
		if($(this).val()==''){
			$(this).val('0');
		}
		//if(e.which == 13)
		{

			$("#service_total").val(calServiceCharge());
			$("#salary_total").val(calSalary());
			$("#sum_service_total").val(calTotalSC());
			calIndividualServiceCharge();
		}
	});
///////////////////// SERVICE CHARGE VALUE CHANGE //////////////////////////////////

//////////////////// SAVE REGISTRATION ////////////////////////////////////
	$(document).on('click','#save',function(){
		if($("#name").val()==""){
			alert("Enter Name");
			$("#name").focus();
			return false;
		}
		if($("#phone").val()==""){
			alert("Enter Phone");
			$("#phone").focus();
			return false;
		}
		if($("#designation").val()==""){
			alert("Enter Designation");
			$("#designation").focus();
			return false;
		}
		if($("#salary").val()==""){
			alert("Enter Salary");
			$("#salary").focus();
			return false;
		}
		if($("#service").val()==""){
			alert("Enter Service Charge %age");
			$("#servie").focus();
			return false;
		}
		if($("#address").val()==""){
			alert("Enter Address");
			$("#address").focus();
			return false;
		}
		if(confirm("Want to Save Employee Information ?")){
			$.ajax({
        	url: 'register.php',
        	type: 'POST',
        	data: {method:'registerEmployee',name:$('#name').val(), phone : $('#phone').val(),designation: $('#designation').val(),salary: $('#salary').val(),service:$('#service').val(),address:$('#service').val()} ,
        	success: function (response){  
				$('#name').val('');
				$('#phone').val('');
				$('#designation').val('');
				$('#salary').val('');
				$('#service').val('');
				$('#address').val('');
				
				window.location.reload(true);
        	},
        	error: function () {
        	  
        	}
    		}); 
		}
	});

/////////////// SAVE ATTENDANCE ///////////////////////////////////////////	
	$(document).on("click","#atten",function(){
		if($("#service_charge_current").val()==""){
			alert("Enter Service Charge Amount");
			$("#service_charge_current").focus();
			return false;
		}
		if(confirm("Want to Save Attendace of date "+$("#datepicker").val()+" ? ")){
			$("#form2").submit();
		}
	});

/////////////////////// ATTENDANCE STATUS CHANGE /////////////////////////////////////	
	$(document).on("change",".status",function(){
		
		if($(this).val()=="A"){	
			 $(this).closest("td").css("background-color","red");	
			 $(this).closest("td").prev("td").find('.hidden_service').val($(this).closest("td").prev("td").find('.srvs').val());
			 $(this).closest("td").prev("td").find('.srvs').val('0');
		}
		else{
			$(this).closest("td").css("background-color","white");
			$(this).closest("td").prev("td").find('.srvs').val($(this).closest("td").prev("td").find('.hidden_service').val());
			$(this).closest("td").prev("td").find('.hidden_service').val('0');	
		}
		$("#service_total").val(calServiceCharge());
		$("#salary_total").val(calSalary());
		$("#sum_service_total").val(calTotalSC());
		calIndividualServiceCharge();
	});
	$(document).on("keypress","#service_charge_current",function(e){
		if(e.which == 13){
			alert("Go Back");
			return false;
		}
	});
	
	function calServiceCharge(){
		var total=0;
		$("#emplist td").find('.srvs').each(function(){
			total = parseInt(total) + parseInt($(this).val());
		});

		return total;
	}
	
	
	function calSalary(){
		var total=0;
		$("#emplist td").find('.sal_').each(function(){
			total = parseInt(total) + parseInt($(this).val());
		});

		return total;
	}
	
	
	function calTotalSC(){
		var total=0;
		$("#emplist td").find('.today_service_charge').each(function(){
			total = parseInt(total) + parseInt($(this).val());
		});
		return total;
	}
	
	
	function calIndividualServiceCharge(){
		$("#emplist tr").each(function(){
		var service_charge_percentage = $(this).find('td').children('input.srvs').val();
		var current_service_charge = $("#service_charge_current").val();
		var service_total = $("#service_total").val();
		
		var result = (parseInt(current_service_charge) * parseInt(service_charge_percentage)) / parseInt(service_total);
		
		$(this).find('td').children('input.today_service_charge').val(Math.round(result,0));
		
		});	
	}
	
	$(document).on('click','#hideReg',function(){
		$("#reg").slideToggle();
	});
	
	$(document).on('click',"#emplist tbody tr",function(event){
		
        $("#emplist tbody tr").removeClass('row_selected');        
        $(this).addClass('row_selected');
    });
	$(document).on('blur','.adv_amt',function(){
			if($(this).val()==''){
				$(this).val('0');
			}
	});
	
	$(document).on('blur','.fin_amt',function(){
			if($(this).val()==''){
				$(this).val('0');
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

</script>
<link href="css/styles.css" type="text/css" rel="stylesheet" />
</head>
<body>
<?php include 'include/header.php' ?>

<div class="admin-greyBg">
  <div class="admin-wrapper">
    <div id="admin-leftNav" class="admin-fLeft">
<?php include 'include/left_nav.php' ?>
    </div>
<!-- <a href="salaryCalc.php?mon=<?=date("m")?>">Detail Salary Calculation</a> -->
 <div <?=$style?>>
<?php
if(!empty($dataArr)){
?>
<div id="admin-body" class="admin-fRight">
<form name='srchform'>

<input name="txtdate" class="admin-inputBox" type="text" id="txtdate" value="<?=(isset($_REQUEST['txtdate']))? $_REQUEST['txtdate'] : Date('d-m-Y') ?>" size="9"/>
<input class="admin-button" id="fetchRecord" name="Submit" value="Fetch Records" type="submit">
</form>
		<?php
  	if($res > 0){
		$style = "style='display:none;";
		echo "<h4 align='center' style='color:red;'>Attendance for <font color='green'>'".date('d-m-Y')."'</font>  has been Taken<br>Attendance Locked</h4> ";
		exit();
	}else{
		
	}
  ?>
<form name="form2" method="POST" action="register.php">
<input type="hidden" id="datepicker" class="admin-inputBox" size="8" name="today_date">&nbsp;&nbsp;Enter Service Charge : <input onKeyPress="return numbersonly(event);" id="service_charge_current" class="admin-inputBox" name="service_charge_current" type="text" value="0"><br><br>
<table width="100%" border="0" align="center" id="emplist" class="hover cell-border">
	<thead>
	  <tr bgcolor='#fffa90'>
		<td class="admin-tbHdRow1"><strong>ID </strong></td>
		<td class="admin-tbHdRow1"><strong>Name</strong></td>
		<td class="admin-tbHdRow1" style='display:none;'><strong>Designation</strong></td>
		<td class="admin-tbHdRow1"><strong>Salary</strong></td>
		<td class="admin-tbHdRow1" style='display:none;'><strong>%age</strong></td>
		<td class="admin-tbHdRow1"><strong>Attendance</strong></td>
		<td class="admin-tbHdRow1"><strong>Advanced</strong></td>
		<td class="admin-tbHdRow1"><strong>Fine</strong></td>
		<td class="admin-tbHdRow1 admin-tbHdRow3"><strong>Today's SC</strong></td>
	  </tr>
	</thead>
	<tbody>  
  <?php 
  	foreach($dataArr as $data){
  ?>
  <tr>
    <td  class="admin-tbRow1">
		<?=$data['sid']?>
		<input type="hidden" value="<?=$data['sid']?>" name="id[<?=$data['sid']?>]">
	</td>
	<td class="admin-tbRow1"><?=$data['sname']?></td>
    <td style='display:none;' class="admin-tbRow1"><?=$data['sdesig']?></td>
	<td class="admin-tbRow1">
	<input type="text"  class="sal_" size="5" value="<?=$data['ssalary']?>" readonly="readonly" name="sal[<?=$data['sid']?>]">
	</td>
	<td style='display:none;' class="admin-tbRow1">
	<input type="text" class="srvs" size="1" value="<?=$data['service']?>" readonly="readonly" name="srvs[<?=$data['sid']?>]">
	<input type="hidden" class="hidden_service" value="0">
	</td>
	<td class="admin-tbRow1">
	<select class="status combostyle" name='status[<?=$data['sid']?>]'>
		<option value="P">PRESENT</option>
	<!--	<option value="L">LEAVE</option> -->
		<option value="A">ABSENT</option>
	</select></td>
	<td  class="admin-tbRow1">
		<input type="text" class="admin-inputBox adv_amt" name="advanced[<?=$data['sid']?>]" size="5" value="0" onKeyPress="return numbersonly(event);">
	</td>
	<td class="admin-tbRow1">
		<input type="text" class="admin-inputBox fin_amt" name="fine[<?=$data['sid']?>]" size="5" value="0" onKeyPress="return numbersonly(event);">
	</td>
	<td class="admin-tbRow1 admin-tbRow2">
		<input type="text" class="today_service_charge sum_style" value="0" readonly="readonly" name="today_service_charge[<?=$data['sid']?>]" size="5">
	</td>
  </tr>
  <?php
  }
  ?>
  </tbody>
  <tfoot>
  <tr bgcolor='#fffa90'>
		<td></td>
		<td></td>
		<td><input type="text" size="6" id="salary_total" class="sum_style" readonly="readonly"></td>
		<td></td>
		<td>Total %age SC <input type="text" size="5" id="service_total" class="sum_style" readonly="readonly"></td>
		<td></td>
		<td><input type="hidden" size="5" id="sum_service_total" class="sum_style"></td>
	  </tr>
  <tr>
  <th colspan="7" align="center">
  	<input type="submit" class="admin-button" value="Save Attendance" <?=$style?>>
	<input type="hidden" name="saveAttendance" value="yes">
  </th>
  </tr>
  </tfoot>
</table>
</form>
<?php
}
?>
</div>
</body>
</html>
