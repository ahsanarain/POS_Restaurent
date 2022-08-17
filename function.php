<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include('lib/iq.php');
$query_rsMgtList = "SELECT * FROM res_functions order by srno asc";
$rsMgtList = mysqli_query($cn, $query_rsMgtList) or die(mysqli_error($cn));
$row_rsMgtList = mysqli_fetch_assoc($rsMgtList);
$totalRows_rsMgtList = mysqli_num_rows($rsMgtList);

$first = 1;
$last = mysqli_num_rows($rsMgtList);

if(isset($_POST['func']))
if($_POST['func']=="up"){
    $id = $_POST['function_code'];
    $srno = $_POST['srno'];
    $first = $_POST['first'];
    $last = $_POST['last'];
    $sqlmoveup = "select function_code,srno from res_functions where srno = (SELECT max(srno) FROM res_functions WHERE srno < '$srno')";
    $result = mysqli_query($cn, $sqlmoveup,$cn);
    $row = mysqli_fetch_assoc($result);
   
    $uid = $row['function_code'];
    $usrno = $row['srno'];
    mysqli_query($cn,  ("update res_functions set srno = '$srno' where function_code = '$uid'");
    mysqli_query($cn,  ("update res_functions set srno = '$usrno' where function_code = '$id'");
   
}
if(isset($_POST['func']))
if($_POST['func']=="down"){
    $id = $_POST['function_code'];
    $srno = $_POST['srno'];
    $first = $_POST['first'];
    $last = $_POST['last'];
    $sqlmovedown = "select function_code,srno from res_functions where srno = (SELECT min(srno) FROM res_functions WHERE srno > '$srno')";
    $result = mysqli_query($cn, $sqlmovedown,$cn);
    $row = mysqli_fetch_assoc($result);
   
    $uid = $row['function_code'];
    $usrno = $row['srno'];


  mysqli_query($cn,  ("update res_functions set srno = '$srno' where function_code = '$uid'");
  mysqli_query($cn,  ("update res_functions set srno = '$usrno' where function_code = '$id'"); 
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Functions</title>
<script src="js/jquery.js"></script>
<link href="css/styles.css" type="text/css" rel="stylesheet" />
<style>
.table-hover thead tr:hover th, 
 .table-hover tbody tr:hover td {
        background-color: lightyellow;
}
</style>
<script>
function fun(dimension,id,srno,first,last,recno){
        if(dimension == "up"){
            if(recno==first){
               return false;
            }else{
               $.ajax({
        type: "POST",
        url: "<?=$_SERVER['PHP_SELF']?>",
        async:false,
        data: {func:dimension,function_code:id,srno:srno,first:first,last:last},
        success: function(res){
               
            }
                       
        });
        window.location.reload(true);
            }
        }else if(dimension == "down"){
           if(recno==last){
               return false;
            }else{
        $.ajax({
        type: "POST",
        url: "<?=$_SERVER['PHP_SELF']?>",
        async:false,
        data: {func:dimension,function_code:id,srno:srno,first:first,last:last},
        success: function(res){
               
            }
                       
        });
      window.location.reload(true);
            }
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
		<h1>List of Functions </h1>
        <form action="add_function.php" method="post">
        	<input type="submit" class="admin-button" value="Add Function" style="margin-bottom:10px;" />
	  </form>
	    <table border="0" width="100%" cellspacing="0" cellpadding="0" class='table-hover'>
          <thead>
            <tr>
            <td  class="admin-tbHdRow1">Name</td>
            <td  class="admin-tbHdRow1" align="center">Menu Name</td>
            <td  class="admin-tbHdRow1">File Name</td>
            <td  class="admin-tbHdRow1">Target</td>
            <td  class="admin-tbHdRow1">Menu Sorter</td>
            <td  class="admin-tbHdRow1">Main Menu Name</td>    
            <td width="10%" class="admin-tbHdRow1" align="center">Edit</td>
            <td width="7%" class="admin-tbHdRow1 admin-tbHdRow3" align="center">Delete</td>
          </tr>
		  </thead>
		  	 
		 <tbody>
          <?php $rec=1;  do { ?>
            <tr>
              <td class="admin-tbRow1" valign="top"><?php echo $row_rsMgtList['function_name']; ?></td>
                <td class="admin-tbRow1" align="center" valign="top"><?=$row_rsMgtList['menu_name']; ?></td>
		<td class="admin-tbRow1" valign="top"><?=$row_rsMgtList['file_name']; ?></td>
                <td class="admin-tbRow1" valign="top"><?=$row_rsMgtList['target']; ?></td>
                <td class="admin-tbRow1" valign="top" align="center">
                    <a href="#" title="Up" class="admin-up" onclick="fun('up',<?=$row_rsMgtList['function_code']?>,<?=$row_rsMgtList['srno']?>,<?=$first?>,<?=$last?>,<?=$rec?>);"> UP </a>
                    <a href="#" title="Down" class="admin-down" onclick="fun('down',<?=$row_rsMgtList['function_code']?>,<?=$row_rsMgtList['srno']?>,<?=$first?>,<?=$last?>,<?=$rec?>);"> DOWN </a>
                </td>
              <td class="admin-tbRow1" align="center" valign="top"><?=$row_rsMgtList['menu_head']; ?></td>  
              <td class="admin-tbRow1" align="center" valign="top"><a href="edit_function.php?id=<?PHP echo $row_rsMgtList['function_code']; ?>" title="Edit" class="admin-edit"></a></td>
              <td class="admin-tbRow1 admin-tbRow2" align="center" valign="top"><a href="delete_function.php?id=<?PHP echo $row_rsMgtList['function_code']; ?>" title="Delete" class="admin-del" onclick="return confirm('Are you sure you want to perform this action ?');"></a></td>
            </tr>
            <?php $rec++;  } while ($row_rsMgtList = mysqli_fetch_assoc($rsMgtList)); ?>
		 </tbody>
		 </table>
	   </form>
    </div>
    <br clear="all" />
  </div>
</div>
<?php include 'include/footer.php' ?>

</body>
</html>
<?php
mysqli_free_result($rsMgtList);
?>
