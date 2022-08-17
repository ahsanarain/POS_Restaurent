<?php
//Session started and database connection  included here...
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include("lib/iq.php");
$dealArray = array('18','19');
if(isset($_REQUEST['txtdate']))
if(!empty($_REQUEST['txtdate'])){
	$string = explode('-',str_replace(' ', '', $_REQUEST['txtdate']));
	$from = $string[0];
	$to = $string[1];
	
	$from = str_replace('/', '-', $from);
	$to = str_replace('/','-',$to);
	
	$fromto = "Detail Stock Summary Report from <font size='4' color='green'>'".$from."'</font> to <font size='4' color='green'>'".$to."'</font>";
	
	$from = $from;
	$from = date("Y-m-d", strtotime($from));
	
	$to = $to;
	$to = date("Y-m-d", strtotime($to));

	$sql = "select * from stock_detail where dat between '".$from."' and '".$to."' ORDER BY stock_detail_id desc";
        $result = mysqli_query($cn, $sql);
	$dataArr = array();
	while($data = mysqli_fetch_assoc($result)){
		$dataArr[] = $data;
	}
}




?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Stock Detail Report</title>
<link href="css/styles.css" type="text/css" rel="stylesheet" />
<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/jquery.dataTables.js"></script>   

<script src="js/jquery.datepick.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.datepick.css">
<link rel="stylesheet" type="text/css" href="css/jquery.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css"> 
<script>
$(document).ready(function(){
	$("#txtdate").datepick({
                    rangeSelect: true,
                    dateFormat: 'dd/mm/yyyy',
                    monthsToShow: 2,
                    maxDate: 1,
                    
                });
        
       $('#stocktable').DataTable();

    
	
});
</script>
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
  <div id="msg" align="center">
	<?php if(!empty($_POST['txtdate'])){?>
            <?=$fromto?>
        <?php }?>
  </div>
  <div class="admin-wrapper">
    <div id="admin-leftNav" class="admin-fLeft">
<?php include 'include/left_nav.php' ?>
    </div>
    <div id="admin-body" class="admin-fRight">
	<h1>Detail Stock Summary Report</h1>
	<form action="stock_detail_view.php" method="POST">
            <input name="txtdate" type="text" autocomplete="off" id="txtdate" size="25" class="admin-inputBox">
            <input type="submit" value="Filter Now" class="admin-button">
        </form>
        <br>
 <?php
  if(isset($dataArr)){
 ?>
    <table width="100%" border="0" class="display" align="center" id='stocktable'>
        <thead>
            <tr>
                <td class="admin-tbHdRow1" width="5%">Sno</td>
                <td class="admin-tbHdRow1" align="left">Date</td>
				<td class="admin-tbHdRow1" align="left">Item Name</td>
				<td class="admin-tbHdRow1" align="left">Unit</td>
				<td class="admin-tbHdRow1" align="left">Quantity In</td>
                <td class="admin-tbHdRow1 admin-tbHdRow3">Details</td>
				<td width="5%" class="admin-tbHdRow1 admin-tbHdRow3" align="center">Delete</td>
            </tr>
        </thead>
        <tbody>
            <?php $srno=0; foreach($dataArr as $data){ $srno++;?>
				<tr>
					<td class="admin-tbRow1"><?=$srno?></td>
					<td class="admin-tbRow1"><?=date("d-m-Y", strtotime($data['dat']));?></td>
					<td class="admin-tbRow1"><?=strtoupper($data['item_name'])?></td>
					<td class="admin-tbRow1"><?=$data['unit']?></td>
					<td class="admin-tbRow1"><?=$data['qty_in']?></td>
                    <td class="admin-tbRow1 admin-tbRow3"><?=strtoupper($data['details'])?></td>
					<td class="admin-tbRow1 admin-tbRow2"  valign="top"><a href="update_stock.php?stock_detail_id=<?=$data['stock_detail_id']?>&sub_item_ing_id=<?=$data['sub_item_ing_id']?>&date=<?=$_REQUEST['txtdate']?>" title="Delete" class="admin-del" onclick="return confirm('Are you sure you want to perform this action ?');"></a>
					</td>
				</tr>
			<?php }?>
        </tbody>
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
</html>