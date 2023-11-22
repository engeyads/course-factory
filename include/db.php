<?php
if ($_SERVER['HTTP_HOST']=='localhost' || $_SERVER['HTTP_HOST']=='192.168.5.141'){
    $url = 'http://'.$_SERVER['HTTP_HOST'].'/course-factory/';
    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = '';
    $db_name = 'admin';
    $conn1dbname = 'admin';
}else {
    $db_host = 'localhost';
    $db_user = 'mercuryt_coursef';
    $db_password = '@I9o4!!!oZ0Z$LXP';
    $db_name = 'mercuryt_cf';
    $conn1dbname = 'mercuryt_cf';
    $url = 'https://mercuryt.mercury-training.com/course-factory/';
}

// Create connection
$conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


if (isset($_SESSION['db']) && !empty($_SESSION['db']) || isset($db) && !empty($db)) {
 
    $db_id = $_SESSION['db'] ?? $db['id'];

     $query = "SELECT * FROM db WHERE id = $db_id";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $db = mysqli_fetch_assoc($result);
        $db2_host = $db['db_host'];
        $db2_user = $db['db_username'];
        $db2_password = $db['db_password'];
        $db2_port = $db['db_port'];
        $db2_name = $db['db_name'];
        $_SESSION['db_name'] = $db['name'];
        if ($db2_port == NULL) {
            $db2_port = 3306;
        }
    }
    $conn2 = mysqli_connect($db2_host, $db2_user, $db2_password, $db2_name, $db2_port);
    mysqli_query($conn2, "SET SESSION time_zone = '+03:00'");
    mysqli_query($conn2, "SET NAMES 'utf8'");

    if (!$conn2) {
        die("Connection failed: " . mysqli_connect_error());
    }
}
?>