<?php
if ($_SESSION['userlevel'] > 2 ) {
    $path = dirname(__FILE__);
    include $path.'/conf.php'; 
    $tooltips = ['start_date','end_date'  ];
    $popups = [ ];
    $jsonarrays = [ ];
    $imagePaths = [ ];
    $urlPaths = ['c_id' => 'id'];
    $ajaxview= true;
    switch ($db_name) {
        case "agile4training":
            $ignoredColumns = ['d1', 'm1', 'y1', 'd2', 'm2', 'y2','deleted_at'];  
            $fieldTitles = ['c_id' => 'Course Name' ,'certain' => 'Upcoming', 'start_date' => 'Start Date', 'end_date' => 'End Date', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            // $imagePaths = ['city_photo' => $citiesimgurl,'slider_photo' => $citiessliderimgurl];
            $urlslug = $websiteurl .$citiesslug;
            
            break;
        case "agile4training ar":
            $ignoredColumns = ['d1', 'm1', 'y1', 'd2', 'm2', 'y2','deleted_at'];  
            $fieldTitles = ['c_id' => 'Course Name' ,'certain' => 'Upcoming', 'start_date' => 'Start Date', 'end_date' => 'End Date', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            // $imagePaths = ['city_photo' => $citiesimgurl,'slider_photo' => $citiessliderimgurl];
            $urlslug = $websiteurl .$citiesslug;
            
            break;
        case "blackbird-training.co.uk":
            $ignoredColumns = ['d1', 'm1', 'y1', 'd2', 'm2', 'y2','deleted_at'];  
            $fieldTitles = ['c_id' => 'Course Name' ,'certain' => 'Upcoming', 'start_date' => 'Start Date', 'end_date' => 'End Date', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $imagePaths = ['city_photo' => $citiesimgurl,'slider_photo' => $citiessliderimgurl];
            $urlslug = $websiteurl .$citiesslug;
            
            break;
        case "mercury_dubai":
            $ignoredColumns = ['d1', 'm1', 'y1', 'd2', 'm2', 'y2','deleted_at'];  
            $fieldTitles = ['c_id' => 'Course Name' ,'certain' => 'Upcoming', 'start_date' => 'Start Date', 'end_date' => 'End Date', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $urlslug = '';
            $imagePaths = [];
            
            break;
        case "mercury arabic":
            $ignoredColumns = ['d1', 'm1', 'y1', 'd2', 'm2', 'y2','deleted_at'];  
            $fieldTitles = ['c_id' => 'Course Name' ,'certain' => 'Upcoming', 'start_date' => 'Start Date', 'end_date' => 'End Date', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $urlslug = '';
            $imagePaths = [];
            
            break;
        case "mercury english":
            $ignoredColumns = ['d1', 'm1', 'y1', 'd2', 'm2', 'y2','deleted_at'];  
            $fieldTitles = ['c_id' => 'Course Name' ,'certain' => 'Upcoming', 'start_date' => 'Start Date', 'end_date' => 'End Date', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $urlslug = '';
            $imagePaths = [];
            
            break;
        case "mercury-training":
            $ignoredColumns = ['d1', 'm1', 'y1', 'd2', 'm2', 'y2','deleted_at'];  
            $fieldTitles = ['c_id' => 'Course Name' ,'certain' => 'Upcoming', 'start_date' => 'Start Date', 'end_date' => 'End Date', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $urlslug = '';
            $imagePaths = [];
            
            break;
        case "blackbird-training":
            $ignoredColumns = ['d1', 'm1', 'y1', 'd2', 'm2', 'y2','deleted_at'];  
            $fieldTitles = ['c_id' => 'Course Name' ,'certain' => 'Upcoming', 'start_date' => 'Start Date', 'end_date' => 'End Date', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $urlslug = '';
            $imagePaths = [];
            
            break;
        case "Euro Wings En":
            $ignoredColumns = ['d1', 'm1', 'y1', 'd2', 'm2', 'y2','deleted_at'];  
            $fieldTitles = ['c_id' => 'Course Name' ,'certain' => 'Upcoming', 'start_date' => 'Start Date', 'end_date' => 'End Date', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $urlslug = '';
            $imagePaths = [];
            
            break;
        case "Euro Wings Ar":
            $ignoredColumns = ['d1', 'm1', 'y1', 'd2', 'm2', 'y2','deleted_at'];  
            $fieldTitles = ['c_id' => 'Course Name' ,'certain' => 'Upcoming', 'start_date' => 'Start Date', 'end_date' => 'End Date', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $urlslug = '';
            $imagePaths = [];
            
            break;
        default:
            echo " ";
            break;
    }
    
    $dateColumns = ['created_at', 'updated_at','published_at']; // replace with your actual date columns
    $categories = GetForSelect('course_c' , $conn2, 'id', 'name');
    $cities = GetForSelect('cities' , $conn2, 'id', 'name');
    $course = GetForSelect('course_main' , $conn2, 'c_id', 'name');
    $ajaxDataArrays = array(
        array(
            'column' => 'city',
            'table' => 'cities',
            'param1' => 'id',
            'param2' => 'name',
        ),
        array(
            'column' => 'c_id',
            'table' => 'course_main',
            'param1' => 'c_id',
            'param2' => 'name',
        ),
        array(
            'column' => 'category',
            'table' => 'course_c',
            'param1' => 'id',
            'param2' => 'name',
        ),
    );
    $dataArrays = [
        'category' => $categories, // 'column_name_in_table' => 'select_name
        'city' => $cities,
        'c_id' => $course
        // Add more column mappings here
    ];
    // $keys = array_keys($course);
    $custom_select = "course.* ,course.id as course_id, course_c.id as category ,TIMESTAMP(CONCAT(y1, '-', LPAD(m1, 2, '0'), '-', LPAD(d1, 2, '0'))) AS start_date, TIMESTAMP(CONCAT(y2, '-', LPAD(m2, 2, '0'), '-', LPAD(d2, 2, '0'))) AS end_date";
    $custom_from = " course LEFT JOIN course_main ON course.c_id = course_main.c_id LEFT JOIN course_c ON course_main.course_c = course_c.id LEFT JOIN cities ON course.city = cities.id";
    $searchColumns = [ 'id' => 'course`.`id','category' => 'course_c`.`id' , 'city' => 'cities`.`id' , 'c_id' => 'course_main`.`c_id'];
    //   $costumeQuery = "SELECT * ,
    //         TIMESTAMP(CONCAT(y1, '-', LPAD(m1, 2, '0'), '-', LPAD(d1, 2, '0'))) AS start_date,
    //         TIMESTAMP(CONCAT(y2, '-', LPAD(m2, 2, '0'), '-', LPAD(d2, 2, '0'))) AS end_date
    //     FROM course";
    $ignoredColumns = ['d1', 'm1', 'y1', 'd2', 'm2', 'y2','deleted_at','updated_at','created_at','published_at'];   
    $ignoredColumnsDB = ['hotel_photo','hotel_link','address','visible' ];  // Replace these with your actual column names to ignore
    $additionalColumns = ['category','start_date', 'end_date'];  // Replace these with your actual additional column names
    $withEventCid = true;
    $custom_buttons = [
        (object)[
            'id' => 'addmultievent',
            'type' => 'a',
            'kind' => 'URL',
            'action' => "$url"."event/addmultiple/",
            'name' => 'Add Multiple Event',
            'class' => 'float-right justify-content-end',
        ],
        (object)[
            'id' => 'category',
            'type' => 'select',
            'options' => $categories,
            'kind' => 'URL',
            'action' => "$url"."event/viewfiltered/",
            'name' => 'category',
            'class' => 'float-right justify-content-end',
          ],
          (object)[
            'id' => 'city',
            'type' => 'select',
            'options' => $cities,
            'kind' => 'URL',
            'action' => "$url"."event/viewfiltered/",
            'name' => 'city',
            'class' => 'float-right justify-content-end',
          ],
          (object)[
            'id' => 'upcomming',
            'type' => 'select',
            'options' => ['1' => 'Upcomming', '0' => 'Not Upcomming'],
            'kind' => 'URL',
            'action' => "$url"."event/viewfiltered/",
            'name' => 'certain',
            'class' => 'float-right justify-content-end',
          ]
    ];
    $customLink = "$url"."event/addeventtocourse";
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
