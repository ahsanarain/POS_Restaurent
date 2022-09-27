<?php
if (!isset($_SESSION)) session_start();

include('Connections/cn.php');
include('lib/iq.php');

$userName=$_POST['loginname'];
$password=$_POST['password'];

if(isset($userName) && isset($password))
{
    $uname = strtolower($userName);
    $upass = strtolower($password);
    $query ="SELECT * FROM staff_reg WHERE  user_id = '$uname' AND password = '$upass' and staff_status = 1";

    $result = mysqli_query($cn, $query);
    $num=mysqli_num_rows($result);
    $rs = mysqli_fetch_array($result);

    if( $num > 0 )
    {
        $_SESSION['sid'] = $rs[0];
        $_SESSION['sname']=$rs[1];
        $_SESSION['user_id']=$uname;
        $_SESSION['rcode']=$rs[12];
        header("location:cms.php");
        exit;
    }
    else
    {
        header("location:index.php?loginmsg=Invalid+User+Name+/+Password+or+User+Is+Inactive.");
        exit;
    }
}
?>