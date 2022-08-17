<?php
//Session started and database connection  included here...
session_start();

include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include('lib/iq.php');

$query_rsMgtList = "SELECT * FROM hard_inventory ORDER BY inventory_id desc";
$rsMgtList = mysqli_query($cn, $query_rsMgtList) or die(mysqli_error($cn));
$row_rsMgtList = mysqli_fetch_assoc($rsMgtList);
$totalRows_rsMgtList = mysqli_num_rows($rsMgtList);
$file = ucwords(strtolower(str_replace ("_"," ",basename($_SERVER["SCRIPT_FILENAME"], '.php'))));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$file?></title>
<link href="css/styles.css" type="text/css" rel="stylesheet" />
<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/jquery.dataTables.js"></script>   

<script src="js/jquery.datepick.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.datepick.css">
<link rel="stylesheet" type="text/css" href="css/jquery.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css"> 


<style>
.table-hover thead tr:hover th, 
 .table-hover tbody tr:hover td {
        background-color: lightyellow;
}
</style>
<link href="css/styles.css" type="text/css" rel="stylesheet" />
<script>
$(document).ready(function(){
    $('#inventorytable').DataTable(); 
});
</script>
</head>
<body>
<?php include 'include/header.php' ?>
<div class="admin-greyBg">
  <div class="admin-wrapper">
  <div id="msg" align="center"><?php if(isset($_GET['msg'])) { echo $_GET['msg']; } ?></div>
    <div id="admin-leftNav" class="admin-fLeft">
<?php include 'include/left_nav.php' ?>
    </div>
    <div id="admin-body" class="admin-fRight">
       <h1>Hard Inventory</h1>
	   <form action="add_inventory.php" method="post">
        	<input type="submit" class="admin-button" value="Add Inventory" style="margin-bottom:10px;" />
	  </form>
	  <table border="0" width="100%" cellspacing="0" cellpadding="0" class='table-hover' id="inventorytable">
          <thead>
		  <tr>
            <td width="1%" class="admin-tbHdRow1">Srno.</td>
            <td width="15%" class="admin-tbHdRow1" align="center">Item Name</td>
            <td width="15%" class="admin-tbHdRow1">Description</td>
			<td width="3%" class="admin-tbHdRow1">Qty</td>		
            <td width="1%" class="admin-tbHdRow1" align="center">Edit</td>
            <td width="1%" class="admin-tbHdRow1 admin-tbHdRow3" align="center">Delete</td>
          </tr>
		  </thead>
		  	 
		 <tbody>
		 	<?php  $srno=0; do{  $srno++; ?>
			 <tr>
			 <td class="admin-tbRow1" valign="top"><?=$srno?></td>
              <td class="admin-tbRow1" valign="top"><?php echo $row_rsMgtList['inventory_name']; ?></td>
              <td class="admin-tbRow1" align="center" valign="top"><?php echo $row_rsMgtList['inventory_desc']; ?></td>
			  <td class="admin-tbRow1" align="center" valign="top"><?php echo $row_rsMgtList['inventory_qty']; ?></td>
			   <td class="admin-tbRow1" align="center" valign="top"><a href="edit_hard_inventory.php?id=<?PHP echo $row_rsMgtList['inventory_id']; ?>" title="Edit" class="admin-edit"></a></td>
              <td class="admin-tbRow1 admin-tbRow2" align="center" valign="top"><a href="del_hard_inventory.php?id=<?PHP echo $row_rsMgtList['inventory_id']; ?>" title="Delete" class="admin-del" onclick="return confirm('Are you sure you want to perform this action ?');"></a></td>
            </tr>
		   <?php } while ($row_rsMgtList = mysqli_fetch_assoc($rsMgtList)); ?>
		 </tbody>
	</table>
    <br clear="all" />
  </div>
</div>
<?php include 'include/footer.php' ?>
</body>
</html>
