<?php
    $path = dirname(__FILE__);
    include $path.'/conf.php';   
    $_POST['id'] = $_SESSION['user_id'];
    $_POST['password'] = md5($_POST['password']);
    include 'include/update.php';
    // will get $urlback from include/update.php after update or insert query
    header("Refresh: 1; url=" . $url.'profile/edit');
    exit;
?>