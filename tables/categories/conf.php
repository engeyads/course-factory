<?php 
    switch ($db_name) {
        case "agile4training":
            $imagePaths = ['glyphicon' => $categoriesimgurl];
            $minTitleChar=40;
            $maxTitleChar = 60;
            $minShortCode = 1;
            $maxShortCode = 4;
            $urlslug = $websiteurl .$categoriesslug;
            break;
        case "agile4training ar":
            $imagePaths = ['glyphicon' => $categoriesimgurl];
            $minTitleChar=40;
            $maxTitleChar = 60;
            $minShortCode = 1;
            $maxShortCode = 4;
            $urlslug = $websiteurl .$categoriesslug;
            break;
        case "mercury_dubai":
            $urlslug = '';
            $minTitleChar=40;
            $maxTitleChar = 60;
            $minShortCode = 1;
            $maxShortCode = 2;
            $imagePaths = ['image' => $categoriesimgurl];
            break;
        case "mercury arabic":
            $urlslug = '';
            $minTitleChar=40;
            $maxTitleChar = 60;
            $minShortCode = 1;
            $maxShortCode = 2;
            $imagePaths = ['image' => $categoriesimgurl];
        break;
        case "mercury english":
            $urlslug = '';
            $minTitleChar=40;
            $maxTitleChar = 60;
            $minShortCode = 1;
            $maxShortCode = 2;
            $imagePaths = ['image' => $categoriesimgurl];
            break;
        case "mercury-training":
            $urlslug = '';
            $minTitleChar=40;
            $maxTitleChar = 60;
            $minShortCode = 1;
            $maxShortCode = 2;
            $imagePaths = ['glyphicon' => $categoriesimgurl];
            break;
        case "Euro Wings En":
            $urlslug = '';
            $minTitleChar=40;
            $maxTitleChar = 60;
            $minShortCode = 1;
            $maxShortCode = 2;
            $imagePaths = ['image' => $categoriesimgurl];
            break;
        case "Euro Wings Ar":
            $urlslug = '';
            $minTitleChar=40;
            $maxTitleChar = 60;
            $minShortCode = 1;
            $maxShortCode = 2;
            $imagePaths = ['image' => $categoriesimgurl];
            break;
        case "blackbird-training.co.uk":
            $urlslug = '';
            $minTitleChar=40;
            $maxTitleChar = 60;
            $minShortCode = 1;
            $maxShortCode = 4;
            $imagePaths = ['glyphicon' => $categoriesimgurl];
            break;
        case "blackbird-training":
            $urlslug = '';
            $minTitleChar=40;
            $maxTitleChar = 60;
            $minShortCode = 1;
            $maxShortCode = 2;
            $imagePaths = ['glyphicon' => $categoriesimgurl];
            break;
        default:
            echo " ";
            break;
    }
    $id = $_GET['id'] ?? null; 
    $folderName = basename(__DIR__);
    $tablename = $categoriestablename;
    $tabletitle = 'Categories';  
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
    $remoteDirectory = 'images';
     $restype = '';
    $change = false;
    $ajaxview= false;
?>