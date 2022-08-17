<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include('lib/iq.php');
	$q = "SELECT * FROM STAFF_REG";
	$rsFD = mysqli_query($cn, $q) or die(mysqli_error($cn));
	$rowrsFD = mysqli_fetch_assoc($rsFD);

if(isset($_POST['method'])){
	if($_POST['method']=='changestatus'){
		$userid = $_POST['userid'];
		$status = $_POST['status'];
		if($status == 1){
				$updateQry = "update staff_reg set staff_status = '0' where sid = '$userid'";
		}
		else if($status == 0){
				$updateQry = "update staff_reg set staff_status = '1' where sid = '$userid'";
		}
		echo $updateQry;
		mysqli_query($cn, $updateQry);		
		exit();
	}
}	
	
?>
<html>
<head>

<title>Employee Detail List</title>
<link href="css/styles.css" type="text/css" rel="stylesheet" />
<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/jquery.datepick.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.datepick.css">
<link rel="stylesheet" type="text/css" href="css/jquery.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">

</script>
<style>
.table-hover thead tr:hover th, 
 .table-hover tbody tr:hover td {
        background-color: lightyellow;
}
</style>

<script>
$(document).on('change','#active-deactive',function(){
	var status = $(this).attr('status');
	var userid = $(this).attr('userid');
		$.ajax({
        type:'POST', 
        url: '<?=$_SERVER['PHP_SELF']?>', 
        data:{method:'changestatus',status:status,userid:userid}, 
        success: function(response) {
			location.reload();
        }
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
    <div id="admin-body" class="admin-fRight">
		<h1>Employees List </h1>
     <div id="msg" align="center"><?php if(isset($_GET['msg'])) { echo $_GET['msg']; } ?></div>
	<table width="100%" border="0" align="center" class="table-hover">
	<thead>
   <tr bgcolor="#F0F0F0">
   	<td class="admin-tbHdRow1">Sno.</td>
     <td class="admin-tbHdRow1">Name</td>
     <td class="admin-tbHdRow1">Desig</td>
     <td class="admin-tbHdRow1">Salary</td>
     <td class="admin-tbHdRow1">%age</td>
	 <td class="admin-tbHdRow1">Phone #</td>
	 <td class="admin-tbHdRow1">Address</td>
         <td class="admin-tbHdRow1">Status</td>
     <td class="admin-tbHdRow1 admin-tbHdRow3">&nbsp;</td>
   </tr>
   </thead>
   <tbody>
   <?php 
    do { 
    ?>
     <tr>
	   <td class="admin-tbRow1"><?php echo $rowrsFD['sid']?></td>
       <td class="admin-tbRow1">
	   	<a href="profile.php?id=<?=$rowrsFD['sid'];?>" target="_blank">
	   			<?php echo $rowrsFD['sname'] ?>
		</a>
	   </td>
       <td class="admin-tbRow1"><?php echo $rowrsFD['sdesig']; ?></td>
       <td class="admin-tbRow1"><font color='white'><?php echo $rowrsFD['ssalary']; ?></font></td>
       <td class="admin-tbRow1"><font color='white'><?php echo $rowrsFD['service']; ?></font></td>
       <td class="admin-tbRow1"><?php echo $rowrsFD['spno']; ?></td>
       <td class="admin-tbRow1"><?php echo $rowrsFD['saddress']; ?></td>
       <td class="admin-tbRow1 admin-tbRow2">
		<select class="admin-button" status = <?=$rowrsFD['staff_status']?>  userid = <?=$rowrsFD['sid']?> id='active-deactive'>
			<option value="1" <?php if($rowrsFD['staff_status']=="1"){ echo "selected";} ?>> Active </option>
			<option value="0" <?php if($rowrsFD['staff_status']=="0"){ echo "selected";} ?>> In-Active </option>
		</select>
	   </td>
           <td class="admin-tbRow1 admin-tbRow3">
                        
<a href="create_user.php?id=<?=$rowrsFD['sid']?>" title="Edit" class="admin-edit" onClick="return confirm('Are you sure you want to perform this action ?');"></a>
            </td>

     </tr>
     <?php } while ($rowrsFD = mysqli_fetch_assoc($rsFD)); ?>
	 </tbody>
 </table>

    </div>
    <br clear="all" />
  </div>
</div>
<?php include 'include/footer.php' ?>
 
</body>
</html>
