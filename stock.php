<?php
//Session started and database connection  included here...
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include('lib/iq.php');

$query_rsauto = "SELECT distinct upper(item) as exp_desc,sub_item_ing_id as keyword_id,unit from sub_item_ing group by item order by item asc";

$row_rsauto = mysqli_query($cn, $query_rsauto) or die(mysqli_error($cn));

$arrAuto=array();
while($data= mysqli_fetch_assoc($row_rsauto)){
	$arrAuto[] = $data['keyword_id'].'~'.$data['exp_desc'].'~'.$data['unit'];
}

if(isset($_POST['method'])){
	if($_POST['method']=='balanceCalculate'){
                $sqlSum = "select sum(qty_in) as qty_in from"
                        . " stock where sub_item_ing_id = '".$_POST['itemid']."'";
		
                          
                $bal = mysqli_query($cn, $sqlSum) or die(mysqli_error($cn));
                $data= mysqli_fetch_assoc($bal);
                echo json_encode($data);
                exit();
	}
}

$sqlStockDetail = "select * from stock order by stock_id desc";
$rowStock = mysqli_query($cn, $sqlStockDetail) or die(mysqli_error($cn));

$arrStock=array();
while($dataStock= mysqli_fetch_assoc($rowStock)){
	$arrStock[] = $dataStock;
}



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Stock Entry Form</title>
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
/////////////////////////////////////////////
$('#stocktable').DataTable();
////////////////////////////////////////////

   $("#txtdesc").change(function(){
       if($(this).val()=="") return false;
      var currentDate = new Date();
      var day = currentDate.getDate();
      var month = currentDate.getMonth() + 1;
      var year = currentDate.getFullYear();
      
      var date = day+"/"+month+"/"+year;
      var item = $('option:selected', this).text();
      var itemid = $('option:selected', this).val(); 
      var unit = $('option:selected', this).attr('unit');
      $("#dat").val(date);
      $("#item").val(item);
      $("#unit").val(unit);
      $("#itemid").val(itemid);
    
        $.ajax({
          type:'POST', 
          url: '<?=$_SERVER['PHP_SELF']?>', 
          data:{method:'balanceCalculate',itemid:itemid}, 
          success: function(response){
              var data = $.parseJSON(response);
              if((data.qty_in) != null)
                $("#balance").val(data.qty_in);
              else
                $("#balance").val(0);
          }
      });
    }); 
});

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
    <div id="msg" align="center"><?php if(isset($_GET['msg'])) { echo $_GET['msg']; } ?></div>  
    <div id="admin-leftNav" class="admin-fLeft">
<?php include 'include/left_nav.php' ?>
    </div>
    <div id="admin-body" class="admin-fRight">
        <h1> Stock Form </h1>
        
      <select class="admin-inputBox" name="txtdesc" id="txtdesc"> 
          <option value="">Select Item</option>
          <?php
           $arrSep=array();
            foreach($arrAuto as  $desData){
               $arrSep = explode("~",$desData);
               
       ?>
          <option value="<?=$arrSep[0]?>" unit="<?=$arrSep[2]?>"><?=$arrSep[1]?></option>
        <?php
            }
        ?>
      </select>
      <br><br>
      <div id="forform">
          <table border="0" width="100%">
                                <tr>
                                    <td align="center" valign="middle">
                                        <form name="frmdetail" id="frmdetail" action="stock_save.php" method="post">
                          <fieldset>
                            <input type="hidden" name="itemid" id="itemid"> 
                            <table class="details" border="0" width="100%" cellpadding="4" cellspacing="4">
                                <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Item Name</th>
                                    <th>Unit</th>
                                    <th>Qty IN</th>
                               
                                    <th>Balance</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr align="center">
                                    <td>
                                        <input type="text" size="8" class="admin-inputBox" autocomplete="off" id="dat" name="dat" readonly>                                    </td>
                                    <td>
                                        <input type="text" size="35" class="admin-inputBox" autocomplete="off" id="item" name="item" readonly>                                    </td>
                                    <td>
                                       
                                        <input type="text" size="5" class="admin-inputBox" autocomplete="off" name="unit" id="unit" readonly>                                    </td>
                                    <td>
                                        <input type="text" size="5" class="admin-inputBox" autocomplete="off" id="in" value="0" name="in" onkeypress="return numbersonly(event);">                                    </td>
                                  
                                    <td>
                                        <input type="text" size="5" class="admin-inputBox" autocomplete="off" id="balance" value="0" name="balance" readonly>                                    </td>
                                </tr>
                                <tr align="center">
                                  <td align="center"><strong>Details</strong></td>
                                  <td colspan="4" align="left" valign="top"><label>
                                    <textarea name="details" id="details" cols="115" class="admin-inputBox" rows="5"></textarea>
                                  </label></td>
                                  </tr>
                                <tr align="center">
                                  <td colspan="5" align="center" valign="middle"><input name="submit" type="submit" class="admin-button" id="save_stock" value="Save Stock" /></td>
                                  </tr>
                                <tr align="center">
                                  <td colspan="5" align="center"><a href="stock_detail_view.php"><strong>STOCK DETAIL VIEW </strong></a></td>
                                  </tr>
                                </tbody>
                            </table>
                              <br>
                          </fieldset>
                        </form>
                                  </td>
                                </tr>
                                <tr>
                                    <td>
                                 
                     
                  
                                    </td>
                                </tr>
                            </table>
      </div>
                <br>
      <div id="detail">
          <table width="100%" border="0" class="display" align="center" id='stocktable'>
			<thead>
   				<tr bgcolor="#F0F0F0">
     				<th>Item Name</th>
     				<th>Unit</th>
     				<th>Qty IN</th>
					<th>Qty OUT </th>
					<th>Balance</th>
   				</tr>
   			</thead>
			<tbody>
				<?php foreach($arrStock as $stock){?>
				<tr>
					<td><?=$stock['item_name']?></td>
					<td><?=$stock['unit']?></td>
					<td><?=$stock['qty_in']?></td>
					<td><?=$stock['qty_out']?></td>
					<td><?=$stock['qty_in']-$stock['qty_out']?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>

      </div>    
        
    </div>
    
    <br clear="all" />
  </div>
</div>
<?php include 'include/footer.php' ?>
</body>
</html>
