<?php 
    switch ($db_name) {
        case "agile4training":
            $imagePaths = [];
            $urlslug = $websiteurl .$categoriesslug;
            break;
        case "mercury-training":
            $urlslug = '';
            $imagePaths = [];
            break;
        case "blackbird-training":
            $urlslug = '';
            $imagePaths = [];
            break;
        default:
            echo " ";
            break;
    }
    $id = $_GET['id'] ?? null;
    $folderName = basename(__DIR__);
    $tablename = "users";
    $tabletitle = 'Users';
     $maxlenginfield = 20;
    if (isset($conn)) { $theconnection = $conn; }else { $theconnection = ''; }
    $thedbname = $conn1dbname;
    $editslug = 'edit';
    $trashslug = 'trash';
    $deleteslug = 'delete';
    $viewslug = 'view';
    
    $httpurl = $url;
    $prompt = '';
    $system = '';
    $validby='';
    $updateurl = 'update';
    $remoteDirectory = '';
    $restype = '';
    $change = false;
    $ajaxview= false;
?>