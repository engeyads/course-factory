<?php


date_default_timezone_set('Europe/Istanbul');

$getaction = 'fixold';
$getco = 2;
$getlimit =200;
$geturl = $_GET['url'] ?? null;
if ($_SERVER['HTTP_HOST']=='localhost' || $_SERVER['HTTP_HOST']=='mercuryt.mercury-training.local' || $_SERVER['HTTP_HOST']=='192.168.5.141'){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "blackbir_bloomberg";
    $DBweekname = 'weeks';
}else{
$servername = "blackbird-training.com";
$username = "blackbird_bl";
$password = "1aQ*yO@^36u5Mrzx";
$database = "blackbird_bl";
$DBweekname = 'weeks';
}
include 'cronjob.php';

?>