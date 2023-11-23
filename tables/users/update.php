<?php
 
if ($_SESSION['userlevel'] > 2 ) {
    $path = dirname(__FILE__);
    include $path.'/conf.php';   
    $id = isset($_POST['id']) ? $_POST['id'] : false;
    if($id){
        $sql  = "SELECT * FROM users where id = $id";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
        }

        if (isset($_POST['password']) && $_POST['password'] != '' ){
            $_POST['password'] = md5($_POST['password']);
        }else{
            unset($_POST['password']);
        }
    }

    include 'include/update.php';
    if($id){
        $insertedId = $id;
    }else{
      $insertedId;
    }
    echo $insertedId;
    //Step 1: Delete existing records for the user from the "user_db" table
    $query = "DELETE FROM user_db WHERE user_id = $insertedId";
    mysqli_query($conn, $query);
    
    //Step 2: Insert the selected values into the "user_db" table
    if (isset($_POST['databases']) && is_array($_POST['databases'])) {
        $selectedDatabases = $_POST['databases'];
        foreach ($selectedDatabases as $databaseId) {
            $query = "INSERT INTO user_db (user_id, db_id) VALUES ($insertedId, $databaseId)";
            mysqli_query($conn, $query);
        }
    }
    // will get $urlback from include/update.php after update or insert query
    header("Refresh: 1; url=" . $urlback);
    exit;
}
?>