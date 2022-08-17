<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include("lib/iq.php");
//$query_rsMgtList = "SELECT * FROM sub_items ORDER BY item_id ASC";
$query_rsMgtList = "select * from items a, sub_items b WHERE a.item_id = b.item_id ";
$rsMgtList = mysqli_query($cn, $query_rsMgtList) or die(mysqli_error($cn));
$arrMainData = array();
		while($data=mysqli_fetch_assoc($rsMgtList)){
			$arrMainData[$data['item_name']][] = $data;
		}

//$totalRows_rsMgtList = mysqli_num_rows($rsMgtList);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sub Menu Items</title>
<script src="js/jquery.js"></script>
<link href="css/styles.css" type="text/css" rel="stylesheet" />

<style>
.table-hover thead tr:hover th, 
 .table-hover tbody tr:hover td {
        background-color: lightyellow;
}
.style1 {color: #009900}
</style>
</head>

<body>
<?php include 'include/header.php' ?>
<div class="admin-greyBg">
  <div class="admin-wrapper">
	<div align="center" class="style1" id="msg"><?php if(isset($_GET['msg'])) { echo $_GET['msg']; } ?></div>
    <div id="admin-leftNav" class="admin-fLeft">
<?php include 'include/left_nav.php' ?>
    </div>
    <div id="admin-body" class="admin-fRight">
		<h1>Sub Menu Items</h1>
		<table border="0" width="100%">
		<tr>
		<td>
		<form action="add_sub_items.php" method="post">
        	<input type="submit" class="admin-button" value="Add Sub Item" style="margin-bottom:10px;" />
	    </form>
		</td>
		<td align="right">
	<form action="sub_items_update.php" method="post">
      <input type="submit" class="admin-button" value="Update All Prices" style="margin-bottom:10px;" />
	</form>
		</td>
		</tr>
		</table>
        
		
		<form name="form1" method="post" action="news_move.php">
	    <input name="rid" type="hidden" id="rid" />
	    <table border="0" width="100%" cellspacing="0" cellpadding="0" class='table-hover'>
			<thead>
          <tr>
            <td class="admin-tbHdRow1">Sno</td>
            <td width="73%" class="admin-tbHdRow1">Name</td>
			<td width="73%" class="admin-tbHdRow1">Price</td>
            <td width="10%" class="admin-tbHdRow1" align="center">Status</td>
			<td width="73%" class="admin-tbHdRow1">Image</td>		
            <td width="10%" class="admin-tbHdRow1" align="center">Edit</td>
            <td width="7%" class="admin-tbHdRow1 admin-tbHdRow3" align="center">Delete</td>
          </tr>
		  </thead>	 
		 <tbody>
		 <?php
		 	foreach($arrMainData as $key => $md){
			$sno=0;
		 ?>
		 	<tr>
				<td colspan="7" class="admin-tbHdRow1 admin-tbHdRow3"><?=$key?></td>
			</tr>
				<?php foreach($md as $m){ $sno++;?>
				<tr>
					<td class="admin-tbRow1"><?=$sno?></td>
					<td class="admin-tbRow1" align='left' valign='top'>
					<?=$m['sub_item_name']?>
					<br>
                  <?php
                        $sql = "select item,amt,unit from sub_item_ing where sub_item_id = '".$m['sub_item_id']."'";
                        $result = mysqli_query($cn, $sql);
                        $dataArr = array();
                        while($data = mysqli_fetch_assoc($result)){
                                $dataArr[] = $data;
                        }
                        $str = "";
          
                        foreach($dataArr as $data){
                            $str .= "<font size='1'>".$data['item'].' '.$data['amt'].' '.$data['unit']."<br></font>";
                        
                        }
                        echo $str;
                  ?>
					</td>
					<td class="admin-tbRow1"`><?=$m['price']?></td>
					<td class="admin-tbRow1">
					<?php
			  	if($m['sub_item_status']==1)
				{
					echo "Publish";
				}
				else
				{
				  	echo "Un-Publish";
				}
				?>					</td>
					<td class="admin-tbRow1"><a href="images/subitems/<?php echo $m['sub_item_image']; ?>"><img width="40" src="images/subitems/<?php echo $m['sub_item_image']; ?>"></a></td>
					<td class="admin-tbRow1" align="center" valign="top"><a href="edit_sub_items.php?id=<?PHP echo $m['sub_item_id']; ?>" title="Edit" class="admin-edit"></a></td>
              <td class="admin-tbRow1 admin-tbRow2" align="center" valign="top">
			 
			  <a href="delete_sub_items.php?id=<?PHP echo $m['sub_item_id']; ?>" title="Delete" class="admin-del" onclick="return confirm('Are you sure you want to perform this action ?');"></a>
	 			  </td>
				</tr>	
				<?php }?>
		<?php }?>
          </tbody>
	   </form>
    </div>
<br /><br />
    <br clear="all" />
  </div>
</div>

<?php include 'include/footer.php' ?>

</body>
</html>
<?php
mysqli_free_result($rsMgtList);
?>
