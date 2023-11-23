<?php
if ($_SESSION['userlevel'] > 9 ) {
    $tablename = 'tags' ;
    $id =  $_GET['id'];
    $theconnection = $conn;
    $folderName =  basename(__DIR__);
    include 'include/delete.php';
}
?>
 