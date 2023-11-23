<?php
if ($_SESSION['userlevel'] > 2 ) {
    $path = dirname(__FILE__);
    include $path.'/conf.php'; 
    $ajaxview= true;
    switch ($db_name) {
        case "agile4training":
            $ignoredColumns = ['s_alias', 'color', 'another_column', 'deleted_at'];
            $tooltips = ['', 'title' ];
            $popups = ['description', 'keyword', 'text'];
            $jsonarrays = ['keyword', 'xx'];
            $urlPaths = ['name' => 's_alias'];
            $fieldTitles = ['glyphicon' => 'Image',  'sh' => 'Short',  'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $dateColumns = ['created_at', 'updated_at','published_at'];
            //$gsc = ['indexed' => 's_alias'];
            $dataArrays = [
            ];
            break;
        case "agile4training ar":
            $ignoredColumns = ['s_alias', 'color', 'another_column', 'deleted_at'];
            $tooltips = ['', 'title' ];
            $popups = ['description', 'keyword', 'text'];
            $jsonarrays = ['keyword', 'xx'];
            $urlPaths = ['name' => 's_alias'];
            $fieldTitles = ['glyphicon' => 'Image',  'sh' => 'Short',  'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $dateColumns = ['created_at', 'updated_at','published_at'];
            //$gsc = ['indexed' => 's_alias'];
            $dataArrays = [
            ];
            break;
        case "blackbird-training.co.uk":
            $ignoredColumns = ['s_alias', 'color', 'another_column', 'deleted_at'];
            $tooltips = ['', 'title' ];
            $popups = ['description', 'keyword', 'text'];
            $jsonarrays = ['keyword', 'xx'];
            $urlPaths = ['name' => 's_alias'];
            $fieldTitles = ['glyphicon' => 'Image',  'sh' => 'Short',  'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $dateColumns = ['created_at', 'updated_at','published_at'];
            //$gsc = ['indexed' => 's_alias'];
            $dataArrays = [
            ];
            break;
        case "mercury_dubai":
            $ignoredColumns = ['s_alias', 'color', 'another_column', 'deleted_at'];
            $tooltips = ['', 'title' ];
            $popups = ['description', 'keyword', 'text'];
            $jsonarrays = ['keyword', 'xx'];
            $urlPaths = ['name' => 's_alias'];
            $fieldTitles = ['glyphicon' => 'Image',  'sh' => 'Short',  'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $dateColumns = ['created_at', 'updated_at','published_at'];
            //$gsc = ['indexed' => 's_alias'];
            $dataArrays = [
            ];
            break;
        case "mercury arabic":
            $ignoredColumns = ['alias', 'color', 'another_column', 'deleted_at'];
            $tooltips = ['', 'title' ];
            $popups = ['description', 'keyword', 'text'];
            $jsonarrays = ['keyword', 'xx'];
            $urlPaths = [];
            $fieldTitles = ['glyphicon' => 'Image',  'sh' => 'Short'];
            $dateColumns = ['created_at', 'updated_at','published_at'];
            //$gsc = ['indexed' => 's_alias'];
            $dataArrays = [
            ];
            break;
        case "mercury english":
            $ignoredColumns = ['alias', 'color', 'another_column', 'deleted_at'];
            $tooltips = ['', 'title' ];
            $popups = ['description', 'keyword', 'text'];
            $jsonarrays = ['keyword', 'xx'];
            $urlPaths = ['name' => 'alias'];
            $fieldTitles = ['glyphicon' => 'Image',  'sh' => 'Short',  'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $dateColumns = ['created_at', 'updated_at','published_at'];
            //$gsc = ['indexed' => 's_alias'];
            $dataArrays = [
            ];
            break;
        case "Euro Wings En":
            $ignoredColumns = ['s_alias', 'color', 'another_column', 'deleted_at'];
            $tooltips = ['', 'title' ];
            $popups = ['description', 'keyword', 'text'];
            $jsonarrays = ['keyword', 'xx'];
            $urlPaths = ['name' => 's_alias'];
            $fieldTitles = ['glyphicon' => 'Image',  'sh' => 'Short',  'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $dateColumns = ['created_at', 'updated_at','published_at'];
            //$gsc = ['indexed' => 's_alias'];
            $dataArrays = [
            ];
            break;
        case "Euro Wings Ar":
            $ignoredColumns = ['s_alias', 'color', 'another_column', 'deleted_at'];
            $tooltips = ['', 'title' ];
            $popups = ['description', 'keyword', 'text'];
            $jsonarrays = ['keyword', 'xx'];
            $urlPaths = ['name' => 's_alias'];
            $fieldTitles = ['glyphicon' => 'Image',  'sh' => 'Short',  'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $dateColumns = ['created_at', 'updated_at','published_at'];
            //$gsc = ['indexed' => 's_alias'];
            $dataArrays = [
            ];
            break;
        case "mercury-training":
            $ignoredColumns = ['s_alias', 'color', 'another_column', 'deleted_at'];
            $tooltips = ['', 'title' ];
            $popups = ['description', 'keyword', 'text'];
            $jsonarrays = ['keyword', 'xx'];
            $urlPaths = ['name' => 's_alias'];
            $fieldTitles = ['glyphicon' => 'Image',  'sh' => 'Short',  'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $dateColumns = ['created_at', 'updated_at','published_at'];
            //$gsc = ['indexed' => 's_alias'];
            $dataArrays = [
            ];
            break;
        case "blackbird-training":
            $ignoredColumns = ['s_alias', 'color', 'another_column', 'deleted_at'];
            $tooltips = ['', 'title' ];
            $popups = ['description', 'keyword', 'text'];
            $jsonarrays = ['keyword', 'xx'];
            $urlPaths = ['name' => 's_alias'];
            $fieldTitles = ['glyphicon' => 'Image',  'sh' => 'Short',  'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $dateColumns = ['created_at', 'updated_at','published_at'];
            //$gsc = ['indexed' => 's_alias'];
            $dataArrays = [
            ];
            break;
        default:
            echo " ";
            break;
    }

    
    include 'include/view.php';
    include 'include/logview.php';
}else{
    ?>
    <div class="alert border-0 bg-light-warning alert-dismissible fade show py-2">
        <div class="d-flex align-items-center">
            <div class="fs-3 text-warning"><i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div class="ms-3">
            <div class="text-warning">You are not allowed to access this page!</div>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php
}
?>
