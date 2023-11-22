<?php

echo $id.' '.$tablename;
$restype = 'Delete';
$query = "DELETE FROM `$tablename` WHERE `$tablename`.`id` = $id";
$result = mysqli_query($theconnection, $query);
$newData = 'The Row '. $id . ' in Table ' . $tablename . ' has been Deleted';
$logSql = "INSERT INTO admin_log (user,row_id, tablename, columnname, oldData, newData, action, date) 
VALUES ('".$_SESSION['username']."','$id', '$tablename', '', '', '$newData', 'Delete', NOW())";
mysqli_query($theconnection, $logSql);
if ($result) {
    $response['type'] = $restype;
    $response['success'] = true;
    $response['message'] = $restype.' successful.';
} else {
    $response['type'] = $restype;
    $response['success'] = false;
    $response['message'] = $restype.' failed.<br> ' . 'Error: ' . $query . '<br>' . mysqli_error($theconnection);
}

// Clear any previously generated output
ob_clean();
// Set the appropriate headers
header('Content-Type: application/json');
echo json_encode($response);
// Flush the output buffer
ob_flush();
exit();
?>