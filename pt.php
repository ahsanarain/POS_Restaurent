<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include('lib/iq.php');

if(!empty($_REQUEST['txtdate'])){
	$string = str_replace(' ', '', $_REQUEST['txtdate']);
	$from = $string;
	$to = $string;
	
	$from = str_replace('/', '-', $from);
	$to = str_replace('/','-',$to);
	
	$fromto = "Point Of Sale Report from <font color='green'>'".$from."'</font> to <font color='green'>'".$to."'</font>";
	
	$from = $from;
	$from = date("Y-m-d", strtotime($from));
	
	$to = $to;
	$to = date("Y-m-d", strtotime($to));

	$query_rsFilterDisplay = "SELECT
                                SUM(AMOUNT) AS AMOUNT,
								SUM(SERVICE_CHARGE) AS SERVICE_CHARGE,
								SUM(DISCOUNT) AS DISCOUNT,
                                                                ROUND(AVG(TAX1),0) AS TAX1,
                                                                ROUND(AVG(TAX2),0) AS TAX2,
																ROUND(AVG(TAX3),0) AS TAX3
								FROM
								ORDER_TAB 
								WHERE
								AMOUNT_STATUS NOT IN ('C','P') AND 
								DATE(DATE_TIME) 
								BETWEEN  '$from' and '$to'";
								
								

	$rsFilterDisplay = mysqli_query($cn, $query_rsFilterDisplay) or die(mysqli_error($cn));
	$row_rsFilterDisplay = mysqli_fetch_assoc($rsFilterDisplay);
	$totalRows_rsFilterDisplay = mysqli_num_rows($rsFilterDisplay);
	$tr = $totalRows_rsFilterDisplay;
	
	///////////////////////////////////////////////////////////////////////////////////////////////////
	
	$query_rsFilterDisplay1 = "SELECT
                                    SUM(exp_amount) AS AMOUNT
                                    FROM
                                    exp_tab 
                                    WHERE 
                                    DATE(exp_date) 
                                    BETWEEN  '$from' and '$to'";
	
	$rsFilterDisplay1 = mysqli_query($cn, $query_rsFilterDisplay1) or die(mysqli_error($cn));
	$row_rsFilterDisplay1 = mysqli_fetch_assoc($rsFilterDisplay1);
	$totalRows_rsFilterDisplay1 = mysqli_num_rows($rsFilterDisplay1);
	$tr1 = $totalRows_rsFilterDisplay1;
	
        $sqlOwnerAmount = "Select pt_main from pt where pt_date = '$from'";
        $result = mysqli_query($cn, $sqlOwnerAmount);
	$dataArr = array();
	while($data = mysqli_fetch_assoc($result)){
		$dataArr[] = $data;
	}
        
        $sqlPreviousDay = "select * from pt where pt_date = '". ( date('Y-m-d', strtotime($from . " - 1 day")))."'";
      
        $result = mysqli_query($cn, $sqlPreviousDay);
	$dataA = array();
	while($dat = mysqli_fetch_assoc($result)){
		$dataA[] = $dat;
	}
	
        if(!empty($dataA)){
        		$pdaypt=($dataA[0]['pt_sale']+$dataA[0]['pt_pdpt'])-($dataA[0]['pt_exp']+$dataA[0]['pt_main']);
			
		}
	
}
?>
<html>
<head>

<title>Petty Cash</title>
<link href="css/styles.css" type="text/css" rel="stylesheet" />
<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/jquery.dataTables.js"></script>   

<script src="js/jquery.datepick.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.datepick.css">
<link rel="stylesheet" type="text/css" href="css/jquery.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css"> 

</script>
<style>
.table-hover thead tr:hover th, 
 .table-hover tbody tr:hover td {
        background-color: lightyellow;
}
.noborder{
    border:0px;
    font-size:20px;
}
.style4 {
	font-size: 16px;
	font-weight:bold;
	border: 0px;
}
.style12 {font-size: 12px; font-weight: bold; }
.style13 {padding: 6px; font-family: Tahoma, Verdana, Arial, Helvetica, sans-serif; color: #333333; border: 1px solid #CCCCCC;}
</style>

<script>
$(document).ready(function(){
   $("#txtdate").datepick({
                    rangeSelect: false,
                    dateFormat: 'dd/mm/yyyy',
                    monthsToShow: 1,
                    maxDate: 1,
                    
                });
  docalculation();
  $("#owner").blur(function(){
      if($(this).val()==""){
          $(this).val(0);
      }
      docalculation();
  });
  
  $('#exptable').DataTable( {
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            
            
        });
  
  
});
function docalculation(){
     var amount1 = parseInt($("#sale").val()) + parseInt($("#previous_day_petty").val());	   
     var amount2 =  parseInt($("#expanse").val());
     var tax1 = parseInt($("#tax1").val());
     var tax2 = parseInt($("#service_charge").val());
     var tax3 = parseInt($("#tax3").val());
	
	var total_tax = tax1 + tax2 + tax3;
	  
   var total_cash = (amount1-amount2)-$("#owner").val()- $("#discount").val();
   $("#total_cash").val(total_cash+tax2);
   
   $("#sale").val( $("#sale").val());

}
function numbersonly(e){
    
    var unicode=e.charCode? e.charCode : e.keyCode;
    if(unicode!=8)
    {
        if(unicode>=48 && unicode <=57 || unicode == 9)
        {
            return true;
        }
	else
        {
            return false;
        }
    }
}
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
        <h1>Petty Cash </h1>
        <form action="pt.php" method="POST">
		 <input autocomplete="off" name="txtdate" type="text" id="txtdate" size="25" class="admin-inputBox">
		 <input type="submit" value="Fetch" class="admin-button">
	</form>
        <?php 
	if(!empty($row_rsFilterDisplay)){
            $applied_date = str_replace("/", "-",$_REQUEST['txtdate'] );
            $applied_date = strtotime($applied_date);
          
	?>
            <form action="pt_save.php" method="post" enctype="multipart/form-data" name="frm1" style="margin:0; padding:0;" >
         <?php
         
                $pt_sale =  (isset($row_rsFilterDisplay['AMOUNT']))? $row_rsFilterDisplay['AMOUNT'] : '0';
		
        	$tx1 = $row_rsFilterDisplay['TAX1'];
			$tx2 = $row_rsFilterDisplay['TAX2'];
			$tx3 = $row_rsFilterDisplay['TAX3'];

                $tax1 = $pt_sale * $tx1 / 100;
                $tax1 = round($tax1,0);
                $tax1 = ($tax1<0) ? "0" : $tax1;
               
			    $tax2 = $pt_sale * $tx2 / 100;
                $tax2 = round($tax2,0);
				$tax2 = ($tax2<0) ? "0" : $tax2;
				
				
				$tax3 = $pt_sale * $tx3 / 100;
                $tax3 = round($tax3,0);
				$tax3 = ($tax3<0) ? "0" : $tax3;
                
                
				$tax2 = (isset($row_rsFilterDisplay['SERVICE_CHARGE']))? $row_rsFilterDisplay['SERVICE_CHARGE'] : '0';

                $pt_exp = (isset($row_rsFilterDisplay1['AMOUNT']))? $row_rsFilterDisplay1['AMOUNT'] : '0';
                $disc = (isset($row_rsFilterDisplay['DISCOUNT']))? $row_rsFilterDisplay['DISCOUNT'] : '0';
                $pt_main = $data['pt_main'];

		
            ?>
			
                   <table border="0" width="100%" cellspacing="4" cellpadding="4">
              <tr bgcolor="silver">
                <td><span class="style12">Date</span></td>
                <td><span class="style12">Sale</span></td>
                <td><span class="style12">
                <?=$row_rsFilterDisplay['TAX1']?>
                % Tax</span></td>
                <td><span class="style12">Sr Chr 
                <?=$row_rsFilterDisplay['TAX2']?>
                %</span></td>
				<td><span class="style12">OTHR 
		        <?=$row_rsFilterDisplay['TAX3']?>
			    %</span></td>
                <td><span class="style12">Expanse</span></td>
                <td><span class="style12">Discount</span></td>
                <td><span class="style12">Prev Day PC</span></td>
                <td><span class="style12">Owner's Amt</span></td>
                <td><span class="style12">Total Cash</span></td>
              </tr>   
              <tr>
                  <td>
                     <input name="date" type="text" class="admin-inputBox style4 " id="date" value="<?=(isset($_REQUEST['txtdate'])? $_REQUEST['txtdate']:'')?>" size="10" readonly/>                  </td>
                  <td><input name="sale" type="text" class="admin-inputBox style4 " id="sale" style="color:green;" value="<?=$pt_sale?>" size="5" readonly ></td>
                  <td>
                      <input name="tax1" type="text" class="admin-inputBox style4 " id="tax1" style="color:lightgrey;" value="<?=$tax1?>" size="5" readonly >                  </td>
                  <td>
                      <input name="service_charge" type="text" class="admin-inputBox style4 " id="service_charge" style="color:lightgreen;" value="<?=$tax2?>" size="5" readonly >                  </td>
				  <td>
                      <input name="tax3" type="text" class="admin-inputBox style4 " id="tax3" style="color:lightgreen;" value="<?=$tax3?>" size="5" readonly >                  </td>
                  <td>
                      <input name="expanse" type="text" class="admin-inputBox style4 " id="expanse" style="color:red;" value="<?=$pt_exp?>" size="5" readonly >                  </td>
                  <td>
                      <input name="discount" type="text" class="admin-inputBox style4 " id="discount" style="color:pink;" value="<?=$disc?>" size="5" readonly >                  </td>
                  <td>
                      <input name="previous_day_petty" type="text" class="admin-inputBox style4 " id="previous_day_petty" style="color:green;" value="<?=(isset($pdaypt))? $pdaypt : '0';?>" size="5" readonly >                  </td>
                  <td>
                      <input name="owner" type="text" class="style13" id="owner" onKeyPress="return numbersonly(event);" value="<?=(isset($dataArr[0]['pt_main']))? $dataArr[0]['pt_main'] : '0';?>" size="5"/>                  </td>
                  <td>
                      <input name="total_cash" type="text" class="admin-inputBox style4 " id="total_cash" style="color:green;" size="5" readonly/>                  </td>
              </tr>
              <tr>
                <td colspan="6" align='center'>
                  <input type="submit" id="submit" class="admin-button" value="Save" onClick="return validateForm();" />  
                  <input type="reset" class="admin-button" value="Cancel">                </td>
              </tr>
            </table> 
      </form>
        <?php
        }
        ?>
        <br>
    <?php
    if(isset($_REQUEST['txtdate'])){
     $applied_date = str_replace("/", "-",$_REQUEST['txtdate'] );
     $applied_date = strtotime($applied_date);
     $sql="select * from pt where pt_date >= '$applied_date' order by pt_date desc";
   
    $result = mysqli_query($cn, $sql);
    $dataA = array();
	while($dat = mysqli_fetch_assoc($result)){
		$dataA[] = $dat;
	}
   
   ?>
 <table width="100%" border="0" class="display" align="center" id='exptable'>
	<thead>
   <tr bgcolor="#F0F0F0">
     <th>Sno.</th>
     <th>Date </th>
     <th>Sale</th>
     <!--
     <th><?=$ttaaxx1?>% Tax</th>
     <th><?=$ttaaxx2?>% Srv Chrg</th>
     -->
     <th>Expanse</th>
     <th>Discount</th>
     <th>Owner</th>
     <th>Total </th>
   </tr>
   </thead>
   <tbody>
    <?php
   $srno=1;
    foreach($dataA as $data){
	
	
	
        $pt_sale =  $data['pt_sale'];
        $pt_exp = $data['pt_exp'];
        $disc = $data['pt_disc'];
        $pt_main = $data['pt_main'];

	$string = str_replace(' ', '', $data['pt_date']);
	$from = $string;
	$to = $string;
	
	$from = str_replace('/', '-', $from);
	$to = str_replace('/','-',$to);


    ?>
     <tr>
       <td><?=$srno?></td>
       <td><?=date("d-m-Y", strtotime($data['pt_date']));?></td>
       <td><?=$pt_sale?></td>
       <!--
       <td><?='tax2'?></td>
       <td><?='tax2'?></td>
       -->
       <td><?=$pt_exp?></td>
       <td><?=$disc?></td>
       <td><?=$pt_main?></td>
       <td>
	   <?php 
	   		$fig=0;
			if(empty($data['pt_pdpt']) || $data['pt_pdpt']=="" || !isset($data['pt_pdpt'])){
				$data['pt_pdpt'] = 0;
			}
			else{
				$data['pt_pdpt'] = $data['pt_pdpt'];
			}
	   		$fig = ($data['pt_pdpt'] + $data['pt_sale'] - $data['pt_main']) - $data['pt_exp'];
			echo $fig;
	   
	   ?>
	   </td>
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
