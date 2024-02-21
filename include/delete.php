<?php
$restype = 'Delete';
// Select the existing record to check if it exists
$query = "SELECT *".($tablename != 'course' ? ($tablename != 'seo' ? ",`name`" : ",`link`") : ",`c_id`")."  FROM `$tablename` WHERE `$tablename`.`id` = $id";
$result = mysqli_query($theconnection, $query);
$row = mysqli_fetch_assoc($result);
if($tablename == 'course'){
    $queryc = "SELECT `name` FROM `course_main` WHERE `c_id` = ".$row['c_id'];
    $resultc = mysqli_query($theconnection, $queryc);
    $rowc = mysqli_fetch_assoc($resultc);
    $row['name'] = $rowc['name'];
}elseif($tablename == 'seo'){
    $queryc = "SELECT `link` FROM `seo` WHERE `id` = ".$row['id'];
    $resultc = mysqli_query($theconnection, $queryc);
    $rowc = mysqli_fetch_assoc($resultc);
    $row['name'] = $rowc['link'];
}
if(isset( $row['c_id'])){
    $c_id = $row['c_id'];
}else{
    $c_id = '';
}
if ($row) {
    $userId = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $databasenm = $_SESSION['db_name'];
    switch($tablename){
        case 'course':
            $newtablename = 'event';
            break;
        case 'course_main':
            $newtablename = 'courses';
            break;
        case 'course_c':
            $newtablename = 'categories';
            break;
        case 'cities':
            $newtablename = 'cities';
            break;
    }
    // If the record exists, perform the delete operation
    $query = "DELETE FROM `$tablename` WHERE `$tablename`.`id` = $id";
    $newData = 'The Row ' . $id . ' in Table ' . $tablename . ' has been deleted';
    $thename = $row['name'];
    $logSql = "INSERT INTO admin_log (user, row_id, tablename, columnname, oldData, newData, action, date) 
    VALUES ('".$_SESSION['username']."','$id', '$tablename', '', '', '$newData', 'Delete', NOW())";
    mysqli_query($theconnection, $logSql);
    $link = "<a href=".$url.$newtablename."/edit/".($newtablename == 'event' ? $c_id ."/".$id : $id ).">Click here</a> to view";
    echo $notificationSql = "INSERT INTO notifications (`user_id`, `message`, `uid`, `status`, `created_at`) VALUES ('$userId', '$username has deleted $id $thename in $newtablename in $databasenm', '$userId', 'unread', NOW())";
    mysqli_query($conn, $notificationSql);
    $adminSql = "SELECT id FROM users WHERE userlevel = 10";
    $adminResult = mysqli_query($conn, $adminSql);
    $adminUserIds = array();

    if ($adminResult && mysqli_num_rows($adminResult) > 0) {
        while ($adminRow = mysqli_fetch_assoc($adminResult)) {
            if($adminRow['id'] != $userId){
                $adminUserIds[] = $adminRow['id'];
            }
        }
    }

    // Insert the notification for admin users
    foreach ($adminUserIds as $adminUserId) {
        $insertSql = "INSERT INTO notifications (`user_id`, `message`, `uid`, `status`, `created_at`) VALUES ('$adminUserId', '$username has deleted $id $thename in $newtablename in $databasenm', '$userId', 'unread', NOW())";
        mysqli_query($conn, $insertSql);
    }
} else {
    // If the record doesn't exist, set appropriate response
    $res['type'] = $restype;
    $res['success'] = false;
    $res['message'] = 'Record not found.';
    
    // Clear any previously generated output
    ob_clean();
    
    // Set the appropriate headers
    header('Content-Type: application/json');
    
    // Echo the JSON response
    echo json_encode($res);
    
    // Flush the output buffer
    ob_flush();
    exit();
}

$result = mysqli_query($theconnection, $query);
if ($result) {
    $res['type'] = $restype;
    $res['success'] = true;
    $res['message'] = $restype.' successful.';
} else {
    $res['type'] = $restype;
    $res['success'] = false;
    $res['message'] = $restype.' failed.<br> ' . 'Error: ' . $query . '<br>' . mysqli_error($theconnection);
}


// Clear any previously generated output
ob_clean();

// Set the appropriate headers
header('Content-Type: application/json');

// Echo the JSON response
echo json_encode($res);

// Flush the output buffer
ob_flush();
exit();
?>
