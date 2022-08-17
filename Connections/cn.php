<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_cn = "localhost";
$database_cn = "eat_tandoori_db";
$username_cn = "root";
$password_cn = "root";
$cn = mysqli_connect($hostname_cn, $username_cn, $password_cn);

if(!$cn) die("Could not connect ".mysqli_error($cn));

mysqli_select_db($cn, $database_cn);
$root = "etrs";
$org_name="Eat Tandoori Restaurant";
$website = "#";
$org_address = 'your address goes here ';
$org_address .= '<br>';
$org_address .= '00000000, 1111111';
$ntn = '123456789';


$ttaaxx1 = 01;
$ttaaxx2 = 01;
$taxactivationdate = "03-03-2019";


?>
