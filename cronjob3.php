<?php


date_default_timezone_set('Europe/Istanbul');

$getaction = 'fixold';
$getco = 3;
$getlimit =200;
$geturl = $_GET['url'] ?? null;

$servername = "mercury-training.com";
$username = "mercurytraining_mct";
$password = "kkfRleRU2NoY";
$database = "mercuryt_newen";

include 'cronjob.php';

?>


{"status":200,"0":[{"db":"agile4training_agile","message":"All events are up to date."}],"last_updated":"2023-11-02 11:38:23","last_check":"2023-11-02 11:38:23"}{"status":200,"0":[{"db":"agile4training_agile","message":"All events are up to date."},{"db":"blackbird_bl","message":"All events are up to date."}],"last_updated":"2023-11-02 11:38:23","last_check":"2023-11-02 11:38:23"}
{"status":200,"0":[{"db":"agile4training_agile","message":"All events are up to date."},