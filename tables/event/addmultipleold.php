<?php
$path = dirname(__FILE__);
include $path.'/conf.php';  
echo "<h1>Add Multiple Events Once</h1>";
$cid = $_GET['cid'] ?? null;
if($cid == null){
    $query = "SELECT c_id FROM `course_main` ORDER BY `c_id` ASC LIMIT 1";
    $result = mysqli_query($conn2, $query);
    $row = mysqli_fetch_assoc($result);
    $cid = $row['c_id'];
}else{
    $query = "SELECT c_id FROM `course_main` WHERE c_id = $cid";
    $result = mysqli_query($conn2, $query);
    
    if($row = mysqli_fetch_assoc($result)){

        $cid = $row['c_id'];
    }else{
        echo "no course with this id<br><button class='btn btn-primary float-right justify-content-end' onclick='window.location.href = \"/course-factory/event/addmultiple/\"'>Go To Add Multiple Events</button>";
        exit;
    }
}

$query = "SELECT c_id FROM `course_main` WHERE c_id > $cid ORDER BY `c_id` ASC LIMIT 1";
    $result = mysqli_query($conn2, $query);
    if(mysqli_num_rows($result) != 0){
        $row = mysqli_fetch_assoc($result);
        $nxtcid = $row['c_id'];
    }else{
        $query = "SELECT c_id FROM `course_main` ORDER BY `c_id` ASC LIMIT 1";
        $result = mysqli_query($conn2, $query);
        $row = mysqli_fetch_assoc($result);
        $nxtcid = $row['c_id'];
    }

// select courses with thir cities and course name query by c_id from the course table
$courseQuery = "SELECT * FROM course_main 
WHERE c_id=$cid AND deleted_at IS NULL AND (published_at <= NOW() OR published_at IS NULL)";
// select courses with thir cities and course name query by c_id from the course table
// select courses with thir cities and course name query by c_id from the course table
// $eventsQuery = "SELECT * FROM cities WHERE x>0 AND deleted_at IS NULL AND (published_at <= NOW() OR published_at IS NULL)";
// result of the query
$courses = mysqli_query($conn2, $courseQuery);
$course = mysqli_fetch_assoc($courses);

$coursecQuery = "SELECT * FROM course_c WHERE id= ".$course['course_c'];
$coursec = mysqli_query($conn2, $coursecQuery);
$coursec = mysqli_fetch_assoc($coursec);

$class = $coursec['class'];
$weeknumber = $course[$DBweekname];
switch ($class) {
    case 'B': $classforweek  = '_b';
    break;
    case 'C': $classforweek  = '_c';
    break;
    // default case is A
    default: $classforweek  = '';
}
$cityQuery = "SELECT * FROM cities WHERE x$classforweek>0 AND deleted_at IS NULL AND (published_at <= NOW() OR published_at IS NULL)";
switch ($course[$DBweekname]){
    case 2:
    $daysdration = 11;
    break;
    case 3:
    $daysdration = 18;
    break;
    case 4:
    $daysdration = 25;
    break;
    // default case is 1
    default: $daysdration = 4;
}
//select courses with thir cities and course name query by c_id from the course table
// $all = "SELECT course.id as 'Event ID', course_main.name as 'Course Name', course.created_at as 'Created', cities.name as 'City',course.certain as Upcoming ,cities.x as x,  TIMESTAMP(CONCAT(y1, '-', LPAD(m1, 2, '0'), '-', LPAD(d1, 2, '0'))) AS start_date, TIMESTAMP(CONCAT(y2, '-', LPAD(m2, 2, '0'), '-', LPAD(d2, 2, '0'))) AS end_date, course.updated_at as 'Updated', course.published_at as 'Publish', course.price as 'Price', course.currency as 'Currency' FROM course 
// LEFT JOIN cities ON course.city = cities.id
// LEFT JOIN course_main ON course_main.c_id = course.c_id
// WHERE course.c_id=$cid AND cities.x > 0";
?>
<script src="<?php echo $url ?>assets/makitweb/jquery-3.3.1.min.js"></script>
<script src="<?php echo $url ?>assets/makitweb/DataTables/datatables.min.js"></script>
<!-- if there is any courses -->
<?php if(mysqli_num_rows($courses) > 0){ ?>
    <script src="https://cdn.jsdelivr.net/npm/pickadate/lib/picker.js"></script>
<script src="https://cdn.jsdelivr.net/npm/pickadate/lib/picker.date.js"></script>
<style>
    .picker {
        position: absolute;
        transform: translateZ(0);
    }
    /* Other elements with z-index values lower than the picker */
    div, .card, .card-body, .table-responsive {
        position: relative;
        overflow: visible;
    }
</style>
<div class="card">
    <div class="card-body">
        <h2>Courses</h2>
        <div class="table-responsive">

            <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap5">
                <table id='example1' class='table table-striped table-bordered dataTable no-footer display dataTable'>
                    <thead>
                        <tr>
                            <th>Course ID</th>
                            <th>Category</th>
                            <th>Class</th>
                            <th>Name</th>
                            <th>Weeks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- show all courses mach this c_id in the table -->
                            <tr>
                                <td>
                                    <?php echo $course['c_id']; ?>
                                </td>
                                <td>
                                    <?php echo $coursec['name']; ?>
                                </td>
                                <td>
                                    <?php echo $coursec['class']; ?>
                                </td>
                                <td>
                                    <?php echo $course['name']; ?>
                                </td>
                                <td>
                                    <?php echo $course[$DBweekname]; ?>
                                </td>
                            </tr>
                    </tbody>
                </table></div>
            </div>
        </div>
    </div>
<?php } ?>

<?php
$cities = mysqli_query($conn2, $cityQuery);
?>
<!-- if there is any courses -->
<?php if(mysqli_num_rows($cities) > 0){ ?>
<div class="card">
    <div class="card-body">
        <h2>Cities</h2>
        <form method="POST" action="<?php echo $url; ?>event/updatemultiple">
        <div class="table-responsive">

            <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap5">
                <table id='example2' class='table table-striped table-bordered dataTable no-footer display dataTable'>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Repeat Ratio</th>
                            <th>Price</th>
                            <th>This Event Count</th>
                            <th>All Events Count</th>
                            <th>Events To Create</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- show all courses mach this c_id in the table -->
                        
                        <?php while( $city = mysqli_fetch_assoc($cities)){ ?>
                            <tr>
                                <td>
                                    <?php echo $city['id']; ?>
                                </td>
                                <td>
                                    <?php echo $city['name']; ?>
                                </td>
                                <td>
                                    <?php echo $city['x']; ?>
                                </td>


                                <td>
                                    <?php
                                        $pricefield = 'w'. $weeknumber . '_p'. $classforweek; 
                                            echo $cityprice = $city[$pricefield];   
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        $querycnt = "SELECT count(*) FROM course WHERE city = ".$city['id']." AND c_id=$cid AND deleted_at IS NULL AND (published_at <= NOW() OR published_at IS NULL)";
                                        $resultcnt = mysqli_query($conn2, $querycnt);
                                        $rowcnt = mysqli_fetch_row($resultcnt);
                                        $eventcnt = $rowcnt[0];
                                        echo $eventcnt;
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        $querycnt = "SELECT count(*) FROM course WHERE city = ".$city['id']." AND deleted_at IS NULL AND (published_at <= NOW() OR published_at IS NULL)";
                                        $resultcnt = mysqli_query($conn2, $querycnt);
                                        $rowcnt = mysqli_fetch_row($resultcnt);
                                        $totalcnt = $rowcnt[0];
                                        echo $totalcnt;
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        if($eventcnt < $city['x']){
                                            // input hiden c_id
                                            echo "<input name='c_id' type='hidden' value='$cid'>";
                                            echo "<input name='price-".$city['id']."' type='hidden' value='$city[$pricefield]'>";
                                            echo "<input name='id' type='hidden' value='".$course['id']."'>";

                                            $sunmon = $city['monday'] == 1 ? "Monday" : "Sunday";
                                            $sunmon = getRandomDays($sunmon, $city['x']-$eventcnt);
                                            $cnt = 0;
                                            foreach ($sunmon as $date) {
                                                $enddate = new DateTime($date);
                                                echo "<div style='display: inline-flex; position: relative;'>";  // Wrap the input and delete button in a div
                                                echo "<input id='".$city['code']."-".$city['id']."-"."date-".$cnt."' name='".$city['id']."-"."startdate-".$cnt."' placeholder='Start Date' class='result form-control ' type='text'  value='$date'>"; 
                                                echo "<input id='".$city['code']."-".$city['id']."-"."enddate-".$cnt."' name='".$city['id']."-"."enddate-".$cnt."' placeholder='End Date' class='result form-control ' type='hidden'  value='".$enddate->add(new DateInterval('P' . $daysdration . 'D'))->format('Y-m-d')."'>"; 
                                                // echo "<input id='".$city['code']."-".$city['id']."-"."day-".$cnt."' name='".$city['id']."-"."day-".$cnt."' data-id='".$city['id']."' class='days' type='hidden' value='".date('d', strtotime($date))."'>"; 
                                                // echo "<input id='".$city['code']."-".$city['id']."-"."month-".$cnt."' name='".$city['id']."-"."month-".$cnt."' data-id='".$city['id']."' class='months' type='hidden' value='".date('m', strtotime($date))."'>"; 
                                                // echo "<input id='".$city['code']."-".$city['id']."-"."year-".$cnt."' name='".$city['id']."-"."year-".$cnt."' data-id='".$city['id']."' class='years' type='hidden' value='".date('Y', strtotime($date))."'>"; 
                                                // echo "<input id='".$city['code']."-".$city['id']."-"."day1-".$cnt."' name='".$city['id']."-"."day1-".$cnt."' data-id='".$city['id']."' class='days1' type='hidden' value='".date('d', strtotime($enddate->add(new DateInterval('P' . $daysdration . 'D'))->format('Y-m-d')))."'>"; 
                                                // echo "<input id='".$city['code']."-".$city['id']."-"."month1-".$cnt."' name='".$city['id']."-"."month1-".$cnt."' data-id='".$city['id']."' class='months1' type='hidden' value='".date('m', strtotime($enddate->add(new DateInterval('P' . $daysdration . 'D'))->format('Y-m-d')))."'>"; 
                                                // echo "<input id='".$city['code']."-".$city['id']."-"."year1-".$cnt."' name='".$city['id']."-"."year1-".$cnt."' data-id='".$city['id']."' class='years1' type='hidden' value='".date('Y', strtotime($enddate->add(new DateInterval('P' . $daysdration . 'D'))->format('Y-m-d')))."'>"; 
                                                // The date picker input remains unchanged

                                                echo "</div>"; 
                                                // Close the wrapping div
                                                echo "<button style='background-color: red; color: white; border: none; border-radius: 8px;padding: 0 5px;height: 20px;width:20px' 
                                                onclick='this.previousElementSibling.remove(); this.remove();'>X</button>";
                                                // Create a delete button ('X') styled with a red background, white text color, no border, and padding.
                                                ?>
                                                <script>
                                                    <?php if(!isset($strict)){
                                                        echo '"use strict";';
                                                        $strict = 1;
                                                    } ?>
                                                    
                                                    $('#<?php echo $city['code']."-".$city['id']."-"."date-".$cnt; ?>').pickadate({
                                                        selectMonths: true,
                                                        selectYears: true, 
                                                        format: 'yyyy-mm-dd',
                                                        onSet: function(context) {
                                                            var date = new Date(context.select);
                                                            var selectDate = new Date(context.select);
                                                            selectDate.setDate(date.getDate() + <?php echo $daysdration; ?>);

                                                            
                                                            $('#<?php echo $city['code']."-".$city['id']."-"."enddate-".$cnt; ?>').val(selectDate.getFullYear()+'-'+(selectDate.getMonth() + 1)+'-'+selectDate.getDate());
                                                            // $('#<?php //echo $city['code']."-".$city['id']."-"."day-".$cnt; ?>').val(date.getDate());
                                                            // $('#<?php //echo $city['code']."-".$city['id']."-"."month-".$cnt; ?>').val(date.getMonth() + 1);
                                                            // $('#<?php //echo $city['code']."-".$city['id']."-"."year-".$cnt; ?>').val(date.getFullYear());
                                                            // $('#<?php //echo $city['code']."-".$city['id']."-"."day1-".$cnt; ?>').val(selectDate.getDate());
                                                            // $('#<?php //echo $city['code']."-".$city['id']."-"."month1-".$cnt; ?>').val(selectDate.getMonth() + 1);
                                                            // $('#<?php //echo $city['code']."-".$city['id']."-"."year1-".$cnt; ?>').val(selectDate.getFullYear());
                                                        }
                                                    });
                                                </script>
                                                <?php $cnt++;
                                            }
                                        }
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table></div>
                <button type="submit"  class="btn btn-primary float-right justify-content-end">Save</button>
            <button type="button" id="saveAndNext" class="btn btn-primary float-right justify-content-end">Save & Next</button>
        
        </form></div>
            <script>

                
                $(document).ready(function() {
                    // prevent default form submit and create json array of the form data
                    $('#saveAndNext').click(function() {
                        $('form').append('<input type="hidden" name="next" value="<?php echo $nxtcid; ?>">');   
                        $('form').submit();
                    });
                    $('#form').submit(function(e) {
                        e.preventDefault();
                        var form = $(this);
                        var url = form.attr('action');
                        var data = {};
                        var formData = form.serializeArray();
                        $.each(formData, function(index, value) {
                            data[value.name] = value.value;
                        });
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                data: data
                            },
                            success: function(response) {
                                console.log(response);
                                if (response == 'success') {
                                    alert('Saved');
                                } else {
                                    alert('Error');
                                }
                            }
                        });
                    });
                });
                
            </script>
        </div>
    </div>
<?php } ?>

<?php
    //select courses with thir cities and course name query by c_id from the course table
    
    // $tablename = 'course';
    // $tabletitle = 'Events';
    // $urlslug = $websiteurl .$eventslug;
    // $maxlenginfield = 20;
    // $theconnection = $conn2;
    // $thedbname = $db2_name;
    // $editslug = 'edit/'.$cid;
    
    // $trashslug = 'trash';
    // $deleteslug = 'delete';
    // $viewslug = 'view';
    // $ignoredColumns = ['c_id','hotel_link','hotel_photo','address','d1', 'm1', 'y1', 'd2', 'm2', 'y2','deleted_at'];  
    // $tooltips = ['start_date','end_date'];
    // $popups = [];
    // $jsonarrays = [];
    // $imagePaths = [];
    // $urlPaths = [];
    // $fieldTitles = ['x'=>'Repeat Ratio','id' => 'Event ID','certain' => 'Upcoming', 'start_date' => 'Start Date', 'end_date' => 'End Date', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish','city' => 'City','price' => 'Price','currency' => 'Currency'];
    // $dateColumns = ['created_at', 'updated_at','published_at']; // replace with your actual date columns
    // $cities = GetForSelect('cities' , $conn2, 'id', 'name');
    // $course = GetForSelect('course_main' , $conn2, 'c_id', 'name');
    // $dataArrays = [
    //    'city' => $cities,
    //    //'c_id' => $course
    //     // Add more column mappings here
    // ];
    // $ajaxDataArrays = array(
    //     array(
    //         'column' => 'city',
    //         'table' => 'cities',
    //         'param1' => 'id',
    //         'param2' => 'name',
    //     ),
    //     // array(
    //     //     'column' => 'c_id',
    //     //     'table' => 'course_main',
    //     //     'param1' => 'c_id',
    //     //     'param2' => 'name',
    //     // ),
    // );
    // $custom_from = "FROM course
    // LEFT JOIN cities ON course.city = cities.id
    // LEFT JOIN course_main ON course_main.c_id = course.c_id";
    // $custom_select = "SELECT course.id, course_main.c_id , course.created_at, cities.id as 'city' ,cities.x, course.certain, course.visible, TIMESTAMP(CONCAT(y1, '-', LPAD(m1, 2, '0'), '-', LPAD(d1, 2, '0'))) AS start_date, TIMESTAMP(CONCAT(y2, '-', LPAD(m2, 2, '0'), '-', LPAD(d2, 2, '0'))) AS end_date, course.updated_at, course.published_at,course.deleted_at, course.price, course.currency";
    // $custom_where = "WHERE course.c_id=$c_id";

    // $costumeQuery = "$custom_select $custom_from $custom_where";

    // $custom_from = "course LEFT JOIN cities ON course.city = cities.id";
    // $custom_select = "course.currency,course.price,course.id,  course.created_at, cities.id as 'city' ,cities.x, course.certain, course.visible ,DATE(CONCAT(y1, '-', LPAD(m1, 2, '0'), '-', LPAD(d1, 2, '0'))) AS start_date, DATE(CONCAT(y2, '-', LPAD(m2, 2, '0'), '-', LPAD(d2, 2, '0'))) AS end_date,course.updated_at,course.published_at,course.deleted_at";
    // $custom_where = "c_id = $cid";

    // $costumeQuery = "SELECT * ,
    //     DATE(CONCAT(y1, '-', LPAD(m1, 2, '0'), '-', LPAD(d1, 2, '0'))) AS start_date,
    //     DATE(CONCAT(y2, '-', LPAD(m2, 2, '0'), '-', LPAD(d2, 2, '0'))) AS end_date
    //     FROM course  WHERE c_id = $cid AND 1";
    // $ignoredColumnsDB = ['hotel_photo','hotel_link','address' ];  // Replace these with your actual column names to ignore
    // $additionalColumns = ['start_date', 'end_date','x'];  // Replace these with your actual additional column names
    // // echo $costumeQuery;
    // $ajaxview= true;
    //include 'include/view.php';
    //include 'include/logview.php';
    ?>

    <?php
    function getNext52Days($day) {
        $date = new DateTime();
        if (strtolower($date->format('l')) != strtolower($day)) {
            $date->modify('next ' . $day);
        }
    
        $days = [];
        for ($i = 0; $i < 52; $i++) {
            array_push($days, $date->format('Y-m-d'));
            $date->add(new DateInterval('P7D'));
        }
    
        return $days;
    }
    
    function getRandomDays($day, $parts) {
        $allDays = getNext52Days($day);
        $selectedDays = [];
    
        // divide all days into parts
        $chunkedDays = array_chunk($allDays, ceil(count($allDays) / $parts));
    
        foreach($chunkedDays as $chunk) {
            // pick a random day from each part
            array_push($selectedDays, $chunk[array_rand($chunk)]);
        }
    
        return $selectedDays;
    }
    
    
    ?>
    