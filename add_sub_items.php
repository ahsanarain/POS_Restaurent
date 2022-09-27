<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include('lib/iq.php');

$query_rsauto = "SELECT upper(keyword_name) as exp_desc FROM keywords_ing where status = '1'";
$row_rsauto = mysqli_query($cn, $query_rsauto) or die(mysqli_error($cn));
$arrAuto=array();
while($data= mysqli_fetch_assoc($row_rsauto)) $arrAuto[] = $data['exp_desc'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?=$org_name?></title>
    <script src="js/jquery.js"></script>
    <script src="js/jquery-ui.js"></script>
    <link href="css/jquery-ui.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="css/jquery.css">

    <script language="JavaScript" type="text/javascript" src="js/tiny_mce.js"></script>
    <script language="JavaScript" type="text/javascript" src="js/custom_config.js"></script>
    <link href="css/styles.css" type="text/css" rel="stylesheet" />

    <script>
        $(document).on('click','.add',function()
        {
            $('.details tbody>tr:last').clone(true).insertAfter('.details tbody>tr:last');
            $('.details tbody>tr:last').children().find('.itm').val('');
            $('.details tbody>tr:last').children().find('.amt').val('');
        });

        $(document).on('click','.minus',function()
        {
            var row = $(this).parent().parent().index();
            if(row != 0)
            {
                $(this).parent().parent().remove();
            }
        });

        $(document).on('click','#save_ing',function()
        {
            var chk=0;
            $(".itm").each(function()
            {
                if($(this).val()=='')
                {
                    chk=1;
                    return false;
                }
            });

            if(chk==1)
            {
                alert("Can't Store Null Value to the database.");
                return false;
            }
            else
            {
                $("#frmdetail").submit();
            }

        });

        $(document).on('click','#save_deal',function()
        {
            var chk=0;
            $(".qty").each(function()
            {
                if($(this).val()=='')
                {
                    chk=1;
                    return false;
                }
            });

            if(chk==1)
            {
                alert("Can't Store Null Value to the database.");
                return false;
            }
            else
            {
                $("#frmdeal").submit();
            }
        });

        $(document).on('click','.addd',function()
        {
            $('.deal tbody>tr:last').clone(true).insertAfter('.deal tbody>tr:last');
            $('.deal tbody>tr:last').children().find('.qty').val('');
        });

        $(document).on('click','.minusd',function()
        {
            var row = $(this).parent().parent().index();
            if(row != 0)
            {
                $(this).parent().parent().remove();
            }
        });
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
            <h1>Add Sub Menu Item </h1>
            <table border="0" width="100%" align="center">
                <tr>
                    <td align="left" valign="top">
                        <table width="100%" border="0">
                            <tr><td align="left" valign="top">
                                    <form action="add_sub_items_confirm.php" method="post" enctype="multipart/form-data" style="margin:0; padding:0;">
                                        <table border="0" cellspacing="4" cellpadding="4">
                                            <tr>
                                                <td><strong>Name:</strong></td>
                                                <td><input type="text" name="name" id="date" class="admin-inputBox" size="40" value="" /></td>
                                            </tr>
                                            <tr>
                                                <td><strong><span class="redColor">*</span> Category:</strong></td>
                                                <td>
                                                    <?php
                                                    $query_rsMgtList = "SELECT * FROM items where item_status = '1' ORDER BY item_id ASC";
                                                    $rsMgtList = mysqli_query($cn, $query_rsMgtList) or die(mysqli_error($cn));
                                                    $row_rsMgtList = mysqli_fetch_assoc($rsMgtList);
                                                    $totalRows_rsMgtList = mysqli_num_rows($rsMgtList);
                                                    ?>
                                                    <select name='category' id='category' class="admin-inputBox">
                                                        <?php do { ?>
                                                            <option value='<?=$row_rsMgtList['item_id']; ?>'><?=$row_rsMgtList['item_name']; ?></option>
                                                        <?php } while ($row_rsMgtList = mysqli_fetch_assoc($rsMgtList)); ?>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Price:</strong></td>
                                                <td><input type="text" name="price" id="price" class="admin-inputBox" size="40" value=""></td>
                                            </tr>
                                            <tr>
                                                <td align="left" valign="top"><strong>Image:</strong></td>
                                                <td><input type="file" name="file" class="admin-inputBox" accept="image/x-png, image/gif, image/jpeg/"/></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Status:</strong></td>
                                                <td><select name="status" id="status" class="admin-inputBox">
                                                        <option value="1">Publish</option>
                                                        <option value="0"> Un-Publish</option>
                                                    </select></td>
                                            </tr>
                                            <tr>
                                                <td><strong>For Deals Only:</strong></td>
                                                <td>
                                                    <textarea name="dealdetails"  cols="40" rows="4" class="admin-inputBox"></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                    <input type="submit" id="submit" class="admin-button" value="Save" />
                                                    <input type="button" class="admin-button" value="Cancel" onclick="javascript:history.back(1);" />
                                                </td>
                                            </tr>
                                    </form>
                                </td></tr></table>
                    </td>
                </tr>
            </table>
            </table>
        </div>
        <br clear="all" />
    </div>
</div>
<?php include 'include/footer.php' ?>
</body>
</html>
