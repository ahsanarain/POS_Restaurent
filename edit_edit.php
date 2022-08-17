<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include('lib/iq.php');
    $sql1 = "select sub_item_id,sub_item_name from sub_items where sub_item_id = '".$_GET['idd']."'";
    $result1 = mysqli_query($cn, $sql1);
	$dataArr1 = array();
	while($data1 = mysqli_fetch_assoc($result1)){
		$dataArr1[] = $data1;
	}
    $sql2 = "select sub_item_ing_id,item,amt,unit from sub_item_ing where sub_item_ing_id = '".$_GET['id']."' and sub_item_id = '".$_GET['idd']."'";
    $result2 = mysqli_query($cn, $sql2);
	$dataArr2 = array();
	while($data2 = mysqli_fetch_assoc($result2)){
		$dataArr2[] = $data2;
	}
      
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$org_name?></title>
<link href="css/styles.css" type="text/css" rel="stylesheet" />
<script src="js/jquery.js"></script>

    <script language="JavaScript" type="text/javascript" src="js/tiny_mce.js"></script>
	<script language="JavaScript" type="text/javascript" src="js/custom_config.js"></script>
	
<link href="css/jquery-ui.css" type="text/css" rel="stylesheet" />
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
		<h1>Edit <?=$dataArr1[0]['sub_item_name']?> Ingrediant</h1>
                <form action="edit_edit_confirm.php" method="post" enctype="multipart/form-data" style="margin:0; padding:0;">
        	<table border="0" cellspacing="4" cellpadding="4">
              <tr>
                <td><strong>Item:</strong></td>
                <td><input type="text" name="item" id="date" class="admin-inputBox" size="20" value="<?=$dataArr2[0]['item']?>" onkeypress="return validateQty(event);" /></td>
              </tr>
              <tr>
                <td><strong>Amount</strong></td>
                <td><input type="text" name="amt" class="admin-inputBox" size="5" value="<?=$dataArr2[0]['amt']?>" onkeypress="return numbersonly(event);" /></td>
              </tr>
              <tr>
                <td><strong>Unit</strong></td>
                <td>
                    
                    <select name="unit" class="admin-inputBox unt">
                        <option value="pcs" <?=($dataArr2[0]['unit']=="pcs")?"selected='selected'":"";?>> PC(s) </option>
                        <option value="mg" <?=($dataArr2[0]['unit']=="mg")?"selected='selected'":"";?>> MG </option>
                        <option value="gm" <?=($dataArr2[0]['unit']=="gm")?"selected='selected'":"";?> > G </option>
                        <option value="kg" <?=($dataArr2[0]['unit']=="kg")?"selected='selected'":"";?>> KG </option>
                        <option value="ml" <?=($dataArr2[0]['unit']=="ml")?"selected='selected'":"";?>> ML </option>
                        <option value="ltr" <?=($dataArr2[0]['unit']=="ltr")?"selected='selected'":"";?>> Ltr </option>
                    </select>
                </td>
              </tr>
               <tr>
                <td colspan="2">
                    <input type="hidden" name="sub_item_ing_id" value="<?=$_GET['id']?>">
                    <input type="hidden" name="sub_item_id" value="<?=$_GET['idd']?>">
                    <input type="submit" id="submit" class="admin-button" value="Save" /> 
                    <input type="button" class="admin-button" value="Cancel" onclick="javascript:history.back(1);" />				</td>
              </tr>      
                
     </div>
    <br clear="all" />
  </div>
</div>
<?php include 'include/footer.php' ?>
</body>
</html>

