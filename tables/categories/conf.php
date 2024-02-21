<?php 
    switch ($db_name) {
        case "agile4training":
            $imagePaths = ['glyphicon' => $categoriesimgurl];
            $minTitleChar=40;
            $maxTitleChar = 60;
            $minShortCode = 1;
            $maxShortCode = 4;
            $urlslug = $websiteurl .$categoriesslug;
            $imgcolumn = 's_alias';
            $urlPath = 's_alias';
            break;
        case "agile4training ar":
            $imagePaths = ['glyphicon' => $categoriesimgurl];
            $minTitleChar=40;
            $maxTitleChar = 60;
            $minShortCode = 1;
            $maxShortCode = 4;
            $urlslug = $websiteurl .$categoriesslug;
            $imgcolumn = 's_alias';
            $urlPath = 's_alias';
            break;
        case "mercury_dubai":
            $urlslug = '';
            $minTitleChar=40;
            $maxTitleChar = 60;
            $minShortCode = 1;
            $maxShortCode = 2;
            $imagePaths = ['image' => $categoriesimgurl];
            $imgcolumn = 's_alias';
            $urlPath = 's_alias';
            break;
        case "mercury arabic":
            $urlslug = $websiteurl .$categoriesslug;
            $minTitleChar=40;
            $maxTitleChar = 60;
            $minShortCode = 1;
            $maxShortCode = 2;
            $imagePaths = ['glyphicon' => $categoriesimgurl];
            $imgcolumn = 'alias';
            $urlPath = 'name';
            $dashedname = true;
            break;
        case "mercury english":
            $urlslug = $websiteurl .$categoriesslug;
            $minTitleChar=40;
            $maxTitleChar = 60;
            $minShortCode = 1;
            $maxShortCode = 2;
            $imagePaths = ['glyphicon' => $categoriesimgurl];
            $imgcolumn = 'alias';
            $urlPath = 'alias';
            break;
        case "mercury-training":
            $urlslug = $websiteurl .$categoriesslug;
            $minTitleChar=40;
            $maxTitleChar = 60;
            $minShortCode = 1;
            $maxShortCode = 2;
            $imagePaths = ['glyphicon' => $categoriesimgurl];
            $imgcolumn = 's_alias';
            $urlPath = 'alias';
            break;
        case "Euro Wings En":
            $urlslug = $websiteurl .$categoriesslug;
            $minTitleChar=40;
            $maxTitleChar = 60;
            $minShortCode = 1;
            $maxShortCode = 2;
            $imagePaths = ['image' => $categoriesimgurl];
            $imgcolumn = 's_alias';
            $urlPath = 's_alias';
            break;
        case "Euro Wings Ar":
            $urlslug = $websiteurl .$categoriesslug;
            $minTitleChar=40;
            $maxTitleChar = 60;
            $minShortCode = 1;
            $maxShortCode = 2;
            $imagePaths = ['image' => $categoriesimgurl];
            $imgcolumn = 's_alias';
            $urlPath = 's_alias'; 
            break;
        case "blackbird-training.co.uk":
            $urlslug = $websiteurl .$categoriesslug;
            $minTitleChar=40;
            $maxTitleChar = 60;
            $minShortCode = 1;
            $maxShortCode = 4;
            $imagePaths = ['glyphicon' => $categoriesimgurl];
            $imgcolumn = 's_alias';
            $urlPath = 's_alias';
            break;
        case "blackbird-training":
            $urlslug = $websiteurl .$categoriesslug;
            $minTitleChar=40;
            $maxTitleChar = 60;
            $minShortCode = 1;
            $maxShortCode = 2;
            $imagePaths = ['glyphicon' => $categoriesimgurl];
            $imgcolumn = 's_alias';
            $urlPath = 's_alias';
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