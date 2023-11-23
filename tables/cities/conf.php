<?php 
$id = $_GET['id'] ?? null; 
 $httpurl = $url;
 $prompt = '';
$system = '';
$validby='';
 $updateurl = 'update';
$remoteDirectory = 'images';
if (isset($conn2)) { $theconnection = $conn2; }else { $theconnection = ''; }
$folderName =  basename(__DIR__);
     $viewslug = 'view';
     $tablename = $citiestablename;
    $tabletitle = 'Cities';  
     $editslug = 'edit';
     $restype = '';
    $change = false; 
     
    $maxlenginfield = 20;
     $thedbname = $db2_name;
     $trashslug = 'trash';
    $deleteslug = 'delete';
    $ajaxview= true;
    
    ?>