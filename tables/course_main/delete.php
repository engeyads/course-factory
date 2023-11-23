<?php
if ($_SESSION['userlevel'] > 9 ) {
    echo 'this should delete the course and all related data';
    exit;
    $path = dirname(__FILE__);
    include $path.'/conf.php';   
    include 'include/delete.php';
}
?>