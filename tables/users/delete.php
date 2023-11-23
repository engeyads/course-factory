<?php
if ($_SESSION['userlevel'] > 9 ) {
    $path = dirname(__FILE__);
    include $path.'/conf.php';   
    include 'include/delete.php';
}
?>
 