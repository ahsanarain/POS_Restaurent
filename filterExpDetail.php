<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');

if(!empty($_POST['txtdate'])){
	$string = explode('-',str_replace(' ', '', $_POST['txtdate']));
	$from = $string[0];
	$to = $string[1];
	
	$from = str_replace('/', '-', $from);
	$to = str_replace('/','-',$to);
	
	$fromto = "Expanse Report from <font color='green'>'".$from."'</font> to <font color='green'>'".$to."'</font>";
	
	$from = $from;
	$from = date("Y-m-d", strtotime($from));
	
	$to = $to;
	$to = date("Y-m-d", strtotime($to));

	$query_rsFilterDisplay = "SELECT exp_date, UPPER(exp_desc) exp_desc, exp_amount FROM exp_tab where exp_date between '".$from."' and '".$to."' ORDER BY EXP_DATE ASC";
	$rsFilterDisplay = mysqli_query($cn, $query_rsFilterDisplay) or die(mysqli_error($cn));
	$row_rsFilterDisplay = mysqli_fetch_assoc($rsFilterDisplay);
	$totalRows_rsFilterDisplay = mysqli_num_rows($rsFilterDisplay);
	$tr = $totalRows_rsFilterDisplay;
	$q = "SELECT sum(exp_amount) TOTAL FROM exp_tab where exp_date between '".$from."' and '".$to."' ORDER BY EXP_DATE DESC";
	
	$rsFD = mysqli_query($cn, $q) or die(mysqli_error($cn));
	$rowrsFD = mysqli_fetch_assoc($rsFD);
	
}
?>
<html>
<head>

<title>Expanse Detail Report</title>
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
                    maxDate: 1
                    
        });
        //////////////////////////////
        $('#exptable').DataTable( {
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // Total over all pages
            total = api
                .column( 3 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Total over this page
            pageTotal = api
                .column( 3, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
 
            // Update footer
            $( api.column( 3 ).footer() ).html('Rs. '+pageTotal+'/-'
            );
        }
        });
        //////////////////////////////
        
        
        
});

</script>
</head>

<body>
<?php include 'include/header.php' ?>

<div class="admin-greyBg">
  <?php
		 if(!empty($_POST['txtdate']))
		 {
		 echo "<h2 align='center'>". $fromto ."</h2>";
			}
		
		 ?>
  <div class="admin-wrapper">
    <div id="admin-leftNav" class="admin-fLeft">
<?php include 'include/left_nav.php' ?>
    </div>
    <div id="admin-body" class="admin-fRight">
		<h1>Expanse Detail Filter</h1>
		<form action="filterExpDetail.php" method="POST">
                    <input name="txtdate" autocomplete="off" type="text" id="txtdate" size="25" class="admin-inputBox">
		 <input type="submit" value="Filter Now" class="admin-button">
		</form>  
<?php 
	if(!empty($row_rsFilterDisplay)){
?>	<table width="100%" border="0" class="display" align="center" id='exptable'>
	<thead>
   <tr bgcolor="#F0F0F0">
        <th>Sno.</th>
     <th>Entry Date </th>
     <th>Description</th>
     <th>Amount</th>
   </tr>
   </thead>
   <tfoot>
            <tr>
                <th colspan="3" style="text-align:right; font-size:12px;">Total:</th>
                <th style="text-align:right; font-size:12px;"></th>
            </tr>
    </tfoot>
   <tbody>
   <?php 
   	$sno = 1;
   do { 
   	$dateToday = $row_rsFilterDisplay['exp_date'];
	$newDate = date("d-m-Y", strtotime($dateToday));
   ?>
   
     <tr>
	   <td ><?=$sno?></td>
       <td ><?php echo $newDate; ?></td>
       <td><?php echo $row_rsFilterDisplay['exp_desc']; ?></td>
       <td  align="right"><?php echo $row_rsFilterDisplay['exp_amount']; ?></td>
     </tr>
     <?php $sno++; } while ($row_rsFilterDisplay = mysqli_fetch_assoc($rsFilterDisplay)); ?>
	 </tbody>
          
 </table>
                <br><br><br>
<?php
 }
 ?>
    </div>
  </div>
</div>
<?php include 'include/footer.php' ?>
 
</body>
</html>
