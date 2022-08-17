<?PHP
	if (!isset($_SESSION)){ session_start(); }
	unset($_SESSION['sname']);
        unset( $_SESSION['user_id']);
	if(!isset($_SESSION['sname']) || !isset( $_SESSION['user_id']))
	{
		header("location:index.php");
	}
?>