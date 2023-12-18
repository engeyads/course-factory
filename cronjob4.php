<?php

date_default_timezone_set('Europe/Istanbul');

$getaction = 'fixold';
$getco = 4;
$getlimit =200;
$geturl = $_GET['url'] ?? null;
if ($_SERVER['HTTP_HOST']=='localhost' || $_SERVER['HTTP_HOST']=='mercuryt.mercury-training.local' || $_SERVER['HTTP_HOST']=='192.168.5.141'){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "mercuryt_newar";
    $DBweekname = 'week';
}else{
$servername = "mercury-training.com";
$username = "mercurytraining_ar";
$password = "fsdfsdf$!@212@sSAA";
$database = "mercuryt_newar";
$DBweekname = 'week';
}
include 'cronjob.php';
?>