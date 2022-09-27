<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');

$name = mres($_POST["name"]);
$user_id = mres($_POST["user_id"]);
$password = sha1(mres($_POST["password"]));
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
	$insertMgtQry = "insert into staff_reg
                                (
                                sname,
                                user_id,
                                password,
                                spno,
                                sdesig,
                                ssalary,
                                service,
                                saddress,
                                staff_status,
                                role_code
                                )
                                VALUES
                                (
                                '$name',
                                '$user_id',
                                '$password',
                                '$phone',
                                '$designation',
                                '$salary',
                                '$service_charge',
                                '$address',
                                '$status',
                                '$role'
                                )";
}
else
{
	$insertMgtQry = "insert into staff_reg
                                (
                                sname,
                                user_id,
                                password,
                                spno,
                                sdesig,
                                ssalary,
                                service,
                                saddress,
                                staff_status,
                                staff_image,
                                staff_cnic,
                                role_code
                                )
                                VALUES
                                (
                                '$name',
                                '$user_id',
                                '$password',
                                '$phone',
                                '$designation',
                                '$salary',
                                '$service_charge',
                                '$address',
                                '$status',
                                '$image',
                                '$cnic',
                                '$role'
                                )";
}

if(mysqli_query($cn, $insertMgtQry))
	header("location:empList.php?msg=User+Registered+Successfully.");
else
	header("location:create_user.php?msg=Error+Occured.");
?>

<?php
function mres($value)
{
	$search = array("\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a");
	$replace = array("\\\\","\\0","\\n", "\\r", "\'", '\"', "\\Z");
	return str_replace($search, $replace, $value);
}
?>
