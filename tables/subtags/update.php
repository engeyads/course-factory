<?php
if ($_SESSION['userlevel'] > 2 ) {

    $tablename = 'subtags';
    $tabletitle = 'Sub Tags';  
    $folderName =  'keyword';
    $theconnection = $conn;
    $editslug = 'viewall';
    $viewslug = 'viewall';
    $restype = '';
    $change = false;
    include 'include/update.php';
}
?>