<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include('lib/iq.php');
if(!empty($_POST)){

    $sub_item_id = $_POST['sub_item_id'];
    $item_id = $_POST['item_id'];
    $size = count($_POST['subitem']);
        for($i=0; $i<$size; $i++){
            $subitem = $_POST['subitem'][$i];
            $qty = $_POST['qty'][$i];
            $sqlInsert = "insert into deal
                            (item_id,sub_item_id,subitem,qty)
                            values
                            ('$item_id','$sub_item_id','$subitem','$qty')";
            mysqli_query($cn, $sqlInsert);
        }
        header("location:edit_sub_items.php?msg=Deals+Sub+Item+Saved+Successfully.&id=$sub_item_id");
}

?>