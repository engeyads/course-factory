<?php 
    $id = $_GET['id'] ?? null; 
    $tablename = 'course_main' ;
    $theconnection = $conn;
     $folderName =  basename(__DIR__);
    $httpurl = $url;
    $tabletitle = 'Courses';  
    $prompt = '';
    $system = '';
    $validby='';
    $updateurl = 'update';
    $remoteDirectory = 'images';
    $editslug = 'edit';
    $restype = '';
    $change = false;
     $urlslug = $websiteurl .$courseslug;
    $maxlenginfield = 20;
     $thedbname = $conn1dbname;
     $trashslug = 'trash';
    $deleteslug = 'delete';
    $viewslug = 'view';
    $ajaxview= true;
?>