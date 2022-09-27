<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include("lib/iq.php");

if(isset($_REQUEST['id']))
{
    $id=$_REQUEST['id'];
    $sqlUser="select * from staff_reg where sid = '$id'";
    $result = mysqli_query($cn, $sqlUser);
    $dataArr = array();
    while($data = mysqli_fetch_assoc($result)) $dataArr[] = $data;
    $dataArr = $dataArr[0];
}

$file = ucwords(strtolower(str_replace ("_"," ",basename($_SERVER["SCRIPT_FILENAME"], '.php'))));
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?=$file?></title>
    <link href="css/styles.css" type="text/css" rel="stylesheet" />
    <link href="css/jquery-ui.css" type="text/css" rel="stylesheet" />
    <script src="js/jquery.js"></script>
    <script language="javascript" type="text/javascript">
        $(document).ready(function()
        {
            $("#name").focus();
        });

        function validateQty(event)
        {
            var key = window.event ? event.keyCode : event.which;

            if (event.keyCode == 8 || event.keyCode == 46
                || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 9)
            {
                return true;
            }
            else if ( key >= 65 && key <=90 || key>=97 && key <=122 || key == 32 || key == 11)
            {
                return true;
            }
            else return false;
        }

        function numbersonly(e)
        {
            var unicode=e.charCode? e.charCode : e.keyCode;
            if(unicode!=8)
            {
                if(unicode>=48 && unicode <=57 || unicode == 9)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }

        function validateForm()
        {
            var flag=0;
            var fields="";
            var name = document.getElementById('name');
            var file = document.getElementById('file');

            if(name.value=="")
                fields=fields+"Name, ";
            else
                flag++;

            if(file.value=="")
                fields=fields+"Picture.";
            else
                flag++;


            if(flag==2) return true;
            else
            {
                document.getElementById('spanMsg').innerHTML = "Please correct the following field. "+fields;
                document.getElementById('spanMsg').style.background="#FFFFD7";
                return false;
            }
        }
    </script>
</head>
<body>
<?php include 'include/header.php' ?>
<div id="msg" align="center"><?php if(isset($_GET['msg'])) { echo $_GET['msg']; } ?></div>
<div class="admin-greyBg">
    <div class="admin-wrapper">
        <div id="admin-leftNav" class="admin-fLeft">
            <?php include 'include/left_nav.php' ?>
        </div>
        <div id="admin-body" class="admin-fRight">
            <h1>Add Employee</h1>
            <p>Fields marked with an asterisk (<span class="redColor">*</span>) are required.</p>
            <span id="spanMsg"></span>
            <?php
            if(isset($_REQUEST['id']))
            {
            ?>
            <form action="update_user_confirm.php" method="post" enctype="multipart/form-data" name="frm1" style="margin:0; padding:0;" >
                <input type="hidden" value="<?=$_REQUEST['id']?>" name="id">
                <?php
                }else{
                ?>
                <form action="create_user_confirm.php" method="post" enctype="multipart/form-data" name="frm1" style="margin:0; padding:0;" >
                    <?php
                    }
                    ?>
                    <table border="0" cellspacing="4" cellpadding="4">
                        <tr>
                            <td><strong><span class="redColor">*</span> Name:</strong></td>
                            <td><input type="text" value="<?=($dataArr['sname'] ?? '')?>" name="name" id="name" autocomplete="off" class="admin-inputBox" maxlength="50" size="40" onkeypress="return validateQty(event);"/></td>
                        </tr>
                        <tr>
                            <td><strong><span class="redColor">*</span> User ID:</strong>(Without Spaces)</td>
                            <td><input type="text" value="<?=($dataArr['user_id'] ?? '')?>" name="user_id" id="user_id" autocomplete="off" class="admin-inputBox" maxlength="50" size="40" onkeypress="return validateQty(event);"/></td>
                        </tr>
                        <tr>
                            <td><strong><span class="redColor">*</span> Password:</strong> </td>
                            <td><input type="password" name="password" value="<?=($dataArr['password'] ?? '')?>" id="password" autocomplete="off" class="admin-inputBox" maxlength="50" size="40" onkeypress="return validateQty(event);"/></td>
                        </tr>
                        <tr>
                            <td><strong><span class="redColor">*</span> Phone #:</strong> </td>
                            <td><input type="text" name="phone" id="phone" value="<?=($dataArr['spno'] ?? '')?>" autocomplete="off" class="admin-inputBox" maxlength="50" size="40" onkeypress="return numbersonly(event);"/></td>
                        </tr>
                        <tr>
                            <td><strong><span class="redColor">*</span> Designation:</strong> </td>
                            <td><input type="text" name="designation" value="<?=($dataArr['sdesig'] ?? '')?>" id="designation" autocomplete="off" class="admin-inputBox" maxlength="50" size="40" onkeypress="return validateQty(event);"/></td>
                        </tr>
                        <tr>
                            <td><strong><span class="redColor">*</span> Salary:</strong></td>
                            <td><input type="text" name="salary" value="<?=($dataArr['ssalary'] ?? '0')?>" id="salary" autocomplete="off" class="admin-inputBox" maxlength="50" size="40" onkeypress="return numbersonly(event);"/></td>
                        </tr>
                        <tr>
                            <td><strong><span class="redColor">*</span> Service Charge %age:</strong> </td>
                            <td><input type="text" name="service_charge" value="<?=($dataArr['service'] ?? '0')?>" id="service_charge" autocomplete="off" class="admin-inputBox" maxlength="50" size="40" onkeypress="return numbersonly(event);"/></td>
                        </tr>
                        <tr>
                            <td><strong><span class="redColor">*</span> Address:</strong></td>
                            <td><textarea name="address" cols="50" rows="2" id="address" class="admin-inputBox"><?=($dataArr['saddress'] ?? '')?></textarea></td>
                        </tr>
                        <tr>
                            <td><strong><span class="redColor">*</span> Status:</strong></td>
                            <td>
                                <select name="status" id="status" class="admin-inputBox">
                                    <option value="1" <?=(isset($dataArr['staff_status']) && ($dataArr['staff_status'] == '1')?'selected=selected':'')?>> Active </option>
                                    <option value="0" <?=(isset($dataArr['staff_status']) && ($dataArr['staff_status'] == '0')?'selected=selected':'')?>> In-Active </option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><strong><span class="redColor">*</span>Upload Staff Image:</strong></td>
                            <td>
                                <label>
                                    <input type="file" name="file" id="file" class="admin-inputBox" accept="image/x-png, image/gif, image/jpeg/"/>
                                </label>
                                <?=(isset($dataArr['staff_image'])?'<img height=80 src=images/staff_images/'.$dataArr['staff_image']:'')?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong><span class="redColor">*</span>Upload CNIC:</strong></td>
                            <td>
                                <label>
                                    <input type="file" name="file1" id="file1" class="admin-inputBox" accept="image/x-png, image/gif, image/jpeg/"/>
                                </label>
                                <?=(isset($dataArr['staff_cnic'])?'<img height=80 src=images/staff_cnic/'.$dataArr['staff_cnic']:'')?>
                            </td>
                        </tr>
                        <tr>
                            <td><strong><span class="redColor">*</span>Role:</strong></td>
                            <td>
                                <select name="role" id="role" class="admin-inputBox">
                                    <option value="2" <?=(isset($dataArr['role_code']) && ($dataArr['role_code'] == '2')?'selected=selected':'')?>>Operator</option>
                                    <option value="1" <?=(isset($dataArr['role_code']) && ($dataArr['role_code'] == '1')?'selected=selected':'')?>>Admin</option>
                                </select>				</td>
                        </tr>

                        <tr>
                            <td colspan="2" align="center">
                                <input type="submit" id="submit" class="admin-button" value="Save"/>
                                <input type="reset" class="admin-button" value="Cancel">              	</td>
                        </tr>
                    </table>
                </form>
        </div>
        <br clear="all" />
    </div>
</div>
<?php include 'include/footer.php' ?>
</body>
</html>
