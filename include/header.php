<?php 
if(!isset($_SESSION))
session_start(); 
?>
<div class="admin-header">
	<div class="admin-wrapper">
    	<div class="admin-logo admin-fLeft" style="font-size:38px;">
	<a href="cms.php?mon=<?=Date('m')?>"><img src="images/logo - small.png" width="150"/></a>
		</div>
    	<div class="admin-wcMsg admin-fRight">Welcome (<strong><?php echo $_SESSION['sname']?></strong>)&nbsp;<span>|</span>&nbsp; <a href="logout.php">Sign Out</a></div>
        <br clear="all" />
	</div>
</div>
<div class="admin-blueStrip">&nbsp;</div>