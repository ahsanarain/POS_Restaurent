<?php
//Session started and database connection  included here...
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include('lib/iq.php');
$file = basename($_SERVER["SCRIPT_FILENAME"], '.php');
$sqlNote = "select * from notification where nostatus = '1'";
$result = mysqli_query($cn, $sqlNote);
$dataArr = array();
while($data = mysqli_fetch_assoc($result)){
        $dataArr[] = $data;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$org_name?> | <?=ucfirst($file);?></title>
<link href="css/styles.css" type="text/css" rel="stylesheet" />
<script src="js/jquery.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/jquery.datepick.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.datepick.css">
<link rel="stylesheet" type="text/css" href="css/jquery.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">

<style>
.table-hover thead tr:hover th, 
 .table-hover tbody tr:hover td {
        background-color: lightyellow;
}
</style>
<link href="css/styles.css" type="text/css" rel="stylesheet" />
</head>
<body>
<?php include 'include/header.php' ?>
<div class="admin-greyBg">
  <div class="admin-wrapper">
    <div id="admin-leftNav" class="admin-fLeft">
<?php include 'include/left_nav.php' ?>
    </div>
    <div id="admin-body" class="admin-fRight">

	
		<?php
				
				if( $_SESSION['user_id']=="admin"){
		?>
       <h1>Hello Mr.<?=$_SESSION['sname']?>!!! Welcome to the dashboard</h1>
<table width="100%" border="0" cellpadding="2" cellspacing="2">
  <tr>
    <td align="center" valign="top"><table width="180" height="180" border="0">
      <tr>
        <td bgcolor="#DDF1F4">&nbsp;</td>
      </tr>
    </table></td>
    <td align="center" valign="top"><table width="180" height="180" border="0">
      <tr>
        <td bgcolor="#EFE4EF">&nbsp;</td>
      </tr>
    </table></td>
    <td align="center" valign="top"><table width="180" height="180" border="0">
      <tr>
        <td bgcolor="#EDDABC">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td height="35" align="center" valign="top">&nbsp;</td>
    <td align="center" valign="top">&nbsp;</td>
    <td align="center" valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="top"><table width="180" height="180" border="0">
      <tr>
        <td bgcolor="#BDBBE8">&nbsp;</td>
      </tr>
    </table></td>
    <td align="center" valign="top"><table width="180" height="180" border="0">
      <tr>
        <td bgcolor="#EEE6AC">&nbsp;</td>
      </tr>
    </table></td>
    <td align="center" valign="top"><table width="180" height="180" border="0">
      <tr>
        <td bgcolor="#FC9981">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
</table>
        <!-- -->
<?php

				}  // END SESSION CHECK
				else{
?>
				<h1>Hello Mr.<?=$_SESSION['sname']?>!!! </h1>
                                <p>Click on the Links on the left for respective actions <br> Thank You</p>
                                <br>
                                    <table border="0" width="100%" align="center>">
                                        <tr>
                                            <td>  </td>
                                            
                                             <td align="right" valign="top">
                                                 <table border="0" width="300px">
                                                     <tr class="admin-tbHdRow1 admin-tbHdRow2" style="background-color:#b30504; color:white;">
                                                         <td> <h2> Things To Do <font color='yellow'>"<?=Date('d-m-Y')?>"</font></h2></td>
                                                     </tr>
                                                     <tr>
                                                         <td class="admin-tbRow1 admin-tbRow3" bgcolor='wheat'> 
<marquee width="100%" height="350px;" direction="up" scrollamount="3" scrolldelay="3" onmouseover="this.stop();" onmouseout="this.start();">
    <table border='0' width='100%'>
        <?php
        foreach($dataArr as $data){
        ?>
        <tr bgcolor='yellow'><td><h2><?=$data['nohead']?></h2></td></tr>
        <tr bgcolor='lightyellow'><td><?=$data['nodetail']?></td></tr>
        <tr><td><hr></td></tr>
        <?php
        }
        ?>
    </table>
</marquee>
                                                         </td>
                                                     </tr>
                                                 </table>
                                             
                                             </td>
                                            
                                        </tr>
                                        <tr>
                                          
                                            <td>  </td>
                                            <td>  </td>
                                        </tr>
                                        <tr>
                                         
                                            <td>  </td>
                                            <td>  </td>
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
