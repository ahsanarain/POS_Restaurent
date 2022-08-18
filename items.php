<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');

$query_rsMgtList = "SELECT * FROM items ORDER BY item_id ASC";
$rsMgtList = mysqli_query($cn, $query_rsMgtList) or die(mysqli_error($cn));
$row_rsMgtList = mysqli_fetch_assoc($rsMgtList);
$totalRows_rsMgtList = mysqli_num_rows($rsMgtList);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Main Menu Items</title>
<script src="js/jquery.js"></script>
<link href="css/styles.css" type="text/css" rel="stylesheet" />
    <script src="js/sweetalert2.all.min.js"></script>
<style>
.table-hover thead tr:hover th, 
 .table-hover tbody tr:hover td {
        background-color: lightyellow;
}
</style>
<script type="text/javascript" language="javascript">
	function setValue(id)
	{
		document.getElementById('rid').value = id;
		document.getElementById('mov').value = 'up';
		document.form1.submit();
	}
	function setValueMove(id)
	{
		document.getElementById('rid').value = id;
		document.getElementById('mov').value = 'down';
		document.form1.submit();
	}
</script>
</head>

<body>
<?php include 'include/header.php' ?>
<div class="admin-greyBg">
  <div class="admin-wrapper">
	<div id="msg" align="center">
        <?php
        if(isset($_GET['msg']))
        {
            echo '
            <script>
            Swal.fire({
              icon: "success",
              title: "OK",
              text: "'.$_GET['msg'].'",
            });
                window.history.pushState("object or string", "Title", window.location.href.split("?")[0]);
            </script>';
        }
        ?></div>
    <div id="admin-leftNav" class="admin-fLeft">
<?php include 'include/left_nav.php' ?>
    </div>
    <div id="admin-body" class="admin-fRight">
		<h1>List of Main Menu Items</h1>
        <form action="add_items.php" method="post">
        	<input type="submit" class="admin-button" value="Add Item" style="margin-bottom:10px;" />
	  </form>

		<form name="form1" method="post" action="news_move.php">
	    <input name="rid" type="hidden" id="rid" />
	    <table border="0" width="100%" cellspacing="0" cellpadding="0" class='table-hover'>
          <thead>
		  <tr>
            <td width="73%" class="admin-tbHdRow1">Name</td>
            <td width="10%" class="admin-tbHdRow1" align="center">Status</td>
			<td width="73%" class="admin-tbHdRow1">Image</td>		
            <td width="10%" class="admin-tbHdRow1" align="center">Edit</td>
            <td width="7%" class="admin-tbHdRow1 admin-tbHdRow3" align="center">Delete</td>
          </tr>
		  </thead>
		  	 
		 <tbody>
          <?php do { ?>
            <tr>
              <td class="admin-tbRow1" valign="top"><?php echo $row_rsMgtList['item_name']; ?></td>
			  <td class="admin-tbRow1" align="center" valign="top">
			  <?php
			  	if($row_rsMgtList['item_status']==1)
				{
					echo "Publish";
				}
				else
				{
				  	echo "Un-Publish";
				}
				?>
				</td>
				<td class="admin-tbRow1" valign="top"><a href="images/items/<?php echo $row_rsMgtList['item_image']; ?>"><img width="40" src="images/items/<?php echo $row_rsMgtList['item_image']; ?>"></a></td>
              <td class="admin-tbRow1" align="center" valign="top"><a href="edit_items.php?id=<?PHP echo $row_rsMgtList['item_id']; ?>" title="Edit" class="admin-edit"></a></td>
              <td class="admin-tbRow1 admin-tbRow2" align="center" valign="top"><a href="delete_items.php?id=<?PHP echo $row_rsMgtList['item_id']; ?>" title="Delete" class="admin-del" onclick="return confirm('Are you sure you want to perform this action ?');"></a></td>
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
