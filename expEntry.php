<?php
//Session started and database connection  included here...
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');


$currentPage = $_SERVER["PHP_SELF"];
$month = date('Y-m-d');
$arrDate = explode("-",$month);
$m = $arrDate[1];
$y = $arrDate[0];
$query_rsSum = "SELECT sum(exp_amount) as Total FROM exp_tab where DATE_FORMAT(`exp_date`,'%m') = '$m' and DATE_FORMAT(`exp_date`,'%Y') = '$y'";

$rsSum = mysqli_query($cn, $query_rsSum) or die(mysqli_error($cn));
$row_rsSum = mysqli_fetch_assoc($rsSum);
$totalRows_rsSum = mysqli_num_rows($rsSum);

$maxRows_rsDisplay = 30;
$pageNum_rsDisplay = 0;
if (isset($_GET['pageNum_rsDisplay'])) {
  $pageNum_rsDisplay = $_GET['pageNum_rsDisplay'];
}
$startRow_rsDisplay = $pageNum_rsDisplay * $maxRows_rsDisplay;

$query_rsDisplay = "SELECT exp_date, exp_desc, exp_amount FROM exp_tab order by  `exp_date` desc ";

$query_limit_rsDisplay = sprintf("%s LIMIT %d, %d", $query_rsDisplay, $startRow_rsDisplay, $maxRows_rsDisplay);
$rsDisplay = mysqli_query($cn, $query_limit_rsDisplay) or die(mysqli_error($cn));
$row_rsDisplay = mysqli_fetch_assoc($rsDisplay);


if (isset($_GET['totalRows_rsDisplay'])) {
  $totalRows_rsDisplay = $_GET['totalRows_rsDisplay'];
} else {
  $all_rsDisplay = mysqli_query($cn, $query_rsDisplay);
  $totalRows_rsDisplay = mysqli_num_rows($all_rsDisplay);
}
$totalPages_rsDisplay = ceil($totalRows_rsDisplay/$maxRows_rsDisplay)-1;

$queryString_rsDisplay = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_rsDisplay") == false && 
        stristr($param, "totalRows_rsDisplay") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_rsDisplay = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_rsDisplay = sprintf("&totalRows_rsDisplay=%d%s", $totalRows_rsDisplay, $queryString_rsDisplay);

function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = (!get_magic_quotes_gpc()) ? addslashes($theValue) : $theValue;

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
	$dateToday = $_POST['txtdate'];
	$newDate = date("Y-m-d", strtotime($dateToday));
	
  $insertSQL = sprintf("INSERT INTO exp_tab (exp_date, exp_desc, exp_amount) VALUES (%s, %s, %s)",
                       GetSQLValueString($newDate, "date"),
                       GetSQLValueString($_POST['txtdesc'], "text"),
                       GetSQLValueString($_POST['txtprice'], "int"));

  $Result1 = mysqli_query($cn, $insertSQL) or die(mysqli_error($cn));

  $insertGoTo = "expEntry.php?msg=Record+Saved+Successfully";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}

$query_rsauto = "SELECT DISTINCT(keyword_name) exp_desc FROM keywords where status = '1'";
$row_rsauto = mysqli_query($cn, $query_rsauto) or die(mysqli_error($cn));

$arrAuto=array();
while($data= mysqli_fetch_assoc($row_rsauto)){
	$arrAuto[] = $data['exp_desc'];
}

?>

<html>
<head>
<title>Expanses</title>

<script src="js/jquery.js?<?=Date();?>"></script>
<script src="js/jquery-ui.js?<?=Date();?>"></script>
<script src="js/jquery.datepick.js?<?=Date();?>"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.datepick.css?<?=Date();?>">
<link rel="stylesheet" type="text/css" href="css/jquery.css?<?=Date();?>">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css?<?=Date();?>">

<link href="css/styles.css?<?=Date();?>" type="text/css" rel="stylesheet" />
<script>
    
$(document).ready(function(){
	$("#txtdate").datepicker({dateFormat: 'dd-mm-yy'});
	
	$("#txtdesc").focus();
		
});

function numbersonly(e){
    
    var unicode=e.charCode? e.charCode : e.keyCode;
	if(unicode!=8)
    {
        if(unicode>=48 && unicode <=57 || unicode == 9 || unicde == 13)
        {
            return true;
        }
	else
        {
            return false;
        }
    }
}


$(document).on("keypress","#txtdesc",function(){
     
    var availableDesc = new Array;
    <?php 

	foreach($arrAuto as $auto){ 
		
	?>

             availableDesc.push('<?php echo strtoupper($auto); ?>');
    <?php } ?>
      
    $("#txtdesc").autocomplete({ 
		maxResults: 10,
		source: function(request, response) {
			var results = $.ui.autocomplete.filter(availableDesc, request.term);
			response(results.slice(0, this.options.maxResults));
		}
	});
}); 

</script>
</head>
<body>
<?php include 'include/header.php' ?>

<div class="admin-greyBg">
  <div id="msg" align="center"><?php if(isset($_GET['msg'])) { echo $_GET['msg']; } ?></div>
  <div class="admin-wrapper">
    <div id="admin-leftNav" class="admin-fLeft">
<?php include 'include/left_nav.php' ?>
    </div>
    <div id="admin-body" class="admin-fRight">
		<h1>Expanses</h1>
		<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <table width="100%" border="0" align="center">
    <tr> 
      <td class="admin-tbRow1 admin-tbRow3" width="62" align="left" valign="top">Date</td>
      <td class="admin-tbRow1 admin-tbRow3" width="120" align="left" valign="top"><label>
        <input name="txtdate" class="admin-inputBox" style="font-weight:bold;" type="text" id="txtdate" value="<?=Date('d-m-Y')?>" size="9"/>
      </label></td>
      <td class="admin-tbRow1 admin-tbRow3" width="97" align="left" valign="top">Description</td>
      <td class="admin-tbRow1 admin-tbRow3" width="330" align="left" valign="top"><input class="admin-inputBox" style="text-transform:uppercase;" type="text" name="txtdesc" id="txtdesc" size="40" placeholder="Description"></td>
      <td class="admin-tbRow1 admin-tbRow3" width="95" align="left" valign="top">Amount</td>
      <td class="admin-tbRow1 admin-tbRow2 admin-tbRow3" width="170" align="left" valign="top"><label>
        <input name="txtprice" type="text" placeholder="Amount (Rs)" class="admin-inputBox" id="txtprice" size="15" value="0"  onkeypress="return numbersonly(event);"/>
      </label></td>
    </tr>
    <tr>
      <td class="admin-tbRow1" colspan="6" align="center"><label>
        <input type="submit" class="admin-button" name="Submit" value="Save" />
        <input type="reset" class="admin-button" name="Submit2" value="Clear" />
      </label></td>
    </tr>
  </table>
  <input type="hidden" name="MM_insert" value="form1">
</form>
<br>
<?php
if(!empty($row_rsDisplay)){
?>

<table width="100%" border="0" align="center">
  <tr bgcolor="#F0F0F0">
    <td class="admin-tbHdRow1" width="102">Date</td>
    <td class="admin-tbHdRow1" width="523">Description</td>
    <td class="admin-tbHdRow1 admin-tbHdRow2" width="161">Amount</td>
  </tr>
  <?php do { 
  			$dateToday = $row_rsDisplay['exp_date'];
			$newDate = date("d-m-Y", strtotime($dateToday));
  ?>
    <tr>
      <td class="admin-tbRow1"><?php echo $newDate; ?></td>
      <td class="admin-tbRow1"><?php echo $row_rsDisplay['exp_desc']; ?></td>
      <td class="admin-tbRow1 admin-tbRow3" align="right"> <?php echo $row_rsDisplay['exp_amount']; ?></td>
    </tr>
    <?php } while ($row_rsDisplay = mysqli_fetch_assoc($rsDisplay)); ?>
	<tr bgcolor="#F0F0F0">
		<td class="admin-tbHdRow1">&nbsp;</td>
		<td class="admin-tbHdRow1">&nbsp;</td>
		<td class="admin-tbHdRow1 admin-tbRow3" align="right"><span class="style1"><?php echo "Rs. ".$row_rsSum['Total']; ?></span></td>
	</tr>
</table>
<br>
<table border="0" width="50%" align="center">
  <tr>
    <td width="23%" align="center"><?php if ($pageNum_rsDisplay > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsDisplay=%d%s", $currentPage, 0, $queryString_rsDisplay); ?>">First</a>
        <?php } // Show if not first page ?>
    </td>
    <td width="31%" align="center"><?php if ($pageNum_rsDisplay > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_rsDisplay=%d%s", $currentPage, max(0, $pageNum_rsDisplay - 1), $queryString_rsDisplay); ?>">Previous</a>
        <?php } // Show if not first page ?>
    </td>
    <td width="23%" align="center"><?php if ($pageNum_rsDisplay < $totalPages_rsDisplay) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsDisplay=%d%s", $currentPage, min($totalPages_rsDisplay, $pageNum_rsDisplay + 1), $queryString_rsDisplay); ?>">Next</a>
        <?php } // Show if not last page ?>
    </td>
    <td width="23%" align="center"><?php if ($pageNum_rsDisplay < $totalPages_rsDisplay) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_rsDisplay=%d%s", $currentPage, $totalPages_rsDisplay, $queryString_rsDisplay); ?>">Last</a>
        <?php } // Show if not last page ?>
    </td>
  </tr>
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
