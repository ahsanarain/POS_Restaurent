<?php
	include("Connections/cn.php");
	include("lib/iq.php");
	$func="";
if(!empty($_POST['method'])){
		$func = $_POST['method'];
	}	
	if($func== 'registerEmployee'){
	
		$sql = "insert into `staff_reg` (`sname`,`spno`,`sdesig`,`ssalary`,`service`,`saddress`)
				values
				('".strtoupper($_POST['name'])."','".$_POST['phone']."','".strtoupper($_POST['designation'])."',".$_POST['salary'].",'".$_POST['service']."','".strtoupper($_POST['address'])."')";
				
				echo $sql;
				
				$result = mysqli_query($cn, $sql);
	}
	$list='';
	
	if($func=='showlist')	
	{
		$dataArr = listData();
		exit();
	}
	?>
<head>
<script src="js/jquery.js"></script>
<script>
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
        	url: 'registeremp.php',
        	type: 'POST',
        	data: {method:'registerEmployee',name:$('#name').val(), phone : $('#phone').val(),designation: $('#designation').val(),salary: $('#salary').val(),service:$('#service').val(),address:$('#service').val()} ,
        	success: function (response){  
				$('#name').val('');
				$('#phone').val('');
				$('#designation').val('');
				$('#salary').val('');
				$('#service').val('');
				$('#address').val('');
				
				$("#msgdiv").html("Record Saved");
        	},
        	error: function () {
        	  
        	}
    		}); 
		}
	});

</script>
</head>
<div id="reg">
<h1> Employee Registration </h1>
  <table width="1200" border="1" align="center">
    <tr align="left" valign="middle">
      <td width="98">Name</td>
      <td width="147"><label>
        <input name="name" type="text" id="name" />
      </label></td>
      <td width="52">Phone</td>
      <td width="90"><input name="phone" type="text" id="phone" size="10" value="0" /></td>
      <td width="77">Desig</td>
      <td width="115"><input name="designation" type="text" id="designation" size="15" value="nill"/></td>
	</tr>
	<tr>
      <td width="80">Salary</td>
      <td width="95"><input name="salary" type="text" id="salary" size="10" /></td>
      <td width="119"> Service Charge %age </td>
	  <td><input name="service" type="text" id="service" size="10" />
	  </td>
	  <td width="88">Address</td>
      <td width="164"><textarea name="address" id="address">THAMES BURGER</textarea></td>
    </tr>
	<tr>
		<td colspan="6" align="right"> 
			<input type="button" name="save" id="save" value="Save">
			<input type="reset" name="Submit2" value="Clear">	
		</td>
	</tr>
  </table>
  </div>
  <div id="msgdiv">
  
  </div>