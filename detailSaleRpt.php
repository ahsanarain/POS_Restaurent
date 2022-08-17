<?php
//Session started and database connection  included here...
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include("lib/iq.php");
$dealArray = array('18','19');

if(!empty($_POST['txtdate'])){
	$string = explode('-',str_replace(' ', '', $_POST['txtdate']));
	$from = $string[0];
	$to = $string[1];
	
	$from = str_replace('/', '-', $from);
	$to = str_replace('/','-',$to);
	
	$fromto = "Detail Sale Summary Report from <font size='4' color='green'>'".$from."'</font> to <font size='4' color='green'>'".$to."'</font>";
	
	$from = $from;
	$from = date("Y-m-d", strtotime($from));
	
	$to = $to;
	$to = date("Y-m-d", strtotime($to));

	$sql = "select
                distinct(sot.item),
                sum(sot.qty) qty,
                sot.sub_item_id,
                sot.item_id
                from
                order_tab ot,
                sub_order_tab sot
                WHERE
                ot.order_id = sot.order_id
				and ot.amount_status = 'R'
                and Date(ot.date_time) between '$from' and '$to'
                group by sot.item, sot.sub_item_id";
   
        $result = mysqli_query($cn, $sql);
	$dataArr = array();
	while($data = mysqli_fetch_assoc($result)){
                if(!empty($data['item']))
		$dataArr[] = $data;
	}
}




?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Detail Sale Report</title>
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
        
       $('#exptable').dataTable({
    "bPaginate": false,
    "bLengthChange": false,
    "bFilter": true,
    "bInfo": false,
    "bAutoWidth": false });

    $(".showhide").each(function(){
       $(this).hide(); 
    });
	
});
$(document).on('click','.dtl',function(){
    var val = $(this).val();
    if(val=="+"){
        $(this).val("-");
        $(this).next('span').show();
    }else{
        $(this).val("+");
        $(this).next('span').hide();
    }
});
</script>

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
	<h1>Detail Sale Summary Report</h1>
	<form action="detailSaleRpt.php" method="POST">
            <input name="txtdate" type="text" autocomplete="off" id="txtdate" size="25" class="admin-inputBox">
            <input type="submit" value="Filter Now" class="admin-button">
        </form>
        <br>
 <?php
  if(isset($dataArr)){
 ?>
    <table width="100%" border="0" class="display" align="center" id='exptable'>
        <thead>
            <tr>
                <td class="admin-tbHdRow1" width="5%">Sno</td>
                <td class="admin-tbHdRow1" align="left">Item</td>
                <td class="admin-tbHdRow1 admin-tbHdRow3">Quantity</td>
            </tr>
        </thead>
        <tbody>
            <?php
            $srno=1;
            foreach($dataArr as $data){
            ?>
            <tr>
                <td class="admin-tbRow1"  valign="top"><?=$srno?></td>
                <td class="admin-tbRow1"  valign="top">
                    <b> 
                        <!-- .' - '.$data['sub_item_id'].' - '.$data['item_id'] -->
                        <?=$data['item']?><br>
                    </b>
                 <?php
                            //;
                          
                        $sql2 = "select sub_item_id,sub_item_ing_id,item,amt,unit from sub_item_ing where sub_item_id = '".$data['sub_item_id']."'";
                       
                        $result2 = mysqli_query($cn, $sql2);
                        $dataArr2 = array();
                        while($data2 = mysqli_fetch_assoc($result2)){
                                $dataArr2[] = $data2;
                        }
                    if(!empty($dataArr2)){    
                        ?>
                    <input type="button" class="dtl" value="+">
                    <span class="showhide">
                    
                    <table width="100%">
                        <tr>
                            <th class="admin-tbHdRow1">Sno </th>
                            <th class="admin-tbHdRow1">Item </th>
                            <th class="admin-tbHdRow1">Amt </th>
                            <th class="admin-tbHdRow1 admin-tbHdRow3"> Unit </th>
                        </tr>
                                 <?php
                                 $i=1;
                                  foreach($dataArr2 as $data2){
                                 ?>
                        <tr>
                            <td class="admin-tbRow1"><?=$i?></td>
                            <td class="admin-tbRow1"><?=strtoupper($data2['item'])?></td>
                            <td class="admin-tbRow1"><?=$data2['amt']*$data['qty']?></td>
                            <td class="admin-tbRow1 admin-tbRow3"><?=strtoupper($data2['unit'])?></td>
                        </tr>
                                 <?php
                                 $i++;
                                    }
                                 ?>
                    </table>
                    </span>
                    <?php
                    }else{
                        if(in_array($data['item_id'],$dealArray)){
                        
                        $sqlD = "SELECT *
                                    FROM sub_item_ing 
                                    where 
                                    sub_item_id in(
                                    select subitem from deal where sub_item_id = '".$data['sub_item_id']."' and item_id in ('18','19')
                                    )";
                            $result2 = mysqli_query($cn, $sqlD);
                            $dataDeal = array();
                            while($dt = mysqli_fetch_assoc($result2)){
                                    $dataDeal[] = $dt;
                            }
                           
                        ?>
                            <input type="button" class="dtl" value="+">
                            <span class="showhide">

                            <table width="100%">
                                <tr>
                                    <th class="admin-tbHdRow1">Sno </th>
                                    <th class="admin-tbHdRow1">Item </th>
                                    <th class="admin-tbHdRow1">Amt </th>
                                    <th class="admin-tbHdRow1 admin-tbHdRow3"> Unit </th>
                                </tr>
                                         <?php
                                         $si=1;
                                          foreach($dataDeal as $deal){
                                         ?>
                                <tr>
                                    <td class="admin-tbRow1"><?=$si?></td>
                                    <td class="admin-tbRow1"><?=strtoupper($deal['item'])?></td>
                                    <td class="admin-tbRow1"><?=$deal['amt']*$data['qty']?></td>
                                    <td class="admin-tbRow1 admin-tbRow3"><?=strtoupper($deal['unit'])?></td>
                                </tr>
                                         <?php
                                         $si++;
                                            }
                                         ?>
                            </table>
                            </span>
                        <?php
                        }
                    }
                    ?>
                   
                </td>
                <td class="admin-tbRow1 admin-tbRow2" align="center" valign="middle"><font size="4"><?=$data['qty']?></font></td>
            </tr>
            <?php $srno++;
            }
            ?>
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