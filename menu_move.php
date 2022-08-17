<?PHP
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include('lib/iq.php');
if(isset($_POST['rid']))
{
	$mov=$_POST['mov'];
	if($mov=="up")
	{
		$i = $_POST['rid'];
		$cid = $_POST['id'.$i];
		$coid = $_POST['oid'.$i];
		$i--;
		$nid = $_POST['id'.$i];
		$noid = $_POST['oid'.$i];
		if($i>0)
		{
			$news_qry = "update res_functions set srno='$noid' where function_code='$cid'";
			if(mysqli_query($cn, $news_qry)){ }
			$news_qry = "update res_functions set srno='$coid' where function_code='$nid'";
			if(mysqli_query($cn, $news_qry)){ }
		}
		header("location:function.php?msg=Record+Swaped");
	}
	elseif($mov=="down")
	{
		$i = $_POST['rid'];
		$cid = $_POST['id'.$i];
		$coid = $_POST['oid'.$i];
		$i++;
		$nid = $_POST['id'.$i];
		$noid = $_POST['oid'.$i];
		$totalNewsQry = mysqli_query($cn, "select * from res_functions");
		$totNews = mysqli_num_rows($totalNewsQry);
		if($i<=$totNews)
		{
			$news_qry = "update res_functions set srno='$noid' where function_code='$cid'";
			if(mysqli_query($cn, $news_qry)){ }
			$news_qry = "update news_events set srno='$coid' where function_code='$nid'";
			if(mysqli_query($cn, $news_qry)){ }
		}
		header("location:function.php?msg=Record+Swaped");
	}
}
?>