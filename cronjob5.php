<?php

date_default_timezone_set('Europe/Istanbul');

$getaction = 'fixold';
$getco = 5;
$getlimit =200;
$geturl = $_GET['url'] ?? null;
if ($_SERVER['HTTP_HOST']=='localhost' || $_SERVER['HTTP_HOST']=='mercuryt.mercury-training.local' || $_SERVER['HTTP_HOST']=='192.168.5.141'){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "blackbirduk_uk";
    $DBweekname = 'week';
}else{
$servername = "blackbird-training.co.uk";
$username = "blackbirduk_uk";
$password = "qwer1234!@#$";
$database = "blackbirduk_uk";
$DBweekname = 'week';
}
include 'cronjob.php';
?>