<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include('lib/iq.php');

$dealArray = array('18','19');
       

	$mgtId = $_GET['id'];
     
	$mgtQry = mysqli_query($cn, "select * from sub_items where sub_item_id = '$mgtId'");
        
        $sel = mysqli_query($cn, "select item_id from sub_items where sub_item_id = '$mgtId'");
        $selID = mysqli_fetch_array($sel);
        $selID = $selID['item_id'];
        
        
        $query_rsauto = "SELECT upper(keyword_name) as exp_desc FROM keywords_ing where status = '1'";
        $row_rsauto = mysqli_query($cn, $query_rsauto) or die(mysqli_error($cn));
        $arrAuto=array();
        while($data= mysqli_fetch_assoc($row_rsauto)){
                $arrAuto[] = $data['exp_desc'];
        }
        
        
        
	while($mgtRow=mysqli_fetch_array($mgtQry))
	{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$org_name?></title>
<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<link href="css/jquery-ui.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="css/jquery.css">

<script language="JavaScript" type="text/javascript" src="js/tiny_mce.js"></script>
<script language="JavaScript" type="text/javascript" src="js/custom_config.js"></script>
<link href="css/styles.css" type="text/css" rel="stylesheet" />	

<script>
function validateQty(event) {
    var key = window.event ? event.keyCode : event.which;
if (event.keyCode == 8 || event.keyCode == 46
 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 9) {
    return true;
}
else if ( key >= 65 && key <=90 || key>=97 && key <=122 || key == 32 || key == 11) {
    return true;
}
else return false;
};

function numbersonly(e){
    var unicode=e.charCode? e.charCode : e.keyCode
    if (unicode!=8)
	{ 
        if (unicode>=48 && unicode <=57 || unicode == 40 || unicode == 41 || unicode == 43 || unicode == 32 || unicode == 11 || unicode == 9 ) 
            return true 
		else
			return false
    }
}

$(document).on('click','.add',function(){
   $('.details tbody>tr:last').clone(true).insertAfter('.details tbody>tr:last');
   $('.details tbody>tr:last').children().find('.itm').val('');
   $('.details tbody>tr:last').children().find('.amt').val('');
});
$(document).on('click','.minus',function(){
   var row = $(this).parent().parent().index();
	if(row != 0){
            $(this).parent().parent().remove();
	} 
});

$(document).on('click','#save_ing',function(){
    var chk=0;
    $(".itm").each(function(){
        if($(this).val()==''){
            chk=1;
            return false;
        }
   });
   if(chk==1){
       alert("Can't Store Null Value to the database.");
       return false;
   }else{
    $("#frmdetail").submit();
   }
   
});


$(document).on('click','#save_deal',function(){
    var chk=0;
        $(".qty").each(function(){
       if($(this).val()==''){
           chk=1;
           return false;
       }
   }) ;
   if(chk==1){
       alert("Can't Store Null Value to the database.");
       return false;
   }else{
    $("#frmdeal").submit();
   }
   
});

$(document).on('click','.addd',function(){
   $('.deal tbody>tr:last').clone(true).insertAfter('.deal tbody>tr:last');
   $('.deal tbody>tr:last').children().find('.qty').val('');
});
$(document).on('click','.minusd',function(){
   var row = $(this).parent().parent().index();
	if(row != 0){
            $(this).parent().parent().remove();
	} 
});
</script>

</head>
<body>
<?php include 'include/header.php' ?>
<div id="msg" align="center"><?php if(isset($_GET['msg'])) { echo $_GET['msg']; } ?></div>
<div class="admin-greyBg">
  <div class="admin-wrapper">
    <div id="admin-leftNav" class="admin-fLeft">
<?php include 'include/left_nav.php' ?>
    </div>
    <div id="admin-body" class="admin-fRight">
		<h1>Edit Sub Menu Item </h1>
                <table border="0" width="100%" align="center">
                    <tr>
                        <td align="left" valign="top">
                            <table width="100%" border="0">
                                <tr><td align="left" valign="top">
                            <form action="edit_sub_items_confirm.php?id=<?php echo $_GET['id']; ?>" method="post" enctype="multipart/form-data" style="margin:0; padding:0;">
        	<table border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td><strong>Name:</strong></td>
                <td><input type="text" name="name" id="date" class="admin-inputBox" size="40" value="<?php echo $mgtRow['sub_item_name']; ?>" onkeypress="return validateQty(event);" /></td>
              </tr>
			  <tr>
                <td><strong><span class="redColor">*</span> Category:</strong></td>
                <td>
                   
				<?php
					$query_rsMgtList = "SELECT * FROM items where item_status = '1' ORDER BY item_id ASC";
					$rsMgtList = mysqli_query($cn, $query_rsMgtList) or die(mysqli_error($cn));
					$row_rsMgtList = mysqli_fetch_assoc($rsMgtList);
					$totalRows_rsMgtList = mysqli_num_rows($rsMgtList);
				?>
                                    <?php
                                            $selected = "selected = 'selected'";
                                    ?>
					<select name='category' id='category' class="admin-inputBox">
						<?php do { 
                                                    if($selID == $row_rsMgtList['item_id'])
                                                        $selected = "selected = 'selected'";
                                                    else
                                                        $selected = "";
                                                    ?>
						<option <?=$selected?> value='<?=$row_rsMgtList['item_id']; ?>'><?=$row_rsMgtList['item_name']; ?></option>
						<?php } while ($row_rsMgtList = mysqli_fetch_assoc($rsMgtList)); ?>
					</select>
				</td>
              </tr>
			  <tr>
                <td><strong>Price:</strong></td>
                <td><input type="text" name="price" id="price" class="admin-inputBox" size="40" value="<?php echo $mgtRow['price']; ?>"></td>
              </tr>
			  <tr>
			    <td align="left" valign="top"><strong>Image:</strong></td>
			    <td><input type="file" name="file" class="admin-inputBox" accept="image/x-png, image/gif, image/jpeg/"/>
		        <img src="images/subitems/<?php echo $mgtRow['sub_item_image']; ?>" height="100"  align="right"/></td>
		      </tr>
                    <tr>
			    <td><strong>Status:</strong></td>
			    <td><select name="status" id="status" class="admin-inputBox">
                  <?php 
				 if ($mgtRow['sub_item_status']==1)
				 {
				?>
                  <option value="1" selected="selected">Publish</option>
                  <option value="0"> Un-Publish</option>
                  <?php
				}
				?>
                  <?php
				if ($mgtRow['sub_item_status']==0)
				{
				?>
                  <option value="1">Publish</option>
                  <option value="0" selected="selected"> Un-Publish</option>
                  <?php
				}
				?>
                </select></td>
		      </tr>
			  <tr>
                <td><strong>For Deals Only:</strong></td>
                <td>
<textarea name="dealdetails"  cols="40" rows="4" class="admin-inputBox"><?=$mgtRow['dealdetails']?></textarea>
				</td>
              </tr>
              <tr>
                <td colspan="2">
                    <input type="submit" id="submit" class="admin-button" value="Save" /> 
                    <input type="button" class="admin-button" value="Cancel" onclick="javascript:history.back(1);" />				</td>
              </tr>
                    </form>
</td></tr></table>
                        </td>
                        <td align="left" valign="top">
                            <?php
                            if(!in_array($selID,$dealArray)){
                            ?>
                            <table border="0" width="100%">
                                <tr>
                                    <td>
                                        <form name="frmdetail" id="frmdetail" action="edit.php" method="post">
                          <fieldset>
                              <legend><strong>Ingrediants</strong></legend>
                            <table class="details" border="0" width="100%" cellpadding="4" cellspacing="4">
                                <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Amt</th>
                                    <th>Unit</th>
                                    <th>&nbsp;  </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <select name="item[]" class="admin-inputBox itm">
                                            <option value="">Select Ingrediant</option>
                                            <?php
                                        foreach($arrAuto as $data){
                                       ?>
                                            <option value="<?=$data?>"><?=$data?></option>
                                      <?php
                                        }
                                      ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input name="amt[]" type="text" size="2" class="admin-inputBox amt" onkeypress="return numbersonly(event);">
                                    </td>
                                    <td>
                                        <select name="unit[]" class="admin-inputBox unt">
                                            <option value="pcs"> PC(s) </option>
                                            <option value="mg"> MG </option>
                                            <option value="gm"> G </option>
                                            <option value="kg"> KG </option>
                                            <option value="ml"> ML </option>
                                            <option value="ltr"> Ltr </option>
                                        </select>
                                    </td>
                                    <td><input type = "button" value="+" class="add" class="admin-button">
                                        <input type= "button" value="x"  class="minus" class="admin-button">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                              <input type="hidden" name="sub_item_id" value="<?=$_GET['id']?>">
                              <input type="button" value="Save Ingredents" id="save_ing" class="admin-button">
                                </fieldset>
                        </form>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                    <?php
                            $sql = "select sub_item_ing_id,item,amt,unit from sub_item_ing where sub_item_id = '".$_GET['id']."'";
                        $result = mysqli_query($cn, $sql);
                        $dataArr = array();
                        while($data = mysqli_fetch_assoc($result)){
                                $dataArr[] = $data;
                        }
                        ?>
                            <strong>Sub Item Ingrediants Detail</strong>
                        <table width="100%">
                        <tr>
                            <th class="admin-tbHdRow1">Sno </th>
                            <th class="admin-tbHdRow1">Item </th>
                            <th class="admin-tbHdRow1">Amt </th>
                            <th class="admin-tbHdRow1"> Unit </th>
                            <th class="admin-tbHdRow1 admin-tbHdRow3" colspan="2">Operation</th>
                        </tr>
                                 <?php
                                 $i=1;
                                  foreach($dataArr as $data){
                                 ?>
                        <tr>
                            <td class="admin-tbRow1"><?=$i?></td>
                            <td class="admin-tbRow1"><?=$data['item']?></td>
                            <td class="admin-tbRow1"><?=$data['amt']?></td>
                            <td class="admin-tbRow1"><?=$data['unit']?></td>
                            <td class="admin-tbRow1"> 
                            <a href="edit_del.php?id=<?=$data['sub_item_ing_id']?>&idd=<?=$mgtId?>" title="Delete" class="admin-del" onclick="return confirm('Are you sure you want to perform this action ?');"></a>
                            </td>
                            <td class="admin-tbRow1 admin-tbRow2">
                            <a href="edit_edit.php?id=<?=$data['sub_item_ing_id']?>&idd=<?=$mgtId?>" title="Edit" class="admin-edit" onclick="return confirm('Are you sure you want to perform this action ?');"></a>
                            </td>
                        </tr>
                                 <?php
                                 $i++;
                                    }
                                 ?>
                    </table>
                                    </td>
                                </tr>
                            </table>
                            <?php
                                }
                            ?>
                        </td>
                    </tr>
</table>
            </table>
      
    </div>
      <p>&nbsp</p>
     <?php
       echo "<center><font color='white'>$selID</font></center>";
        if(in_array($selID,$dealArray)){
            $sqldeal = "select sub_item_name from sub_items where sub_item_id = '".$_GET['id']."' and item_id ='".$selID."'";
                 $result = mysqli_query($cn, $sqldeal);
                 $dataArr = array();
                 $data = mysqli_fetch_assoc($result);
     ?>
     <div id="admin-body" class="admin-fRight">
         <h1>Add Sub-Item <font color='green'><?=$data['sub_item_name']?></font></h1>
         <?php 
                $sqlsub="select sub_item_id,sub_item_name 
                        from sub_items 
                        where item_id not in ('18','19')
                        and sub_item_status='1'
                        order by sub_item_name asc";
                $result = mysqli_query($cn, $sqlsub);
                $dataArr = array();
                while($data = mysqli_fetch_assoc($result)){
                    $dataArr[] = $data;
                }  
        ?>
          <form name="frmdeal" id="frmdeal" action="save_deal.php" method="post">
                            <table class="deal" border="0" width="50%" cellpadding="4" cellspacing="4">
                                <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Amt</th>
                                    <th>&nbsp;  </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                         <select name="subitem[]" class="admin-inputBox subitem">
                                        <?php
                                            foreach($dataArr as $data){
                                        ?>
                                            <option value="<?=$data['sub_item_id']?>"><?=$data['sub_item_name']?></option>
                                            <?php } ?>   
                                        </select>
                                    </td>
                                    <td>
                                        <input name="qty[]" type="text" size="2" class="admin-inputBox qty" onkeypress="return numbersonly(event);">
                                    </td>
                                    <td><input type = "button" value="+" class="addd" class="admin-button">
                                        <input type= "button" value="x"  class="minusd" class="admin-button">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                              <input type="hidden" name="sub_item_id" value="<?=$_GET['id']?>">
                              <input type="button" value="Save Deal Item" id="save_deal" class="admin-button">
                              <input type="hidden" name="item_id" value="<?=$selID?>">
                              </form>
         
     </div>
      <?php
        }
      ?>
      <br>
          
          
    <br clear="all" />
  </div>
</div>
<?php include 'include/footer.php' ?>
<?php } ?>
</body>
</html>
