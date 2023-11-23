<?php
if ($_SESSION['userlevel'] > 2 ) {
    $path = dirname(__FILE__);
    include $path.'/conf.php';   
    $ignoredColumns = [];
    $tooltips = [];
    $popups = [];
    $jsonarrays = [];
    $urlPaths = [];
    //$gsc = ['indexed' => 's_alias'];
    $fieldTitles = [];
    $dateColumns = ['created_at', 'updated_at','published_at']; // replace with your actual date columns
    $dataArrays = [
        //'subtag_id' => $subtags,
        // Add more column mappings here
    ];
    $imagePaths = [];
    $urlslug = '';
    
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
