<?php
if ($_SESSION['userlevel'] > 2 ) {
    $path = dirname(__FILE__);
    include $path.'/conf.php'; 

$ajaxview= true;
    switch ($db_name) {
        case "agile4training":
            $ignoredColumns = ['description', 'keyword', 'text','s_alias', 'deleted_at','publish','like','done','tags','cource_cc','certain','price','objective','who_should_attend','code', 'out_lines','out_lines2','name_file','Arabic','Certified','conference','top','clean','aside','hidden','accreditation','broshoure','overview'];
            $tooltips = [ 'course_c', 'title', 'subtitle' , 'sub_title'];
            $popups = [ 'description', 'keyword', 'text','about','overview','broshoure','ar_overview','ar_broshoure','ar_about','ar_text','ar_description'];
            $jsonarrays = ['keyword','ar_keyword'];
            $urlPaths = [];
            $editPath = 'name';
            $urlPath = 's_alias';
            // $no_link = true;
            // $no_edits = true;
            $fieldTitles = ['c_id' => 'Course ID' ,'course_c' => 'Category', 'sub_title' => 'Sub Title', 'sh' => 'Short', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $dateColumns = ['created_at', 'updated_at','published_at']; // replace with your actual date columns
            // $urlslug = '';
            $imagePaths = ['image' => $courseimgurl];
            
            break;
        case "agile4training ar":
            $ignoredColumns = ['description', 'keyword', 'text','s_alias', 'deleted_at','publish','like','done','tags','cource_cc','certain','price','objective','who_should_attend','code', 'out_lines','out_lines2','name_file','Arabic','Certified','conference','top','clean','aside','hidden','accreditation','broshoure','overview'];
            $tooltips = [ 'course_c', 'title', 'subtitle' , 'sub_title'];
            $popups = [ 'description', 'keyword', 'text','about','overview','broshoure','ar_overview','ar_broshoure','ar_about','ar_text','ar_description'];
            $jsonarrays = ['keyword','ar_keyword'];
            $urlPaths = [];
            $editPath = 'name';
            $urlPath = 's_alias';
            // $no_link = false;
            // $no_edits = false;
            $fieldTitles = ['c_id' => 'Course ID' ,'course_c' => 'Category', 'sub_title' => 'Sub Title', 'sh' => 'Short', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $dateColumns = ['created_at', 'updated_at','published_at']; // replace with your actual date columns
            // $urlslug = '';
            $imagePaths = ['image' => $courseimgurl];
            
            break;
        case "blackbird-training":
            $ignoredColumns = ['s_alias', 'deleted_at','publish','like','done','tags','cource_cc','certain','price','objective','who_should_attend','code', 'out_lines','out_lines2','name_file','Arabic','Certified','conference','top','clean','aside','hidden','accreditation','broshoure','overview'];
            $tooltips = [ 'course_c', 'title', 'subtitle' , 'sub_title'];
            $popups = [ 'description', 'keyword', 'text','about','overview','broshoure','ar_overview','ar_broshoure','ar_about','ar_text','ar_description'];
            $jsonarrays = ['keyword','ar_keyword'];
            $urlPaths = [];
            $editPath = 'name';
            $urlPath = 'name';
            // $no_link = false;
            $fieldTitles = ['c_id' => 'Course ID' ,'course_c' => 'Category', 'sub_title' => 'Sub Title', 'sh' => 'Short', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $dateColumns = ['created_at', 'updated_at','published_at']; // replace with your actual date columns
            // $urlslug = '';
            $imagePaths = [];
            
            break;
        case "blackbird-training.co.uk":
            $ignoredColumns = ['keyword','keywords','description','created','s_alias', 'deleted_at','publish','like','done','tags','cource_cc','certain','price','objective','who_should_attend','code', 'out_lines','out_lines2','name_file','Arabic','Certified','conference','top','clean','aside','hidden','accreditation','Keyword','broshoure','overview'];
            $tooltips = [ 'course_c', 'title', 'subtitle' , 'sub_title'];
            $popups = [ 'description', 'keywords', 'text','about','overview','broshoure','ar_overview','ar_broshoure','ar_about','ar_text','ar_description'];
            $jsonarrays = ['keywords','ar_keyword'];
            $urlPaths = [];
            $editPath = 'name';
            $urlPath = 's_alias';
            // $no_link = false;
            $fieldTitles = ['c_id' => 'Course ID' ,'course_c' => 'Category', 'sub_title' => 'Sub Title', 'sh' => 'Short', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $dateColumns = ['created_at', 'updated_at','published_at']; // replace with your actual date columns
            // $urlslug = '';
            $imagePaths = [];
            
            break;
        case "mercury_dubai":
            $ignoredColumns = ['s_alias', 'deleted_at','publish','like','done','tags','cource_cc','certain','price','objective','who_should_attend','code', 'out_lines','out_lines2','name_file','Arabic','Certified','conference','top','clean','aside','hidden','accreditation','broshoure','overview'];
            $tooltips = [ 'course_c', 'title', 'subtitle' , 'sub_title'];
            $popups = [ 'description', 'keyword', 'text','about','overview','broshoure','ar_overview','ar_broshoure','ar_about','ar_text','ar_description'];
            $jsonarrays = ['keyword','ar_keyword'];
            $urlPaths = [];
            $editPath = 'name';
            $urlPath = 'c_id';
            // $no_link = false;
            $fieldTitles = ['c_id' => 'Course ID' ,'course_c' => 'Category', 'sub_title' => 'Sub Title', 'sh' => 'Short', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $dateColumns = ['created_at', 'updated_at','published_at']; // replace with your actual date columns
            // $urlslug = '';
            $imagePaths = [];
            
            break;
        case "mercury arabic":
            $ignoredColumns = [ 'deleted_at','publish','like','done','tags','cource_cc','certain','price','objective','who_should_attend','code', 'out_lines','out_lines2','name_file','Arabic','Certified','conference','top','clean','aside','hidden','accreditation','broshoure','overview'];
            $tooltips = [ 'course_c', 'title', 'subtitle' , 'sub_title'];
            $popups = [ 'description', 'keyword', 'text','about','overview','broshoure','ar_overview','ar_broshoure','ar_about','ar_text','ar_description'];
            $jsonarrays = ['keyword','ar_keyword'];
            $urlPaths = [];
            $editPath = 'name';
            $urlPath = 'c_id';
            // $no_link = false;
            $fieldTitles = ['c_id' => 'Course ID' ,'course_c' => 'Category', 'sub_title' => 'Sub Title', 'sh' => 'Short', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $dateColumns = ['created_at', 'updated_at','published_at']; // replace with your actual date columns
            // $urlslug = '';
            $imagePaths = ['image' => $courseimgurl];
            
            break;
        case "mercury english":
            $ignoredColumns = ['s_alias', 'deleted_at','publish','like','done','tags','cource_cc','certain','price','objective','who_should_attend','code', 'out_lines','out_lines2','name_file','Arabic','Certified','conference','top','clean','aside','hidden','accreditation','broshoure','overview'];
            $tooltips = [ 'course_c', 'title', 'subtitle' , 'sub_title'];
            $popups = [ 'description', 'keyword', 'text','about','overview','broshoure','ar_overview','ar_broshoure','ar_about','ar_text','ar_description'];
            $jsonarrays = ['keyword','ar_keyword'];
            $urlPaths = [];
            $editPath = 'name';
            $urlPath = 'c_id';
            // $no_link = false;
            $fieldTitles = ['c_id' => 'Course ID' ,'course_c' => 'Category', 'sub_title' => 'Sub Title', 'sh' => 'Short', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $dateColumns = ['created_at', 'updated_at','published_at']; // replace with your actual date columns
            // $urlslug = '';
            $imagePaths = ['image' => $courseimgurl];
            
            break;
        case "mercury-training":
            $ignoredColumns = ['s_alias', 'deleted_at','publish','like','done','tags','cource_cc','certain','price','objective','who_should_attend','code', 'out_lines','out_lines2','name_file','Arabic','Certified','conference','top','clean','aside','hidden','accreditation','broshoure','overview'];
            $tooltips = [ 'course_c', 'title', 'subtitle' , 'sub_title'];
            $popups = [ 'description', 'keyword', 'text','about','overview','broshoure','ar_overview','ar_broshoure','ar_about','ar_text','ar_description'];
            $jsonarrays = ['keyword','ar_keyword'];
            $urlPaths = [];
            $editPath = 'name';
            $urlPath = 'c_id';
            // $no_link = false;
            $fieldTitles = ['c_id' => 'Course ID' ,'course_c' => 'Category', 'sub_title' => 'Sub Title', 'sh' => 'Short', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $dateColumns = ['created_at', 'updated_at','published_at']; // replace with your actual date columns
            // $urlslug = '';
            $imagePaths = [];
            
            break;
        case "Euro Wings En":
            $ignoredColumns = ['s_alias', 'deleted_at','publish','like','done','tags','cource_cc','certain','price','objective','who_should_attend','code', 'out_lines','out_lines2','name_file','Arabic','Certified','conference','top','clean','aside','hidden','accreditation','broshoure','overview'];
            $tooltips = [ 'course_c', 'title', 'subtitle' , 'sub_title'];
            $popups = [ 'description', 'keyword', 'text','about','overview','broshoure','ar_overview','ar_broshoure','ar_about','ar_text','ar_description'];
            $jsonarrays = ['keyword','ar_keyword'];
            $urlPaths = [];
            $editPath = 'name';
            $urlPath = 'name';
            // $no_link = false;
            $fieldTitles = ['c_id' => 'Course ID' ,'course_c' => 'Category', 'sub_title' => 'Sub Title', 'sh' => 'Short', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $dateColumns = ['created_at', 'updated_at','published_at']; // replace with your actual date columns
            // $urlslug = '';
            $imagePaths = [];
            
            break;
        case "Euro Wings Ar":
            $ignoredColumns = ['alias', 'deleted_at','publish','like','done','tags','cource_cc','certain','price','objective','who_should_attend','code', 'out_lines','out_lines2','name_file','Arabic','Certified','conference','top','clean','aside','hidden','accreditation','broshoure','overview'];
            $tooltips = [ 'course_c', 'title', 'subtitle' , 'sub_title'];
            $popups = [ 'description', 'keyword', 'text','about','overview','broshoure','ar_overview','ar_broshoure','ar_about','ar_text','ar_description'];
            $jsonarrays = ['keyword','ar_keyword'];
            $urlPaths = [];
            $editPath = 'name';
            $urlPath = 'name';
            // $no_link = false;
            $fieldTitles = ['c_id' => 'Course ID' ,'course_c' => 'Category', 'sub_title' => 'Sub Title', 'sh' => 'Short', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $dateColumns = ['created_at', 'updated_at','published_at']; // replace with your actual date columns
            // $urlslug = '';
            $imagePaths = [];
            
            break;
        default:
            echo " ";
            break;
    }

    $categories = GetForSelect('course_c' , $conn2, 'id', 'name');
    $ajaxDataArrays = array(
            array(
                'column' => 'course_c', // the column name in the table
                'table' => 'course_c',
                'param1' => 'id',
                'param2' => 'name',
                // Add more column mappings here
            ),
        );
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
    <a href="javascript:history.back()" class="btn btn-primary">Back to Previous Page</a>
    <?php

}
?>