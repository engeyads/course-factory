<?php
$restype = 'Trash';
// Select the existing record to check if deleted_at is NULL
$query = "SELECT `deleted_at` FROM `$tablename` WHERE `$tablename`.`id` = $id";
$result = mysqli_query($theconnection, $query);
$row = mysqli_fetch_assoc($result);

if ($row['deleted_at'] === NULL) {
    // If deleted_at is NULL, set it to now()
    $res['un'] = false; // Set un to true
    $query = "UPDATE `$tablename` SET `deleted_at` = now(), `updated_at` = now() WHERE `$tablename`.`id` = $id";
    $newData = 'The Row '. $id . ' in Table ' . $tablename . ' has been trashed';
} else {
    $res['un'] = true; // Set un to true
    // If deleted_at is not NULL, set it to NULL
    $query = "UPDATE `$tablename` SET `deleted_at` = NULL, `updated_at` = now() WHERE `$tablename`.`id` = $id";
    $newData = 'The Row '. $id . ' in Table ' . $tablename . ' has been Un trashed';
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
$logSql = "INSERT INTO admin_log (user,row_id, tablename, columnname, oldData, newData, action, date) 
VALUES ('".$_SESSION['username']."','$id', '$tablename', '', '', '$newData', 'Trash', NOW())";
mysqli_query($theconnection, $logSql);
 
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
