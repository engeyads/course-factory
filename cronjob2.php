<?php


date_default_timezone_set('Europe/Istanbul');

$getaction = 'fixold';
$getco = 2;
$getlimit =200;
$geturl = $_GET['url'] ?? null;

$servername = "blackbird-training.com";
$username = "blackbird_bl";
$password = "1aQ*yO@^36u5Mrzx";
$database = "blackbird_bl";

include 'cronjob.php';

?>