<script src="js/script.js"></script>
<?php
    if(!isset($_SESSION))
        session_start();
     
    $sqlRights = "SELECT
                a.file_name,a.function_name,a.menu_name,a.target,a.menu_head

                FROM
                res_functions a,
                staff_priv b

                WHERE
                a.function_code = b.function_code
                and b.sid = '".$_SESSION['sid']."' order by a.srno asc";
    $result = mysqli_query($cn, $sqlRights);
    $rightsArr = array();
    while($rightsData = mysqli_fetch_assoc($result)){
            $rightsArr[] = $rightsData;
    }
    
    $finalArr = array();
    foreach($rightsArr as $key => $arr)
        $finalArr[$arr['menu_head']][] = $arr;
   
?>
<div id="cssmenu">
    <ul>
        <li class="has-sub">
            <a href="#">
                <span><?=$org_name?> Menu</span>
            </a>
            <ul>
                <li class="last">
                    <a href="cms.php">
                        <span>Dashboard</span>
                    </a>
                </li>
            </ul>
        </li>
        <?php
        foreach($finalArr as $key => $arr){
        ?>
        <li class="has-sub">
            <a href="#">
                <span><?=$key?></span>
            </a>
           
            <ul>
                 <?php foreach($arr as $sub){?>
                <li>
                    <a href="<?=$sub['file_name']?>" target="<?=$sub['target']?>">
                        <span><?=$sub['menu_name']?></span>
                    </a>
                </li>
                <?php } ?>
            </ul>
            
        </li>
		 <li class="line"></li>
        <?php
        }
        ?>
        <li class="has-sub">
            <a href="#">
                <span>Other Operations</span>
            </a>
            <ul>
                <li class="last">
                    <a href="change.php">
                        <span>Change Password</span>
                    </a>
                </li>
                <li class="last">
                    <a href="logout.php">
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</div>