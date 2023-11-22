<?php
$Columns = GetTableColumns($tablename,$theconnection);
if(isset($_SESSION['username'])){
    $username = $_SESSION['username'];
}
if(isset($cronjob)){
    $username = 'cronjob';
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

        $retrieveSql = "SELECT sitekeywords_id FROM sitekeywords_coursemain WHERE course_main_id = ?";
        $retrieveStmt = $theconnection->prepare($retrieveSql);
        $retrieveStmt->bind_param('i', $courseMainID);
        $retrieveStmt->execute();
        $retrieveResult = $retrieveStmt->get_result();

        while ($row = $retrieveResult->fetch_assoc()) {
            $existingKeywords[] = $row['sitekeywords_id'];
        }

        // Process the keywords and insert or skip duplicates
        foreach ($cleanedKeywords as $keyword) {
            // Check if the keyword already exists in the sitekeywords table
            $checkSql = "SELECT id FROM sitekeywords WHERE name = ?";
            $checkStmt = $theconnection->prepare($checkSql);
            $checkStmt->bind_param('s', $keyword);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();

            if ($checkResult->num_rows === 0) {
                // Keyword does not exist in sitekeywords, so insert it
                $insertSql = "INSERT INTO sitekeywords (name) VALUES (?)";
                $insertStmt = $theconnection->prepare($insertSql);
                $insertStmt->bind_param('s', $keyword);
                if ($insertStmt->execute()) {
                    // Insert was successful, get the newly inserted keyword ID
                    $sitekeywords_id = $theconnection->insert_id;
                } else {
                    echo "Error inserting data into sitekeywords: " . $theconnection->error;
                    continue; // Skip to the next keyword if insertion fails
                }
            } else {
                // Keyword exists, fetch its ID
                $keywordRow = $checkResult->fetch_assoc();
                $sitekeywords_id = $keywordRow['id'];
            }

            // Check if the combination of course_main_id and sitekeywords_id already exists
            $checkDuplicateSql = "SELECT id FROM sitekeywords_coursemain WHERE course_main_id = ? AND sitekeywords_id = ?";
            $checkDuplicateStmt = $theconnection->prepare($checkDuplicateSql);
            $checkDuplicateStmt->bind_param('ii', $courseMainID, $sitekeywords_id);
            $checkDuplicateStmt->execute();
            $checkDuplicateResult = $checkDuplicateStmt->get_result();

            if ($checkDuplicateResult->num_rows === 0) {
                // Combination of course_main_id and sitekeywords_id does not exist, so insert it
                $insertSql = "INSERT INTO sitekeywords_coursemain (course_main_id, sitekeywords_id) VALUES (?, ?)";
                $insertStmt = $theconnection->prepare($insertSql);
                $insertStmt->bind_param('ii', $courseMainID, $sitekeywords_id);
                if ($insertStmt->execute()) {
                    //echo "Keyword '$keyword' has been associated with course_main_id $courseMainID.<br>";
                } else {
                    echo "Error inserting data into sitekeywords_coursemain: " . $theconnection->error;
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
            $deleteSql = "DELETE FROM sitekeywords_coursemain WHERE course_main_id = ? AND sitekeywords_id IN (" . implode(',', $existingKeywords) . ")";
            $deleteStmt = $theconnection->prepare($deleteSql);
            $deleteStmt->bind_param('i', $courseMainID);
            if (!$deleteStmt->execute()) {
                echo "Error deleting data from sitekeywords_coursemain: " . $theconnection->error;
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
            $logSql = "INSERT INTO admin_log (user,row_id, tablename, columnname, oldData, newData, action, date) VALUES 
            
            ('".$username."','$id', '$tablename', '$key', $oldData, $newData, 'edit', NOW())";
            mysqli_query($theconnection, $logSql);
        }
    }
} else {

    $restype = 'Insertion';
    $insertedId = '';
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
        $query = "SHOW COLUMNS FROM $tablename LIKE '$column'";
        $result = mysqli_query($theconnection, $query);
        if (mysqli_num_rows($result) > 0) {
            $value = mysqli_real_escape_string($theconnection, $_POST[$column]);
            $values[] = "'$value'";
            $columnsthere[] = $column;
        }
    }
    if (!empty($values)) {
        $change = true;
        try {
            $columnsString = implode(',', $columnsthere);
            $valuesString = implode(',', $values);

            $sql = "INSERT INTO $tablename (" . $columnsString . ") VALUES (" . $valuesString . ")";
            mysqli_query($theconnection, $sql);
            $insertedId = mysqli_insert_id($theconnection); // Retrieve the inserted ID
            // get the last inserted id
             

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
        foreach ($_POST as $key => $value) {
            $logValue = ($value === '') ? 'NULL' : "'" . mysqli_real_escape_string($theconnection, $value) . "'";
            $logSql = "INSERT INTO admin_log (user, tablename,  columnname, oldData, newData, action, date) VALUES 
            ('$username', '$tablename', '$key', NULL, $logValue, 'create', NOW())";
            mysqli_query($theconnection, $logSql);
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
