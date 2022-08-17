<?php
	include("Connections/cn.php");
	include("lib/iq.php");				
		
	$sql = "select * from staff_reg where sid = '".$_GET['id']."'";

	$result = mysqli_query($cn, $sql);
	$dataArr = array();
	$data = mysqli_fetch_assoc($result);
?>

	<style type="text/css">
  table{
    border-collapse: collapse;
    border: 1px solid black;
  }
  table td{
    border: 1px solid #CCCCCC;
  }

    </style>
<title>
<?=$data['sname']."'s"?> Profile
</title>
<link href="css/styles.css" type="text/css" rel="stylesheet" />
<body>
<?php include 'include/header.php' ?>

<div class="admin-greyBg">
  <div class="admin-wrapper">
    <div id="admin-leftNav" class="admin-fLeft">
<?php include 'include/left_nav.php' ?>
    </div>
    <div id="admin-body" class="admin-fRight">
	<h1>
		<?=$data['sname']."'s"?> Profile	
	</h1>
<table width="500" border="0" cellpadding="2" cellspacing="2">
  <tr>
    <td width="148" rowspan="5" align="center" valign="top">
	<img src="images/staff_images/<?=$data['staff_image']?>" width="121" height="131">	</td>
    <td height="32" colspan="2"><h2><?=$org_name?></h2></td>
  </tr>
  <tr>
  	<td width="82"><strong>ID</strong></td>
    <td width="248"><?=$data['sid']?></td>
  </tr>
  <tr>
    <td width="82"><strong>Name</strong></td>
    <td width="248"><?=$data['sname']?></td>
  </tr>
  <tr>
    <td><strong>Designation</strong></td>
    <td><?=$data['sdesig']?></td>
  </tr>
  <tr>
    <td><strong>Address</strong></td>
    <td><?=$data['saddress']?></td>
  </tr>
  
  <tr>
    <td>&nbsp;</td>
    <td><strong>Phone # </strong></td>
    <td><?=$data['spno']?></td>
  </tr>
  <tr>
    <td colspan="3">&nbsp;</td>
  </tr>
</table><br />
<table width="500" border="0" cellpadding="2" cellspacing="2">
	<tr>
		<td align="center" valign="top">
			<img src="images/staff_cnic/<?=$data['staff_cnic']?>" width="480">
		</td>
	</tr>
</table>
    </div>
    <br clear="all" />
  </div>
</div>
<?php include 'include/footer.php' ?>
</body>