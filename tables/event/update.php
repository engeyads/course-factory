<?php
if ($_SESSION['userlevel'] > 2 ) {
    $path = dirname(__FILE__);
    include $path.'/conf.php';   
    //$_POST['c_id'] = $_GET['c_id'];
    if (isset($_POST['keyword']) && $_POST['keyword']!=NULL){$_POST['keyword'] = TextasJsonArray($_POST['keyword']);}
    include 'include/update.php';
    // will get $urlback from include/update.php after update or insert query
    header("Refresh: 1; url=" . $urlback);
    exit;
}
?>