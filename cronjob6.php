<?php

date_default_timezone_set('Europe/Istanbul');

$getaction = 'fixold';
$getco = 6;
$getlimit =200;
$geturl = $_GET['url'] ?? null;

$servername = "eurowingstraining.com";
$username = "eurowing_ew";
$password = '@jhe$P7juaSD';
$database = "eurowing_en";

include 'cronjob.php';
?>