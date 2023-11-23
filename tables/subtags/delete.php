<?php
if ($_SESSION['userlevel'] > 9 ) {
    $tablename = 'subtags' ;
    $id =  $_GET['id'];
    $theconnection = $conn;
    $folderName =  basename(__DIR__);
    include 'include/delete.php';
}
?>
 