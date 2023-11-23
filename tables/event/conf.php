<?php 
    $tablename = 'course' ;
    $id = $_GET['id'] ?? null; 
    $theconnection = $conn2;
    $folderName =  basename(__DIR__);
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
       $tabletitle =  'Event';   
 
     $restype = '';
     $change = false;
     $urlslug = $websiteurl .$eventslug;
    $maxlenginfield = 20;
     $thedbname = $db2_name;
    $editslug = 'edit';
    $trashslug = 'trash';
    $deleteslug = 'delete';
    $viewslug = 'view';
    $ajaxview= false;
    

?>