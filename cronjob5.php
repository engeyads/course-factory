<?php

date_default_timezone_set('Europe/Istanbul');

$getaction = 'fixold';
$getco = 5;
$getlimit =200;
$geturl = $_GET['url'] ?? null;

$servername = "blackbird-training.co.uk";
$username = "blackbirduk_uk";
$password = "qwer1234!@#$";
$database = "blackbirduk_uk";

include 'cronjob.php';
?>