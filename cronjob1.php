<?php

ob_start(); 
date_default_timezone_set('Europe/Istanbul');

$getaction = 'fixold';
$getco = 1;
$getlimit =200;
$geturl = $_GET['url'] ?? null;

$servername = "localhost";
$username = "agile4training_agile";
$password = "EViSr)1J6%R4";
$database = "agile4training_agile";

include 'cronjob.php';
 
?>