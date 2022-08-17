<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include 'lib/iq.php';
$id = $_POST['id'];

$name = mres($_POST["name"]);
$user_id = mres($_POST["user_id"]);
$password = sha1($_POST["password"]);
$phone = mres($_POST["phone"]);
$designation = mres($_POST["designation"]);
$salary = mres($_POST["salary"]);
$service_charge = mres($_POST["service_charge"]);
$address = mres($_POST["address"]);
$status = mres($_POST["status"]);
$role = mres($_POST["role"]);
$image= mres($_FILES['file']['name']);
$cnic= mres($_FILES['file1']['name']);

$extimage = substr(strrchr($image, '.'), 1);
$extcnic = substr(strrchr($cnic, '.'), 1);
 if(!empty($image) && !empty($cnic))
	if (($extimage != "jpg") && ($extimage != "jpeg") && ($extimage != "gif") && ($extimage != "png") && ($extcnic != "jpg") && ($extcnic != "jpeg") && ($extcnic != "gif") && ($extcnic != "png"))
	{
		header("location: cms.php?msg=Image+Required.");
		die;
	}
	$time = date("fYhis");

	$new_image = $time . "image" . "." . $extimage;
        $new_cnic = $time . "cnic". "." . $extcnic;
	$image = $new_image;
        $cnic = $new_cnic;
        
	$destination1="images/staff_images/".$new_image;
	$destination2="images/staff_cnic/".$new_cnic;
        $action1 = copy($_FILES['file']['tmp_name'], $destination1);
	$action2 = copy($_FILES['file1']['tmp_name'], $destination2);	
	if(!$action1 && !$action2)
	{
		$insertMgtQry = "update staff_reg
                                set sname = '$name',
                                user_id = '$user_id',
                                password = '$password',
                                spno = '$phone',
                                sdesig = '$designation' ,
                                ssalary = '$salary' ,
                                service = '$service_charge',
                                saddress = '$address',
                                staff_status = '$status' ,
                                role_code = '$role'
                                where sid = '$id'
                                ";
	}	
	else
	{
		$insertMgtQry = "
                                update staff_reg
                                set sname = '$name',
                                user_id = '$user_id',
                                password = '$password',
                                spno = '$phone',
                                sdesig = '$designation' ,
                                ssalary = '$salary' ,
                                service = '$service_charge',
                                saddress = '$address',
                                staff_status = '$status' ,
                                role_code = '$role',
                                staff_image ='$image',
                                staff_cnic = '$cnic'
                                where sid = '$id'
                               ";
                               
	}
		if(mysqli_query($cn, $insertMgtQry))
		{
			header("location:empList.php?msg=User+Registered+Successfully.");

		}
		else
		{
			header("location:create_user.php?msg=Error+Occured.");
		}  
?>

<?php
	function mres($value)
	{
	    $search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
	    $replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");
	
	    return str_replace($search, $replace, $value);
	}
?>
