<?php
$restype = 'Trash';
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
// Select the existing record to check if deleted_at is NULL
$query = "SELECT `deleted_at`".($tablename != 'course' ? ",`name`" : ",`c_id`")." FROM `$tablename` WHERE `$tablename`.`id` = $id";
$result = mysqli_query($theconnection, $query);
$row = mysqli_fetch_assoc($result);
if(isset( $row['c_id'])){
    $c_id = $row['c_id'];
}else{
    $c_id = '';
}
if($tablename == 'course'){
    $queryc = "SELECT `name` FROM `course_main` WHERE `c_id` = ".$c_id;
    $resultc = mysqli_query($theconnection, $queryc);
    $rowc = mysqli_fetch_assoc($resultc);
    $row['name'] = $rowc['name'];
}
$trashed = '';
$thename = $row['name'];
if ($row['deleted_at'] === NULL) {
    // If deleted_at is NULL, set it to now()
    $res['un'] = false; // Set un to true
    $query = "UPDATE `$tablename` SET `deleted_at` = now(), `updated_at` = now() WHERE `$tablename`.`id` = $id";
    $newData = 'The Row '. $id . ' in Table ' . $tablename . ' has been trashed';
    $trashed = 'trashed';
} else {
    $res['un'] = true; // Set un to true
    // If deleted_at is not NULL, set it to NULL
    $query = "UPDATE `$tablename` SET `deleted_at` = NULL, `updated_at` = now() WHERE `$tablename`.`id` = $id";
    $newData = 'The Row '. $id . ' in Table ' . $tablename . ' has been Un trashed';
    $trashed = 'Un trashed';
}
$result = mysqli_query($theconnection, $query);
if ($result) {
    $res['type'] = $restype;
    $res['success'] = true;
    $res['message'] = $trashed.' successful.';
} else {
    $res['type'] = $restype;
    $res['success'] = false;
    $res['message'] = $trashed.' failed.<br> ' . 'Error: ' . $query . '<br>' . mysqli_error($theconnection);
}
$logSql = "INSERT INTO admin_log (user,row_id, tablename, columnname, oldData, newData, action, date) 
VALUES ('".$username."','$id', '$tablename', '', '', '$newData', 'Trash', NOW())";
mysqli_query($theconnection, $logSql);
$link = "<a href=".$url.$newtablename."/edit/".($newtablename == 'event' ? $c_id ."/".$id : $id ).">Click here</a> to view";
echo $notificationSql = "INSERT INTO notifications (`user_id`, `message`, `uid`, `status`, `created_at`) VALUES ('$userId', '$username has $trashed $id $thename in $newtablename in $databasenm\n\n$link', '$userId', 'unread', NOW())";
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
        $insertSql = "INSERT INTO notifications (`user_id`, `message`, `uid`, `status`, `created_at`) VALUES ('$adminUserId', '$username has $trashed $id $thename in $newtablename in $databasenm\n\n$link', '$userId', 'unread', NOW())";
        mysqli_query($conn, $insertSql);
    }
// if ($result) {
//     // header("Location: $url"."$folderName"."/"."$viewslug");
// } else {
//     echo "Error: " . $query . "<br>" . mysqli_error($theconnection);
// }



// Clear any previously generated output
ob_clean();
// Set the appropriate headers
header('Content-Type: application/json');
echo json_encode($res);
// Flush the output buffer
ob_flush();
exit();
?>
