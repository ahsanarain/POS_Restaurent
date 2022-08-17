
<?php
session_start();

include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include("lib/iq.php");

$sqlNote = "Select * from notification order by noid desc";

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
<title>Notifications</title>
<script src="js/jquery.js"></script>
<link href="css/styles.css" type="text/css" rel="stylesheet" />
<style>
.table-hover thead tr:hover th, 
 .table-hover tbody tr:hover td {
        background-color: lightyellow;
}
</style>

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
		<h1>List of Notes</h1>
        <form action="add_notes.php" method="post">
        	<input type="submit" class="admin-button" value="Add Notes" style="margin-bottom:10px;" />
	  </form>

            
	   
	    <table border="0" width="100%" cellspacing="0" cellpadding="0" class='table-hover'>
          <thead>
            <tr>
            <td width="5%" class="admin-tbHdRow1">Sno.</td>
            <td width="30%" class="admin-tbHdRow1" align="center">Heading</td>
            <td width="73%" class="admin-tbHdRow1">Detail</td>		
            <td width="10%" class="admin-tbHdRow1" align="center">Status</td>
            <td width="10%" class="admin-tbHdRow1" align="center">Edit</td>
            <td width="7%" class="admin-tbHdRow1 admin-tbHdRow3" align="center">Delete</td>
          </tr>
            </thead>
		  	 
            <tbody>
                <?php
                $sno=1;
                foreach($dataArr as $data){
                ?>
                <tr>
                    <td class="admin-tbRow1"><?=$sno?></td>
                    <td class="admin-tbRow1"><?=$data['nohead']?></td>
                    <td class="admin-tbRow1"><?=$data['nodetail']?></td>
                    <td class="admin-tbRow1">
                      
                        <?php
			  	if($data['nostatus']==1)
				{
					echo "Publish";
				}
				else
				{
				  	echo "Un-Publish";
				}
				?>
                    
                    </td>
                    <td class="admin-tbRow1" align="center" valign="top"><a href="edit_notes.php?id=<?PHP echo $data['noid']; ?>" title="Edit" class="admin-edit"></a></td>
                    <td class="admin-tbRow1 admin-tbRow3">
                        
<a href="delete_notes.php?id=<?PHP echo $data['noid']; ?>" title="Delete" class="admin-del" onclick="return confirm('Are you sure you want to perform this action ?');"></a>
                    </td>
                   
                <tr>
                <?php
                    $sno++;
                }
                ?>
            </tbody>
		 </table>
	  
    </div>
    <br clear="all" />
  </div>
</div>
<?php include 'include/footer.php' ?>

</body>
</html>


