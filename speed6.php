<?php

ob_start(); 
date_default_timezone_set('Europe/Istanbul');

$getaction = 'speed_cron';
$getco = 6;
$getlimit =200;
$geturl = 'eurowingstraining.com';
if ($_SERVER['HTTP_HOST']=='localhost' || $_SERVER['HTTP_HOST']=='mercuryt.mercury-training.local' || $_SERVER['HTTP_HOST']=='192.168.5.141'){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "eurowing_en";
    $DBweekname = 'weeks';
}else{
$servername = "eurowingstraining.com";
$username = "eurowing_ew";
$password = '@jhe$P7juaSD';
$database = "eurowing_en";
$DBweekname = 'weeks';
}
include 'cronjob.php';
 
?>