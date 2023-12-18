<?php

ob_start(); 
date_default_timezone_set('Europe/Istanbul');

$getaction = 'google_cron';
$getco = 7;
$getlimit =200;
$geturl = 'eurowingstraining.com';
if ($_SERVER['HTTP_HOST']=='localhost' || $_SERVER['HTTP_HOST']=='mercuryt.mercury-training.local' || $_SERVER['HTTP_HOST']=='192.168.5.141'){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "eurowings_ar";
    $DBweekname = 'weeks';
}else{
$servername = "eurowingstraining.com";
$username = "eurowings_ew_ar";
$password = '97[UXwFLM@0,';
$database = "eurowings_ar";
$DBweekname = 'weeks';
}
include 'cronjob.php';
 
?>