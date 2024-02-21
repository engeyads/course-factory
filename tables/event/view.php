<?php
if ($_SESSION['userlevel'] > 2 ) {
    $path = dirname(__FILE__);
    include $path.'/conf.php'; 
    $tooltips = ['start_date','end_date'  ];
    $popups = [ ];
    $jsonarrays = [ ];
    $imagePaths = [ ];
    // $urlPaths = ['c_id' => 'id'];
    $ajaxview= true;
    switch ($db_name) {
        case "agile4training":
            $ignoredColumns = ['d1', 'm1', 'y1', 'd2', 'm2', 'y2','deleted_at'];  
            $fieldTitles = ['certain' => 'Upcoming', 'start_date' => 'Start Date', 'end_date' => 'End Date', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $urlPaths = ['id' => 'id'];
            $urlPath = 'id';
            // $no_link = true;
            // $no_edits = true;
            $urlslug = $websiteurl . $eventslug;
            
            break;
        case "agile4training ar":
            $ignoredColumns = ['d1', 'm1', 'y1', 'd2', 'm2', 'y2','deleted_at'];  
            $fieldTitles = ['certain' => 'Upcoming', 'start_date' => 'Start Date', 'end_date' => 'End Date', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $urlPaths = ['id' => 'id'];
            $urlPath = 'id';
            // $no_link = true;
            // $no_edits = true;
            $urlslug = $websiteurl . $eventslug;
            
            break;
        case "blackbird-training.co.uk":
            $ignoredColumns = ['d1', 'm1', 'y1', 'd2', 'm2', 'y2','deleted_at'];  
            $fieldTitles = ['certain' => 'Upcoming', 'start_date' => 'Start Date', 'end_date' => 'End Date', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $imagePaths = ['city_photo' => $citiesimgurl,'slider_photo' => $citiessliderimgurl];
            $urlPaths = ['id' => 'id'];
            $urlPath = 'id';
            // $no_link = true;
            // $no_edits = true;
            $urlslug = $websiteurl . $eventslug;
            
            break;
        case "mercury_dubai":
            $ignoredColumns = ['d1', 'm1', 'y1', 'd2', 'm2', 'y2','deleted_at'];  
            $fieldTitles = ['certain' => 'Upcoming', 'start_date' => 'Start Date', 'end_date' => 'End Date', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $imagePaths = [];
            $urlPaths = ['id' => 'id'];
            $urlPath = 'id';
            // $no_link = true;
            // $no_edits = true;
            $urlslug = $websiteurl . $eventslug;
            
            break;
        case "mercury arabic":
            $ignoredColumns = ['d1', 'm1', 'y1', 'd2', 'm2', 'y2','deleted_at'];  
            $fieldTitles = ['certain' => 'Upcoming', 'start_date' => 'Start Date', 'end_date' => 'End Date', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $imagePaths = [];
            $urlPaths = ['id' => 'id'];
            $urlPath = 'id';
            // $no_link = true;
            // $no_edits = true;
            $urlslug = $websiteurl . $eventslug;
            
            break;
        case "mercury english":
            $ignoredColumns = ['d1', 'm1', 'y1', 'd2', 'm2', 'y2','deleted_at'];  
            $fieldTitles = ['certain' => 'Upcoming', 'start_date' => 'Start Date', 'end_date' => 'End Date', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $imagePaths = [];
            $urlPaths = ['id' => 'id'];
            $urlPath = 'id';
            // $no_link = true;
            // $no_edits = true;
            $urlslug = $websiteurl . $eventslug;
            
            break;
        case "mercury-training":
            $ignoredColumns = ['d1', 'm1', 'y1', 'd2', 'm2', 'y2','deleted_at'];  
            $fieldTitles = ['certain' => 'Upcoming', 'start_date' => 'Start Date', 'end_date' => 'End Date', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $imagePaths = [];
            // $editPath = 'c_id';
            $urlPath = 'id';
            // $no_link = true;
            // $no_edits = true;
            $urlslug = $websiteurl . $eventslug;
            
            break;
        case "blackbird-training":
            $ignoredColumns = ['d1', 'm1', 'y1', 'd2', 'm2', 'y2','deleted_at'];  
            $fieldTitles = ['certain' => 'Upcoming', 'start_date' => 'Start Date', 'end_date' => 'End Date', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $imagePaths = [];
            $no_link = true;
            
            $urlslug = $websiteurl . $eventslug;
            // the link for the events for bb old is complicated combined of name of the category and course and city and the id of the event
            //ex: Advance-Report-Writing-Communication-Skills/133696/Professional-Skills/Cape-Town.htm
            break;
        case "Euro Wings En":
            $ignoredColumns = ['d1', 'm1', 'y1', 'd2', 'm2', 'y2','deleted_at'];  
            $fieldTitles = ['certain' => 'Upcoming', 'start_date' => 'Start Date', 'end_date' => 'End Date', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $urlslug = '';
            $imagePaths = [];
            // the link for the events for Euro Wings En is complicated combined of name of the category and course and city and the id of the event
            //ex: Advance-Report-Writing-Communication-Skills/133696/Professional-Skills/Cape-Town.htm
            break;
        case "Euro Wings Ar":
            $ignoredColumns = ['d1', 'm1', 'y1', 'd2', 'm2', 'y2','deleted_at'];  
            $fieldTitles = ['certain' => 'Upcoming', 'start_date' => 'Start Date', 'end_date' => 'End Date', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $urlslug = '';
            $imagePaths = [];
            // the link for the events for Euro Wings Ar is complicated combined of name of the category and course and city and the id of the event
            //ex: Advance-Report-Writing-Communication-Skills/133696/Professional-Skills/Cape-Town.htm
            break;
        default:
            echo " ";
            break;
    }
    
    $dateColumns = ['created_at', 'updated_at','published_at']; // replace with your actual date columns
    $categories = GetForSelect('course_c' , $conn2, 'id', 'name');
    $cities = GetForSelect('cities' , $conn2, 'id', 'name');
    //$course = GetForSelect('course_main' , $conn2, 'c_id', 'name');
    $ajaxDataArrays = array(
        // array(
        //     'column' => 'city',
        //     'table' => 'cities',
        //     'param1' => 'id',
        //     'param2' => 'name',
        // ),
        // array(
        //     'column' => 'c_id',
        //     'table' => 'course_main',
        //     'param1' => 'c_id',
        //     'param2' => 'name',
        // ),
        // array(
        //     'column' => 'category',
        //     'table' => 'course_c',
        //     'param1' => 'id',
        //     'param2' => 'name',
        // ),
    );
    $dataArrays = [
        // 'category' => $categories, // 'column_name_in_table' => 'select_name
        // 'city' => $cities,
        'monday' => ['0' => 'No', '1' => 'Yes']
        // 'c_id' => $course
        // Add more column mappings here
    ];
    // $keys = array_keys($course);

    if(isset($_GET['upcomming'])){
        if($_GET['upcomming'] == '0'){
            $upcomming = " 1 ";
        }elseif($_GET['upcomming'] == 'on'){
            $upcomming = " `course`.`certain`='".$_GET['upcomming']."' ";
        }else{
            $upcomming = " `course`.`certain` IS NULL ";
        }
    }else{
        $upcomming = " 1 ";
    }
    if(isset($_GET['monday'])){
        if($_GET['monday'] == '0'){
            $monday = "1";
        }elseif($_GET['monday'] == '2'){
            $monday = " `cities`.`monday`=1";
        }else{
            $monday = " `cities`.`monday` != 1 ";
        }
    }else{
        $monday = " 1 ";    
    }
    if(isset($_GET['category'])){
        if($_GET['category'] != '0'){
            $category = " `course_c`.`id`=".$_GET['category'];
        }else{
            $category = " 1 ";
        }
    }else{
        $category = " 1 ";
    }
    if(isset($_GET['city'])){
        if($_GET['city'] != '0'){
            $city = " `cities`.`id`=".$_GET['city'];
        }else{
            $city = " 1 ";
        }
    }else{
        $city = " 1 ";
    }
    if(isset($_GET['y1'])){
        if($_GET['y1'] != '0'){
            $y1 = " `course`.`y1`=".$_GET['y1'];
        }else{
            $y1 = " 1 ";
        }
    }else{
        $y1 = " 1 ";
    }
    if(isset($_GET['y2'])){
        if($_GET['y2'] != '0'){
            $y2 = " `course`.`y2`=".$_GET['y2'];
        }else{
            $y2 = " 1 ";
        }
    }else{
        $y2 = " 1 ";
    }
    if(isset($_GET['m1'])){
        if($_GET['m1'] != '0'){
            $m1 = " `course`.`m1`=".$_GET['m1'];
        }else{
            $m1 = " 1 ";
        }
    }else{
        $m1 = " 1 ";
    }
    if(isset($_GET['m2'])){
        if($_GET['m2'] != '0'){
            $m2 = " `course`.`m2`=".$_GET['m2'];
        }else{
            $m2 = " 1 ";
        }
    }else{
        $m2 = " 1 ";
    }
    if(isset($_GET['d1'])){
        if($_GET['d1'] != '0'){
            $d1 = " `course`.`d1`=".$_GET['d1'];
        }else{
            $d1 = " 1 ";
        }
    }else{
        $d1 = " 1 ";
    }
    if(isset($_GET['d2'])){
        if($_GET['d2'] != '0'){
            $d2 = " `course`.`d2`=".$_GET['d2'];
        }else{
            $d2 = " 1 ";
        }
    }else{
        $d2 = " 1 ";
    }
    if(isset($_GET['class'])){
        if($_GET['class'] != '0'){
            $class = " LOWER(`course_c`.`class`)=LOWER('".$_GET['class']."') ";
        }else{
            $class = " 1 ";
        }
    }else{
        $class = " 1 ";
    }
    if(isset($_GET['weeks'])){
        if($_GET['weeks'] != '0'){
            $weeks = " `course_main`.`$DBweekname`=".$_GET['weeks'];
        }else{
            $weeks = " 1 ";
        }
    }else{
        $weeks = " 1 ";
    }
    $custom_select = "course.id,CONCAT('<a href=/cities/edit/', `cities`.`id`, '>', `cities`.`name`, '</a>') AS City,CONCAT('<a href=/courses/edit/', `course_main`.`id`, '>', `course_main`.`name`, '</a>') AS course_name ,CONCAT('<a href=/categories/edit/', `course_c`.`id`, '>', `course_c`.`name`, '</a>') AS category ,course.price as Price,course.c_id,course.deleted_at,course.currency as Currency,CASE WHEN course.certain = 'on' THEN '✅' ELSE '❌' END AS Upcomming ,CASE WHEN cities.monday = 1 THEN '✅' ELSE '❌' END AS monday ,date(CONCAT(y1, '-', LPAD(m1, 2, '0'), '-', LPAD(d1, 2, '0'))) AS start_date, date(CONCAT(y2, '-', LPAD(m2, 2, '0'), '-', LPAD(d2, 2, '0'))) AS end_date";
    $custom_from = " course LEFT JOIN course_main ON course.c_id = course_main.c_id LEFT JOIN course_c ON course_main.course_c = course_c.id  LEFT JOIN cities ON course.city = cities.id ";
    
    if(isset($_GET['monday']) || isset($_GET['city']) || isset($_GET['upcomming']) || isset($_GET['category']) || isset($_GET['y1']) || isset($_GET['y2']) || isset($_GET['m1']) || isset($_GET['m2']) || isset($_GET['d1']) || isset($_GET['d2'])){
        $custom_where = " $upcomming AND $category AND $city AND $monday AND $y1 AND $y2 AND $m1 AND $m2 AND $d1 AND $d2 AND $class AND $weeks"; 
    }
    $searchsColumns = [ 'course_name'=>'course_main`.`name','id' => 'course`.`id','category' => 'course_c`.`name' , 'City' => 'cities`.`name' ,  'Upcomming' => 'course`.`certain', 'Price' => 'course`.`price', 'Currency' => 'course`.`currency'];
    //   $costumeQuery = "SELECT * ,
    //         TIMESTAMP(CONCAT(y1, '-', LPAD(m1, 2, '0'), '-', LPAD(d1, 2, '0'))) AS start_date,
    //         TIMESTAMP(CONCAT(y2, '-', LPAD(m2, 2, '0'), '-', LPAD(d2, 2, '0'))) AS end_date
    //     FROM course";
    // $excludesearch = ['category'];
    $ignoredColumns = ['price','currency','certain','smart','city','c_id','d1', 'm1', 'y1', 'd2', 'm2', 'y2','deleted_at','updated_at','created_at','published_at'];
    $ignoredColumnsDB = ['hotel_photo','hotel_link','address','visible' ];  // Replace these with your actual column names to ignore
    $additionalColumns = ['course_name','category','City','Upcomming','monday','Price','Currency','start_date', 'end_date'];  // Replace these with your actual additional column names
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
        // (object)[
        //     'containerid' => 'filtersacordion',
        //     'id' => 'filters',
        //     'type' => 'accordion',
        //     'heading' => '2',
        //     'lable' => 'Filters',
        //     'acid' => 'thfilters',
        // ],
        (object)[
            'id' => 'h',
            'type' => 'h',
            'heading' => '2',
            'lable' => 'Filters: ',
          ],
        
          (object)[
            'id' => 'category',
            'type' => 'select',
            'options' => $categories,
            'size' => '4',
            'kind' => '',
            'action' => "$url"."event/view/",
            'name' => 'category',
            'subdiv' => 'true',
            'lable' => 'Category',
            'class' => 'float-right justify-content-end table-select',
            'selected' => $_GET['category'] ?? '',
          ],
          (object)[
            'id' => 'city',
            'type' => 'select',
            'options' => $cities,
            'size' => '4',
            'kind' => '',
            'action' => "$url"."event/view/",
            'name' => 'city',
            'subdiv' => 'true',
            'lable' => 'City',
            'class' => 'float-right justify-content-end table-select',
            'selected' => $_GET['city'] ?? '',
          ],
          (object)[
            'id' => 'upcomming',
            'type' => 'select',
            'options' => ['on' => 'Upcomming', '1' => 'Not Upcomming'],
            'kind' => '',
            'size' => '4',
            'action' => "$url"."event/view/",
            'name' => 'certain',
            'subdiv' => 'true',
            'lable' => 'is Upcomming?',
            'class' => 'float-right justify-content-end table-select',
            'selected' => $_GET['upcomming'] ?? '',
          ],
          (object)[
            'id' => 'monday',
            'type' => 'select',
            'options' => ['1' => 'sunday', '2' => 'monday'],
            'kind' => '',
            'size' => '4',
            'action' => "$url"."event/view/",
            'name' => 'monday',
            'subdiv' => 'true',
            'lable' => 'is Monday?',
            'class' => 'float-right justify-content-end table-select',
            'selected' => $_GET['monday'] ?? '',
          ],
          (object)[
            'id' => 'y1',
            'type' => 'select',
            'options' => ['2023' => '2023', '2024' => '2024', '2025' => '2025'],
            'kind' => '',
            'size' => '4',
            'action' => "$url"."event/view/",
            'name' => 'y1',
            'lable' => 'Start Year',
            'subdiv' => 'true',
            'class' => 'float-right justify-content-end table-select',
            'selected' => $_GET['y1'] ?? '',
          ],
          (object)[
            'id' => 'y2',
            'type' => 'select',
            'options' => ['2023' => '2023', '2024' => '2024', '2025' => '2025'],
            'kind' => '',
            'size' => '4',
            'action' => "$url"."event/view/",
            'name' => 'y2',
            'lable' => 'End Year',
            'subdiv' => 'true',
            'class' => 'float-right justify-content-end table-select',
            'selected' => $_GET['y2'] ?? '',
          ],
          (object)[
            'id' => 'm1',
            'type' => 'select',
            'options' => ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12'],
            'kind' => '',
            'size' => '4',
            'action' => "$url"."event/view/",
            'name' => 'm1',
            'lable' => 'Start Month',
            'subdiv' => 'true',
            'class' => 'float-right justify-content-end table-select',
            'selected' => $_GET['m1'] ?? '',
          ],
          (object)[
            'id' => 'm2',
            'type' => 'select',
            'options' => ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12'],
            'kind' => '',
            'size' => '4',
            'action' => "$url"."event/view/",
            'name' => 'm2',
            'lable' => 'End Month',
            'subdiv' => 'true',
            'class' => 'float-right justify-content-end table-select',
            'selected' => $_GET['m2'] ?? '',
          ],
          (object)[
            'id' => 'd1',
            'type' => 'select',
            'options' => ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15','16' => '16', '17' => '17', '18' => '18', '19' => '19', '20' => '20','21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25','26' => '26', '27' => '27', '28' => '28', '29' => '29', '30' => '30','31' => '31'],
            'kind' => '',
            'size' => '4',
            'action' => "$url"."event/view/",
            'name' => 'd1',
            'lable' => 'Start Day',
            'subdiv' => 'true',
            'class' => 'float-right justify-content-end table-select',
            'selected' => $_GET['d1'] ?? '',
          ],
          (object)[
            'id' => 'd2',
            'type' => 'select',
            'options' => ['1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15','16' => '16', '17' => '17', '18' => '18', '19' => '19', '20' => '20','21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25','26' => '26', '27' => '27', '28' => '28', '29' => '29', '30' => '30','31' => '31'],
            'kind' => '',
            'size' => '4',
            'action' => "$url"."event/view/",
            'name' => 'd2',
            'lable' => 'End Day',
            'subdiv' => 'true',
            'class' => 'float-right justify-content-end table-select',
            'selected' => $_GET['d2'] ?? '',
          ],
          (object)[
            'id' => 'class',
            'type' => 'select',
            'options' => ['a' => 'A', 'b' => 'B', 'c' => 'C','d' => 'D'],
            'kind' => '',
            'size' => '4',
            'action' => "$url"."event/view/",
            'name' => 'class',
            'lable' => 'Class',
            'subdiv' => 'true',
            'class' => 'float-right justify-content-end table-select',
            'selected' => $_GET['class'] ?? '',
          ],
          (object)[
            'id' => 'weeks',
            'type' => 'select',
            'options' => ['1' => '1', '2' => '2', '3' => '3','4' => '4','12'=>'12'],
            'kind' => '',
            'size' => '4',
            'action' => "$url"."event/view/",
            'name' => $DBweekname,
            'lable' => 'Weeks',
            'subdiv' => 'true',
            'class' => 'float-right justify-content-end table-select',
            'selected' => $_GET['weeks'] ?? '',
          ]
        //   ,
        //   (object)[
        //       'id' => 'filters',
        //       'type' => 'accordionend',
        //       'heading' => '2',
        //       'lable' => 'Filters',
        //     ]
    ];
    $customLink = "$url"."event/addeventtocourse";
    include 'include/view.php';
    include 'include/logview.php';

    ?>
    <script>

        $(document).ready(function() {
            // Attach the onTableChange function to the change event of select tags with the specified class
            $(document).on('change', '.table-select', onTableChange);
        });

        function onTableChange() {
            // Get selected values from select tags
            var jsonData = {};

            // Loop through select tags with the specified class
            $('.table-select').each(function(index, element) {
                // if the index is even then enter
                    var tableName = $(element).data('table');
                    var selectedValue = $(element).val();
                    jsonData[tableName] = selectedValue === null ? '' : selectedValue; // Use an empty string if the value is null
            });

            // Construct URL with parameters
            var urlParams = Object.values(jsonData)
                .map(value => value || 0)
                .join('/');

            // Redirect to the specified page
            window.location.href = "<?php echo $url; ?>event/view/" + urlParams+'/'+<?php echo isset( $_GET['searchfor']) ? $_GET['searchfor'] : '0'; ?>;


            // Make AJAX GET request
            // $.ajax({
            //     type: "GET",
            //     url: "view/" + urlParams,
            //     contentType: "application/json; charset=utf-8",
            //     dataType: "json",
            //     success: function (response) {
            //         // Handle the response from the server
            //         $('#result').text(JSON.stringify(response));
            //     },
            //     error: function (error) {
            //         console.error('Error:', error);
            //     }
            // });


        }




            
    </script>
    <?php
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
