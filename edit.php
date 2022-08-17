<?php
session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include('lib/iq.php');

if(!empty($_POST)){
    $sub_item_id = $_POST['sub_item_id'];
    $size = count($_POST['item']);
        for($i=0; $i<$size; $i++){
            $item = $_POST['item'][$i];
            $amt = $_POST['amt'][$i];
            $unit = $_POST['unit'][$i];
            $sqlInsert = "insert into sub_item_ing
                            (sub_item_id,item,amt,unit)
                            values
                            ('$sub_item_id','$item','$amt','$unit')";
            mysqli_query($cn, $sqlInsert);
        }
        header("location:edit_sub_items.php?msg=Ingrediants+Saved+Successfully.&id=$sub_item_id");
       
}

?>