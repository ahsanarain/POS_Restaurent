<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');

$query_rsMgtList = "SELECT * FROM customer ORDER BY customer_id ASC";
$rsMgtList = mysqli_query($cn, $query_rsMgtList) or die(mysqli_error($cn));
$row_rsMgtList = mysqli_fetch_assoc($rsMgtList);
$totalRows_rsMgtList = mysqli_num_rows($rsMgtList);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Customers</title>
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
            <h1>List of Saved Customers</h1>
            <form action="add_customer.php" method="post">
                <input type="submit" class="admin-button" value="Add Customer" style="margin-bottom:10px;" />
            </form>
            <table border="0" width="100%" cellspacing="0" cellpadding="0" class='table-hover'>
                <thead>
                <tr>
                    <td width="5%" class="admin-tbHdRow1">Srno</td>
                    <td width="25%" class="admin-tbHdRow1">Customer Name</td>
                    <td width="10%" class="admin-tbHdRow1" align="center">Phone #</td>
                    <td width="50%" class="admin-tbHdRow1">Address</td>
                    <td width="5%" class="admin-tbHdRow1" align="center">Edit</td>
                    <td width="5%" class="admin-tbHdRow1 admin-tbHdRow3" align="center">Delete</td>
                </tr>
                </thead>
                <tbody>
                <?php $srno=0; do { $srno++; ?>
                    <tr>
                        <td class="admin-tbRow1" valign="top"><?=$srno?></td>
                        <td class="admin-tbRow1" valign="top"><?php echo $row_rsMgtList['customer_name']; ?></td>
                        <td class="admin-tbRow1" valign="top"><?php echo $row_rsMgtList['customer_phone']; ?></td>
                        <td class="admin-tbRow1" valign="top"><?php echo $row_rsMgtList['customer_address']; ?></td>
                        <td class="admin-tbRow1" align="center" valign="top"><a href="edit_customer.php?id=<?PHP echo $row_rsMgtList['customer_id']; ?>" title="Edit" class="admin-edit"></a></td>
                        <td class="admin-tbRow1 admin-tbRow2"  valign="top"><a href="del_customer.php?id=<?PHP echo $row_rsMgtList['customer_id']; ?>" title="Delete" class="admin-del" onclick="return confirm('Are you sure you want to perform this action ?');"></a></td>
                    </tr>
                <?php } while ($row_rsMgtList = mysqli_fetch_assoc($rsMgtList)); ?>
                </tbody>
            </table>
            </form>
        </div>
        <br clear="all" />
    </div>
</div>
<?php include 'include/footer.php' ?>
</body>
</html>
<?php
mysqli_free_result($rsMgtList);
?>