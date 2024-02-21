<?php
$Columns = GetTableColumns($tablename,$theconnection);
if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
    $userId = $_SESSION['user_id'];
    $databasenm = $_SESSION['db_name'];
}
if(isset($cronjob)){
    $cron = true;
    $username = 'cronjob';
}else{
    $cron = false;
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
}
// $cid= '';
if(isset($_POST['c_id'])){
    $cid= $_POST['c_id'];
}
if (isset($_POST['id'])) {
    $restype = 'Update';
    $id = htmlspecialchars($_POST['id']);
    if(isset($_POST['sitekeywords1'])){
        $inputKeywords = $_POST["sitekeywords1"];

        // Remove leading and trailing whitespace from each keyword
        $cleanedKeywords = array_map('trim', $inputKeywords);

        // Course Main ID from GET parameter (you can change this to your desired source)
        $courseMainID = $id; // Example value; replace with your source

        // Retrieve existing keyword associations for the given course_main_id
        $existingKeywords = array();

        $retrieveSql = "SELECT sitekeywords_id FROM sitekeywords_coursemain WHERE course_main_id = '$courseMainID'";
        mysqli_set_charset($theconnection, "utf8");
        $retrieveResult = mysqli_query($theconnection, $retrieveSql);

        while ($row = mysqli_fetch_assoc($retrieveResult)) {
            $existingKeywords[] = $row['sitekeywords_id'];
        }

        // Process the keywords and insert or skip duplicates
        foreach ($cleanedKeywords as $keyword) {
            // Check if the keyword already exists in the sitekeywords table
            $checkSql = "SELECT id FROM sitekeywords WHERE name = '$keyword'";
            mysqli_set_charset($theconnection, "utf8");
            $checkResult = mysqli_query($theconnection, $checkSql);

            if ($num_rows = mysqli_num_rows($checkResult) === 0) {
                // Keyword does not exist in sitekeywords, so insert it
                $insertSql = "INSERT INTO sitekeywords (name) VALUES ('$keyword')";
                $insertStmt = mysqli_query($theconnection, $insertSql);
                if ($insertStmt) {
                    // Insert was successful, get the newly inserted keyword ID
                    $sitekeywords_id = mysqli_insert_id($theconnection);
                } else {
                    echo "Error inserting data into sitekeywords: " . mysqli_error($theconnection);
                    continue; // Skip to the next keyword if insertion fails
                }
            } else {
                // Keyword exists, fetch its ID
                $keywordRow = mysqli_fetch_assoc($checkResult);
                $sitekeywords_id = $keywordRow['id'];
            }

            // Check if the combination of course_main_id and sitekeywords_id already exists
            $checkDuplicateSql = "SELECT id FROM sitekeywords_coursemain WHERE course_main_id = '$courseMainID' AND sitekeywords_id = '$sitekeywords_id'";
            mysqli_set_charset($theconnection, "utf8");
            $checkDuplicateResult = mysqli_query($theconnection, $checkDuplicateSql);

            if ($num_rows = mysqli_num_rows($checkDuplicateResult) === 0) {
                // Combination of course_main_id and sitekeywords_id does not exist, so insert it
                $insertSql = "INSERT INTO sitekeywords_coursemain (course_main_id, sitekeywords_id) VALUES ('$courseMainID','$sitekeywords_id' )";
                mysqli_set_charset($theconnection, "utf8");
                $insertStmt = mysqli_query($theconnection, $insertSql);
                if ($insertStmt) {
                    //echo "Keyword '$keyword' has been associated with course_main_id $courseMainID.<br>";
                } else {
                    echo "Error inserting data into sitekeywords_coursemain: ".mysqli_error($theconnection);
                }
            }

            // Remove the keyword from the existingKeywords array
            $keyToDelete = array_search($sitekeywords_id, $existingKeywords);
            if ($keyToDelete !== false) {
                unset($existingKeywords[$keyToDelete]);
            }
        }

        // Delete keywords from sitekeywords_coursemain that are not in the cleanedKeywords array
        if (!empty($existingKeywords)) {
            $deleteSql = "DELETE FROM sitekeywords_coursemain WHERE course_main_id = '$courseMainID' AND sitekeywords_id IN (" . implode(',', $existingKeywords) . ")";
            mysqli_set_charset($theconnection, "utf8");
            $deleteResult = mysqli_query($theconnection, $deleteSql);
            if (!$deleteResult) {
                echo "Error deleting data from sitekeywords_coursemain: ".mysqli_error($theconnection);
            }
        }
    }
        
    if (in_array('s_alias', $Columns)) {
        if (isset($_POST['s_alias']) && $_POST['s_alias'] == '') {
            $_POST['s_alias'] = generateSlug($_POST['name'] );
        }else{
            $_POST['s_alias'] = generateSlug($_POST['s_alias'] );
        }
    }
    $ROW = GetRow($id, 'id',  $tablename, $theconnection);
    $dt = '';
    foreach ($_POST as $key => $value) {
        if (array_key_exists($key, $ROW) && $ROW[$key] != $value && $key != 'id') {
            if ($value == '') {
                $value = 'NULL';
                $newData = $value;
            } else {
                $value = isset($_POST[$key]) ? "'" . mysqli_real_escape_string($theconnection, $_POST[$key]) . "'" : 'NULL';
                $newData = "'" . mysqli_real_escape_string($theconnection, $value) . "'";
            }
            $change = true;
            $sql = "UPDATE $tablename SET $key = $value, updated_at = '$currentDateTime' WHERE id = '$id'";
            mysqli_query($theconnection, $sql);
            $oldData = ($ROW[$key] === '') ? 'NULL' : "'" . ($ROW[$key] !== null ? mysqli_real_escape_string($theconnection, $ROW[$key]) : '') . "'";
            
            if($key == 'password'){
                $oldData = '********';
                $newData = '********';
            }
            
            if($oldData!=$newData){
                $logSql = "INSERT INTO admin_log (user,row_id, tablename, columnname, oldData, newData, action, date) VALUES 
                ('".$username."','$id', '$tablename', '$key', $oldData, $newData, 'edit', NOW())";

                mysqli_query($theconnection, $logSql);
                //$dt .= $key . ' : ' . preg_replace('/[^\w\s\r\n]/', ' ',$oldData) . ' => ' . preg_replace('/[^\w\s\r\n]/', ' ',$newData) . '<br>';
            }
        }
    }
    if(!$cron){
        
        if(!isset($newtablename)){
            $newtablename ='';
        }
        $notificationSql = "INSERT INTO `notifications` (`user_id`, `message`, `uid`, `status`, `created_at`) VALUES ('$userId', '$username has updated ".$ROW['id']." ".preg_replace('/[^A-Za-z0-9 ]/', '',$ROW['name'])." in ".ucfirst($newtablename)." in $databasenm\n\n<a href=".$url.$newtablename."/edit/".($newtablename == 'event' ? $ROW['c_id']."/".$ROW['id'] : $ROW['id'] ).">Click here</a> to view', '$userId', 'unread', NOW())";
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
            $insertSql = "INSERT INTO `notifications` (`user_id`, `message`, `uid`, `status`, `created_at`) VALUES ('$adminUserId', '$username has updated ".$ROW['id']." ".preg_replace('/[^A-Za-z0-9 ]/', '',$ROW['name'])." in ".ucfirst($newtablename)." in $databasenm\n\n<a href=".$url.$newtablename."/edit/".($newtablename == 'event' ? $ROW['c_id']."/".$ROW['id'] : $ROW['id'] ).">Click here</a> to view', '$userId', 'unread', NOW())";
            mysqli_query($conn, $insertSql);
        }
    }
} else {

    $restype = 'Insertion';
    $insertedId = 0;
    if(!isset($_POST['updated_at'])){
        $_POST['updated_at'] = $currentDateTime;
    }
    $_POST['created_at'] = $currentDateTime;
    if (in_array('s_alias', $Columns)) {
        $_POST['s_alias'] = generateSlug($_POST['name'] );
    }
    $postcolumns = array_keys($_POST);
    // Get column names from $_POST keys
    $columns = array_keys($_POST);
    $columnsthere = array();
    $values = array();
    foreach ($columns as $column) {
        // Check if column exists in the table
        echo $query = " SHOW COLUMNS FROM $tablename LIKE '$column'";
        $result = mysqli_query($theconnection, $query);
        if (mysqli_num_rows($result) > 0) {
            $value = mysqli_real_escape_string($theconnection, $_POST[$column]);
            $values[] = "'$value'";
            $columnsthere[] = $column;
        }
    }
    echo '<pre>';
    print_r($columnsthere);
    echo '</pre>';

    echo '<pre>';
    print_r($values);
    echo '</pre>';
    if (!empty($values)) {
        $change = true;
        try {
            $columnsString = implode(',', $columnsthere);
            $valuesString = implode(',', $values);

            $sql = "INSERT INTO $tablename (" . $columnsString . ") VALUES (" . $valuesString . ")";
            mysqli_query($theconnection, $sql);
            echo $insertedId = mysqli_insert_id($theconnection); // Retrieve the inserted ID
            // get the last inserted id
            $key = array_search('c_id', $columnsthere);
            if ($key !== false) {
                echo $c_id = trim($values[$key],"'");
            } else {
                
            }
        } catch (mysqli_sql_exception $e) {
            if(!isset($cronjob)){
                echo '<div class="alert border-0 bg-light-danger alert-dismissible fade show py-2">
                        <div class="d-flex align-items-center">
                          <div class="fs-3 text-danger"><i class="bi bi-x-circle-fill"></i>
                          </div>
                          <div class="ms-3">
                            <div class="text-danger">'.$e.'</div>
                          </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
            }

        }
        $noti = '';
        if($insertedId != 0){
            
            foreach ($_POST as $key => $value) {
                if (is_scalar($value)) {
                    $logValue = ($value === '') ? 'NULL' : "'" . mysqli_real_escape_string($theconnection, $value) . "'";
                    $logSql = "INSERT INTO admin_log (user, tablename,  columnname, oldData, newData, action, date) VALUES 
                    ('$username', '$tablename', '$key', NULL, $logValue, 'create', NOW())";
                    mysqli_query($theconnection, $logSql);
                    if($key == 'name'){ $noti = preg_replace('/[^A-Za-z0-9 ]/', '', $logValue); }
                }
            }
        
            if(!$cron){
                if(!isset($newtablename)){
                    $newtablename ='';
                }
                $notificationSql = "INSERT INTO `notifications` (`user_id`, `message`, `uid`,`status`, `created_at`) VALUES ('$userId', '$username has created new ".ucfirst($newtablename)." $noti in $databasenm\n\n<a href=".$url.$newtablename."/edit/".($newtablename == 'event' ? $c_id."/".$insertedId : $insertedId ).">Click here</a> to view', '$userId','unread', NOW())";
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
                    $insertSql = "INSERT INTO `notifications` (`user_id`, `message`, `uid`, `status`, `created_at`) VALUES ('$adminUserId', '$username has created new ".ucfirst($newtablename)." $noti in $databasenm\n\n<a href=".$url.$newtablename."/edit/".($newtablename == 'event' ? $c_id."/".$insertedId : $insertedId ).">Click here</a> to view', '$userId', 'unread', NOW())";
                    mysqli_query($conn, $insertSql);
                }
            }
        }else{
            echo '<div class="alert border-0 bg-light-danger alert-dismissible fade show py-2">
                        <div class="d-flex align-items-center">
                          <div class="fs-3 text-danger"><i class="bi bi-x-circle-fill"></i>
                          </div>
                          <div class="ms-3">
                            <div class="text-danger">Something went wrong!</div>
                          </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
        }
    }
}
 
if(!isset($cronjob) && isset($folderName) ){
    if ($restype == 'Update' && $change) {
        echo '<div class="alert border-0 bg-light-success alert-dismissible fade show py-2">
                        <div class="d-flex align-items-center">
                        <div class="fs-3 text-success"><i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div class="ms-3">
                            <div class="text-success">'.$restype.' successful</div>
                        </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
        if(isset($id)){
            //($cid != '' ? '/'.$cid : '')
            $urlback = $url.$folderName.'/'.$editslug.'/'.$id;
        }else{
            $urlback = $url.$folderName.'/'.$viewslug;
        }
    } else {
        echo '<div class="alert border-0 bg-light-success alert-dismissible fade show py-2">
                        <div class="d-flex align-items-center">
                        <div class="fs-3 text-success"><i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div class="ms-3">
                            <div class="text-success">'.$restype.' successful</div>
                        </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
        if(isset($id)){
            $urlback = $url.$folderName.'/'.$editslug.'/'.$id;
        }else{
            $urlback = $url.$folderName.'/'.$viewslug.'';
        }
        
    }
}
?>
