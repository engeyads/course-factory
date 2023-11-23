<?php
if ($_SESSION['userlevel'] > 2 ) {

    $tablename = 'tags';
    $tabletitle = 'Tags';  
    $folderName =  basename(__DIR__);
    $theconnection = $conn;
    $editslug = 'edit';
    $viewslug = 'view';
    $restype = '';
    $change = false;
    include 'include/update.php';
}
?>