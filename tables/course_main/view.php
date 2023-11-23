<?php
if ($_SESSION['userlevel'] > 2 ) {
    $path = dirname(__FILE__);
    include $path.'/conf.php';   
    $ignoredColumns = ['s_alias',  'deleted_at','publish','like','done','tags','cource_cc'];
    $tooltips = [ 'course_c','name', 'title', 'subtitle' , 'sub_title', 'ar_sub_title'];
    $popups = [ 'description', 'keyword', 'text','about','overview','broshoure'];
    $jsonarrays = ['keyword'];
    $imagePaths = ['country_photo' => $citiescountryimgurl,'city_photo' => $citiesimgurl,'slider_photo' => $citiessliderimgurl];
    $urlPaths = ['name' => 's_alias'];
    $fieldTitles = ['c_id' => 'Course ID' ,'course_c' => 'Category', 'sub_title' => 'Sub Title', 'sh' => 'Short', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
    $dateColumns = ['created_at', 'updated_at','published_at']; // replace with your actual date columns

    $categories = GetForSelect('course_c' , $conn2, 'id', 'name');
    $dataArrays = [
       'course_c' => $categories,
        // Add more column mappings here
    ];
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

