<?php 
switch ($_SESSION['db_name']) {
     case "agile4training":
          $urlPath = 's_alias';
         break;
     case "agile4training ar":
          $urlPath = 's_alias';
         break;
     case "blackbird-training":
          $urlPath = 's_alias';
         break;
     case "blackbird-training.co.uk":
          $urlPath = 's_alias';
         break;
     case "mercury_dubai":
          $urlPath = 'id';
         break;
     case "mercury arabic":
          $urlPath = 'id';
         break;
     case "mercury english":
          $urlPath = 'id';
         break;
     case "mercury-training":
          $urlPath = 's_alias';
         break;
     case "Euro Wings En":
          $urlPath = 's_alias';
         break;
     case "Euro Wings Ar":
          $urlPath = 's_alias';
         break;
     default:
         echo " ";
         break;
 }
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
    $urlslug = $websiteurl .$citiesslug;
    $maxlenginfield = 20;
     $thedbname = $db2_name;
     $trashslug = 'trash';
    $deleteslug = 'delete';
    $ajaxview= true;
    ?>