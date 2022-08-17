<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');

$flag="grid";
if(isset($_POST['method'])){
	if($_POST['method']=='paymentMade'){
		$itemID = $_POST['itemid'];
		$updateQry = "UPDATE ORDER_TAB SET STATUS = '0', AMOUNT_STATUS = 'R' WHERE ORDER_ID = '".$itemID."'";
		mysqli_query($cn, $updateQry);
		$sql = "select sub_item_id, sum(qty) as qty from sub_order_tab where order_id = '$itemID' order by qty";
                $row_rsauto = mysqli_query($cn, $sql) or die(mysqli_error($cn));

                $arrAuto=array();
                while($data= mysqli_fetch_assoc($row_rsauto)){
                        $arrAuto[] = $data;
                }
               
                $sql = "select 
                        a.sub_item_ing_id,
                        a.amt
                        from
                        sub_item_ing a,
                        stock b
                        where
                        a.sub_item_ing_id = b.sub_item_ing_id
                        and a.sub_item_id = '".$arrAuto[0]['sub_item_id']."'
                        ";
						
                $rowrsauto=mysqli_query($cn, $sql,$cn);
               $arrAuto1=array();
                while($data1= mysqli_fetch_assoc($rowrsauto)){
                        $arrAuto1[] = $data1;
                }
                for($i=1; $i<=$arrAuto[0]['qty']; $i++){
                    foreach($arrAuto1 as $data){
                        $sql="
                                update 
                                stock 
                                SET 
                                qty_out = qty_out + ".$data['amt']."  
                                 where sub_item_ing_id = '".$data['sub_item_ing_id']."'
                             ";
                    
					  
                        mysqli_query($cn, $sql);
                    }
                }		
		exit();
	}
}
if(isset($_POST['method'])){
	if($_POST['method']=='orderCancelled'){
		$itemID = $_POST['itemid'];
		$updateQry = "UPDATE ORDER_TAB SET STATUS = '0', AMOUNT_STATUS = 'C' WHERE ORDER_ID = '".$itemID."'";
		mysqli_query($cn, $updateQry);		
		exit();
	}
}
$file = ucwords(strtolower(str_replace ("_"," ",basename($_SERVER["SCRIPT_FILENAME"], '.php'))));
?>
<html>
<head>
<title><?=$file?> Grid</title>
<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<link href="css/styles.css" type="text/css" rel="stylesheet" />
<script>
    $(document).ready(function(){
       $("#grid").click(function(){
           
       });
    });
    $(document).on('click','.rcvdbtn',function(){
	if(confirm("Payment Received and Order Served Successfully ?")){
	var itemid = $(this).attr('act');
		$.ajax({
        type:'POST', 
        url: '<?=$_SERVER['PHP_SELF']?>', 
        data:{method:'paymentMade',itemid:itemid}, 
        success: function(response) {
			$("#upr").load(location.href + " #upr");
        }
    });
	}
	else{
		return false;
	}
});
$(document).on('click','.cancelbtn',function(){
	if(confirm("Sure to Cancel this Order ?")){
	var itemid = $(this).attr('act');
		$.ajax({
        type:'POST', 
        url: '<?=$_SERVER['PHP_SELF']?>', 
        data:{method:'orderCancelled',itemid:itemid}, 
        success: function(response) {
			$("#upr").load(location.href + " #upr");
        }
    });
	}
	else{
		return false;
	}
});
    
    $(function() {
    function callAjax(){
        $("#upr").load(location.href + " #upr");
    }
    setInterval(callAjax, 10000 );
});
</script>
<body>    
<div id='upr'>
<?php include ("pos_header.php");?>        
</div>    
</body>
</html>