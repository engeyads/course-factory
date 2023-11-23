<?php 
    $tablename = 'course_main' ;
 
    $id = $_GET['id'] ?? null; 
    $countmin = 20;
    $countmax = 25;
    $httpurl = $url;
    $tabletitle = 'Courses';  
    $folderName =  basename(__DIR__);
    $promtoverview = "";
    switch($db_name){
        case 'agile4training':
            $DBweekname = 'week';
            
            $newdbstype = true;
        break;
        case 'agile4training ar':
            $DBweekname = 'week';
            $newdbstype = true;
        break;
        case 'blackbird-training':
            $DBweekname = 'weeks';
            $newdbstype = false;
        break;
        case 'blackbird-training.co.uk':
            $DBweekname = 'week';
            $newdbstype = true;
        break;
        case 'mercury english':
            $DBweekname = 'week';
            
            $newdbstype = false;
        break;
        case 'mercury arabic':
            $DBweekname = 'week';
        break;
        case 'Euro Wings En':
            $DBweekname = 'weeks';
            $newdbstype = false;
        break;
        case 'Euro Wings Ar':
            $DBweekname = 'weeks';
            $newdbstype = false;
        break;
        default:
        $DBweekname = 'week';
        $newdbstype = false;
        break;
    }
    $prompt = '';
    $system = '';
    $validby='';
    $theconnection = $conn2;

    $remoteDirectory = 'images';
 
    $restype = '';
    $change = false;
     $urlslug = $websiteurl .$courseslug;
    $maxlenginfield = 20;
     $thedbname = $db2_name;
    $editslug = 'edit';
    $trashslug = 'trash';
    $deleteslug = 'delete';
    $viewslug = 'view';
    $updateurl = 'update';
    
?>