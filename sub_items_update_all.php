<?php
    session_start();
include('Connections/cn.php');
require_once('sessions/mysessionscript.php');
include('lib/iq.php');
    $size = count($_POST['chk']);
 
    
    for($i=0; $i<$size; $i++){
        $arrMix = explode("~",$_POST['chk'][$i]);
        $sub_item_id = $arrMix[0];
        $price = $arrMix[1];
        $query = "UPDATE sub_items set price = '$price' WHERE sub_item_id = '$sub_item_id'";
        mysqli_query($cn, $query);
        
    }
   header("location:sub_items_update.php?msg=All+Prices+Updated+Successfully!!!!");
?>