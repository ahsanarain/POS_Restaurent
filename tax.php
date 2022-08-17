<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');

$query_rsMgtList = "SELECT * FROM tax_tab ORDER BY tax_id ASC";
$rsMgtList = mysqli_query($cn, $query_rsMgtList) or die(mysqli_error($cn));
$row_rsMgtList = mysqli_fetch_assoc($rsMgtList);
$totalRows_rsMgtList = mysqli_num_rows($rsMgtList);

$file = ucwords(strtolower(str_replace ("_"," ",basename($_SERVER["SCRIPT_FILENAME"], '.php'))));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$tax?></title>
<script src="js/jquery.js"></script>
<link href="css/styles.css" type="text/css" rel="stylesheet" />
<style>
.table-hover thead tr:hover th, 
 .table-hover tbody tr:hover td {
        background-color: lightyellow;
}
</style>

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
		<h1>Tax List</h1>
               <form action="add_tax.php" method="post">
        	<input type="submit" class="admin-button" value="Add Tax" style="margin-bottom:10px;" />
      </form>
        
	    <input name="rid" type="hidden" id="rid" />
	    <table border="0" width="100%" cellspacing="0" cellpadding="0" class='table-hover'>
          <thead>
		  <tr>
            <td width="40%" class="admin-tbHdRow1">Tax Description</td>
            <td width="12%" class="admin-tbHdRow1" align="center">Tax1</td>
	        <td width="12%" class="admin-tbHdRow1">Tax2</td>
	        <td width="12%" class="admin-tbHdRow1">Tax3</td>		
            <td width="14%" class="admin-tbHdRow1">Activation Date</td>		
            <td width="15%" class="admin-tbHdRow1">Status</td>		
            <td width="10%" class="admin-tbHdRow1" align="center">Edit</td>
            <td width="7%" class="admin-tbHdRow1 admin-tbHdRow3" align="center">Delete</td>
          </tr>
		  </thead>
		  	 
		 <tbody>
          <?php do { ?>
            <tr>
              <td class="admin-tbRow1" valign="top"><?php echo $row_rsMgtList['description']; ?></td>
              <td class="admin-tbRow1" valign="top" align="right"><?php echo $row_rsMgtList['tax1']; ?></td>
              <td class="admin-tbRow1" valign="top" align="right"><?php echo $row_rsMgtList['tax2']; ?></td>
              <td class="admin-tbRow1" valign="top" align="right"><?php echo $row_rsMgtList['tax3']; ?></td>
              <td class="admin-tbRow1" valign="top"><?php echo Date("d-m-Y",strtotime($row_rsMgtList['activation_date'])); ?></td>
			  <td class="admin-tbRow1" align="center" valign="top">
			  <?php
			  	if($row_rsMgtList['status']==1)
				{
					echo "Active";
				}
				else
				{
				  	echo "In-Active";
				}
				?>			</td>
			
              <td class="admin-tbRow1" align="center" valign="top"><a href="edit_tax.php?id=<?PHP echo $row_rsMgtList['tax_id']; ?>" title="Edit" class="admin-edit"></a></td>
              <td class="admin-tbRow1 admin-tbRow2" align="center" valign="top"><a href="#" title="Delete" class="admin-del" onclick="return confirm('Contact Administrator.');"></a></td>
             <!-- <td class="admin-tbRow1 admin-tbRow2" align="center" valign="top"><a href="delete_tax.php?id=<?PHP echo $row_rsMgtList['tax_id']; ?>" title="Delete" class="admin-del" onclick="return confirm('Are you sure you want to perform this action ?');"></a></td> -->
            </tr>
            <?php } while ($row_rsMgtList = mysqli_fetch_assoc($rsMgtList)); ?>
		 </tbody>
		 </table>
	   </form>
    </div>
    <br clear="all" />
  </div>
</div>
<?php include 'include/footer.php' ?>

</body>
</html>
<?php
mysqli_free_result($rsMgtList);
?>
