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
<title><?=$org_name?></title>
<script src="js/jquery.js"></script>
<link href="css/styles.css" type="text/css" rel="stylesheet" />

<style>
.table-hover thead tr:hover th, 
 .table-hover tbody tr:hover td {
        background-color: lightyellow;
}
</style>
<script>
$(document).on("blur",".price",function(){
    var price = $(this).val();
    var sub_item_id = $(this).attr('sub_item_id');
    var chkbox = $(this).parent().parent().children().first().children();
    chkbox.val(sub_item_id+'~'+price);
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
		<h1>Sub Menu Items Update Prices</h1>

        <form name="form1" method="post" action="sub_items_update_all.php">
	    <table border="0" width="100%" cellspacing="0" cellpadding="0" class='table-hover'>
			<thead>
          <tr>
              <td class="admin-tbHdRow1">&nbsp;</td>
            <td class="admin-tbHdRow1">Sno</td>
            
            <td width="73%" class="admin-tbHdRow1">Name</td>
			<td width="73%" class="admin-tbHdRow1">Price</td>
            <td width="10%" class="admin-tbHdRow1" align="center">Status</td>
			<td width="73%" class="admin-tbHdRow1">Image</td>		
           
          </tr>
		  </thead>	 
		 <tbody>
		 <?php
                       
		 	foreach($arrMainData as $key => $md){
			$sno=0;
		 ?>
		 	<tr>
				<td colspan="6" class="admin-tbHdRow1 admin-tbHdRow3"><?=$key?></td>
			</tr>
				<?php foreach($md as $m){ $sno++;?>
				<tr>
                                        <td class="admin-tbRow1">
                                            <input type="checkbox" name="chk[]" class="sel_price" value="<?=$m['price']?>">
                                          
                                        </td>
					<td class="admin-tbRow1"><?=$sno?></td>
                                        
					<td class="admin-tbRow1" align='left' valign='top'>
					<?=$m['sub_item_name']?>
					<br>
                  
					</td>
					<td class="admin-tbRow1">
                                        <input type="text" class="price admin-inputBox" value="<?=$m['price']?>" class="admin-inputBox" size="40"  sub_item_id = <?=$m['sub_item_id']?>> 
                                        </td>
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
					<td class="admin-tbRow2"><a href="images/subitems/<?php echo $m['sub_item_image']; ?>"><img width="40" src="images/subitems/<?php echo $m['sub_item_image']; ?>"></a></td>
					
				</tr>	
				<?php }?>
		<?php }?>
                     <tr>
                         <td colspan="6">
                             &nbsp;
                         </td>
                     </tr>
                     <tr>
                         <td colspan="6" align="center">
                         <input type="submit" value="Update Now" class="admin-button" style="margin-bottom:10px;">
                             &nbsp; &nbsp;
                         <input type="reset" value="Clear" class="admin-button" style="margin-bottom:10px;">
                          </td>
                     </tr>
          </tbody>
            </table>
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
