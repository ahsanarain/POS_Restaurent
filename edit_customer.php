<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include('lib/iq.php');

$cus_id = $_GET['id'];
$mgtQry = mysqli_query($cn, "select * from customer where customer_id = '$cus_id'");
$mgtRow=mysqli_fetch_array($mgtQry);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?=$org_name?></title>
    <link href="css/styles.css" type="text/css" rel="stylesheet" />
    <script src="js/jquery.js"></script>
    <link href="css/jquery-ui.css" type="text/css" rel="stylesheet" />
    <script>
        $(document).ready(function()
        {
            $("#name").focus();
        });

        function validateQty(event)
        {
            var key = window.event ? event.keyCode : event.which;
            if (event.keyCode == 8 || event.keyCode == 46 || event.keyCode == 37 || event.keyCode == 39 || event.keyCode == 9)
            {
                return true;
            }
            else if ( key >= 65 && key <=90 || key>=97 && key <=122 || key == 32 || key == 11 || key == 46 || key == 95)
            {
                return true;
            }
            else return false;
        };

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
            var menuname = document.getElementById('menuname');
            var filename = document.getElementById('filename');

            if(name.value=="")
                fields=fields+"Name, ";
            else
                flag++;

            if(menuname.value=="")
                fields=fields+"Menu Name, ";
            else
                flag++;

            if(filename.value=="")
                fields=fields+"and File Name. ";
            else
                flag++;

            if(flag==3) return true;
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
<div class="admin-greyBg">
    <div class="admin-wrapper">
        <div id="admin-leftNav" class="admin-fLeft">
            <?php include 'include/left_nav.php' ?>
        </div>
        <div id="admin-body" class="admin-fRight">
            <h1>Edit Customer</h1>
            <form action="edit_customer_confirm.php?id=<?php echo $_GET['id']; ?>" method="post" enctype="multipart/form-data" style="margin:0; padding:0;">
                <table border="0" cellspacing="4" cellpadding="4">
                    <tr>
                        <td><strong> Name:</strong></td>
                        <td><input type="text" name="name" id="name" value="<?=$mgtRow['customer_name'];?>" class="admin-inputBox" maxlength="50" size="40" onkeypress="return validateQty(event);"/></td>
                    </tr>
                    <tr>
                        <td><strong>Phone #:</strong></td>
                        <td>
                            <input type="text" name="phone" id="phone" value="<?=$mgtRow['customer_phone'];?>" class="admin-inputBox" maxlength="50" size="40" onkeypress="return numbersonly(event);"/>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Address</strong></td>
                        <td>
                            <textarea name="address" id="address" class="admin-inputBox" cols="40" rows="3"><?=$mgtRow['customer_address'];?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="submit" id="submit" class="admin-button" value="Save" />
                            <input type="button" class="admin-button" value="Cancel" onclick="javascript:history.back(1);" />				</td>
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
