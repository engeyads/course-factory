<?php 
    $id = $_GET['id'] ?? null; 
    $folderName = basename(__DIR__);
    $tablename = 'seo';
    $tabletitle = 'Search Engine Optimization';
    $custom_button_title = 'Update SEO Data';  
     $maxlenginfield = 20;
    if (isset($conn2)) { $theconnection = $conn2; }else { $theconnection = ''; }
    $thedbname = $db2_name;
    $editslug = 'edit';
    $trashslug = 'trash';
    $deleteslug = 'delete';
    $viewslug = 'view';
    
    $httpurl = $url;
     $prompt = '';
    $system = '';
    $validby='';
    $updateurl = 'update';

     $restype = '';
    $change = false;
    $ajaxview= true;
?>