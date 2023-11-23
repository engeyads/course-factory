<?php
print_r($_SESSION);
if(isset($_SESSION['db'])){
    if(isset($conn2)){
        createAdminLogTable($conn2);
        checkTableColumns('seo', $conn2);
    }
}
?>