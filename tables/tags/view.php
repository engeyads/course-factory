<?php
if ($_SESSION['userlevel'] > 9 ) {
    $folderName = basename(__DIR__);
    $tablename = 'tags';
    $tabletitle = 'Tags';
    $urlslug = $websiteurl .$categoriesslug;
    $maxlenginfield = 20;
    $theconnection = $conn;
    $thedbname = $conn1dbname;
    $editslug = 'edit';
    $trashslug = 'trash';
    $deleteslug = 'delete';
    $viewslug = 'view';
    $ignoredColumns = ['s_alias', 'color', 'another_column', 'deleted_at'];
    $tooltips = ['', 'title' ];
    $popups = ['description', 'keyword', 'text'];
    $jsonarrays = ['keyword', 'xx'];
    $imagePaths = ['glyphicon' => $categoriesimgurl];
    $urlPaths = ['name' => 's_alias'];
    $fieldTitles = ['glyphicon' => 'Image',  'sh' => 'Short',  'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
    $dateColumns = ['created_at', 'updated_at','published_at']; // replace with your actual date columns
    $dataArrays = [
        //'subtag_id' => $subtags, // Add more column mappings here
    ];
    include 'include/view.php';
    include 'include/logview.php';
}else{
    echo 'Access Denied!';
}
?>
