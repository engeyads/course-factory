<?php

date_default_timezone_set('Europe/Istanbul');

$getaction = 'fixold';
$getco = 4;
$getlimit =200;
$geturl = $_GET['url'] ?? null;

$servername = "mercury-training.com";
$username = "mercurytraining_ar";
$password = "fsdfsdf$!@212@sSAA";
$database = "mercuryt_newar";

include 'cronjob.php';
?>