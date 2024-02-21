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
            $imgcolumn = 's_alias';
            $newdbstype = true;
        break;
        case 'agile4training ar':
            $DBweekname = 'week';
            $imgcolumn = 's_alias';
            $newdbstype = true;
        break;
        case 'blackbird-training':
            $DBweekname = 'weeks';
            $newdbstype = false;
        break;
        case 'blackbird-training.co.uk':
            $DBweekname = 'week';
            $imgcolumn = 'alias';
            $newdbstype = true;
        break;
        case 'mercury english':
            $DBweekname = 'week';
            $imgcolumn = 'alias';
            $newdbstype = true;
        break;
        case 'mercury arabic':
            $DBweekname = 'week';
            $imgcolumn = 'alias';
            $newdbstype = true;
        break;
        case 'Euro Wings En':
            $DBweekname = 'weeks';
            $imgcolumn = 's_alias';
            $newdbstype = false;
        break;
        case 'Euro Wings Ar':
            $DBweekname = 'weeks';
            $imgcolumn = 's_alias';
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
    switch ($_SESSION['db_name']) {
        case "agile4training":
            $urlPath = 's_alias';
            break;
        case "agile4training ar":
            $urlPath = 's_alias';
            break;
        case "blackbird-training":
            $urlPath = 'name';
            break;
        case "blackbird-training.co.uk":
            $urlPath = 's_alias';
            break;
        case "mercury_dubai":
            $urlPath = 'c_id';
            break;
        case "mercury english":
            $urlPath = 'c_id';
            break;
        case "mercury arabic":
            $urlPath = 'c_id';
            break;
        case "mercury-training":
            $urlPath = 'c_id';
            break;
        case "Euro Wings En":
            $urlPath = 'name';
            break;
        case "Euro Wings Ar":
            $urlPath = 'name';
            break;
        default:
            echo " ";
            break;
    }
?>