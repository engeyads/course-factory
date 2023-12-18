<?php

ob_start(); 
date_default_timezone_set('Europe/Istanbul');

$getaction = 'fixold';
$getco = 1;
$getlimit =200;
$geturl = $_GET['url'] ?? null;
if ($_SERVER['HTTP_HOST']=='localhost' || $_SERVER['HTTP_HOST']=='mercuryt.mercury-training.local' || $_SERVER['HTTP_HOST']=='192.168.5.141'){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "agile4training_agile_ar";
    $DBweekname = 'week';
}else{
$servername = "localhost";
$username = "agile4training_agile_ar";
$password = "UNFO55)TWI+[";
$database = "agile4training_agile_ar";
$DBweekname = 'week';
}
include 'cronjob.php';
 
?>