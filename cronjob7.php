<?php

date_default_timezone_set('Europe/Istanbul');

$getaction = 'fixold';
$getco = 6;
$getlimit =200;
$geturl = $_GET['url'] ?? null;

$servername = "eurowingstraining.com";
$username = "eurowings_ew_ar";
$password = '97[UXwFLM@0,';
$database = "eurowing_ar";

include 'cronjob.php';
?>