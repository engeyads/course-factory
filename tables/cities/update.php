<?php
if ($_SESSION['userlevel'] > 2 ) {
    $path = dirname(__FILE__);
    include $path.'/conf.php';   
    if (!isset($_POST['monday']) ){ $_POST['monday'] = '0'; }else{ if($_POST['monday'] !='1'){$_POST['monday'] = '0';} }
    if (!isset($_POST['x']) ){ $_POST['x'] = '0'; }else{ if(!is_numeric($_POST['x'])){ $_POST['x'] = '0'; } }
    if (!isset($_POST['x_b']) ){ $_POST['x_b'] = '0'; }else{ if(!is_numeric($_POST['x_b'])){ $_POST['x_b'] = '0'; } } 
    if (!isset($_POST['x_c']) ){ $_POST['x_c'] = '0'; }else{ if(!is_numeric($_POST['x_c'])){ $_POST['x_c'] = '0'; } }
    if (isset($_POST['keyword']) && $_POST['keyword']!=NULL){$_POST['keyword'] = TextasJsonArray($_POST['keyword']);}
    include 'include/update.php';
    // will get $urlback from include/update.php after update or insert query
    header("Refresh: 0; url=" . $urlback);
    exit;
}
?>