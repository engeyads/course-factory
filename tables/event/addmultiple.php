<?php
$path = dirname(__FILE__);
include $path.'/conf.php';  
$cid = $_GET['cid'] ?? 0;
isset($_GET['start']) ? (is_numeric($_GET['start']) ? $start = $_GET['start']+1 : $start = 0) : $start = 0;
isset($_GET['limit']) ? (is_numeric($_GET['limit']) ? $limit = $_GET['limit'] : $limit = 40) : $limit = 40;
echo "<h1>Add Missing Events</h1>";
// get all course_maian count
$query = "SELECT count(*) FROM `course_main`";
$result = mysqli_query($conn2, $query);
$row = mysqli_fetch_row($result);
$totalCourses = $row[0]; // Total courses
echo '<h2>Total Courses: '.$row[0].'</h2>';

   
    // get count before this course
    $query = "SELECT count(*) FROM `course_main` WHERE c_id < ".$start;
    $result = mysqli_query($conn2, $query);
    $row = mysqli_fetch_row($result);
$currentCourseCount = $row[0]; // Current course count
    $percentage = ($currentCourseCount / $totalCourses) * 100;
    
    echo '<div class="progress-special-bar">';
echo '<div class="progress-special" style="width: ' . $percentage . '%;">';
echo '<div class="progress-special-text">' . $currentCourseCount . ' / ' . $totalCourses . '</div>';
echo '</div>';
echo '</div>';

// get all course_maian count
if($cid == null){
    //is number $_Get[start]
    $query = "SELECT c_id FROM `course_main` WHERE c_id >= $start ORDER BY `c_id` ASC LIMIT $limit";
    $result = mysqli_query($conn2, $query);
    // if mysqli_result
    if(mysqli_num_rows($result) == 0){
        echo "<br><h2 class='green'>Done</h2>";
        $stopSearch = true;
        
    } 

    while ($row = mysqli_fetch_assoc($result)) {

        if(mysqli_num_rows($result) == 0){
        $stopSearch = true;
    }else{
            $stopSearch = false;
        }
        $c_id = $row['c_id'];

//////////////////////////////////////////////////////////////// begin insertion
        // select courses with thir cities and course name query by c_id from the course table
        echo $courseQuery = "SELECT * FROM course_main 
        WHERE c_id=$c_id AND deleted_at IS NULL AND (published_at <= NOW() OR published_at IS NULL)";
        // select courses with thir cities and course name query by c_id from the course table
        // result of the query
        $courses = mysqli_query($conn2, $courseQuery);
        $course = mysqli_fetch_assoc($courses);
        if($course == null){
            continue;
        }
        echo $coursecQuery = "SELECT * FROM course_c WHERE deleted_at AND id= ".$course['course_c'];
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

        ?>

        <!-- if there is any courses -->
        <?php if(mysqli_num_rows($courses) > 0){ ?>
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
                                    <th>Events Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- show all courses mach this c_id in the table -->
                                
                                <?php while( $city = mysqli_fetch_assoc($cities)){ 

                                        $querycnt = "SELECT count(*) FROM course WHERE city = ".$city['id']." AND c_id=$c_id AND deleted_at IS NULL AND (published_at <= NOW() OR published_at IS NULL)";
                                        $resultcnt = mysqli_query($conn2, $querycnt);
                                        $rowcnt = mysqli_fetch_row($resultcnt);
                                        $eventcnt = $rowcnt[0];

                                        if($eventcnt < $city['x']){
                                            // input hiden c_id
                                            $pricefield = 'w'. $weeknumber . '_p'. $classforweek; 
                                            echo "<tr><td>".$city['id']."</td><td>".$city['name']."</td><td>".$city['x']."</td><td>".$city[$pricefield]."</td><td>".$eventcnt."</td><td>";

                                            $sunmon = $city['monday'] == 1 ? "Monday" : "Sunday";
                                            $sunmon = getRandomDays($sunmon, $city['x']-$eventcnt);
                                            
                                            foreach ($sunmon as $date) {

                                                $enddate = new DateTime($date);
                                                echo "<div style='display: inline-flex; position: relative;'>";  // Wrap the input and delete button in a div
                                                echo "$date"; 
                                                echo "</div>"; 
                                                echo "<div>";
                                                echo $_enddate = $enddate->add(new DateInterval('P' . $daysdration . 'D'))->format('Y-m-d');
                                                echo "</div>";
                                                $startComponents = explode('-', $date);
                                                $endComponents = explode('-', $_enddate);
                                                
                                                //$_POST['id'] = $theid;
                                                echo '<br><div>';
                                                $_city = $city['id'];
                                                $_c_id = $c_id;
                                                $_price = $city[$pricefield];
                                                $_y1 = $startComponents[0];
                                                $_m1 = $startComponents[1];
                                                $_d1 = $startComponents[2];
                                                $_y2 = $endComponents[0];
                                                $_m2 = $endComponents[1];
                                                $_d2 = $endComponents[2];
                                                
                                                $insertNewCourse = "INSERT INTO `course` 
                                                (`id`, `c_id`, `d1`, `m1`, `y1`, `d2`, `m2`, `y2`, `city`, `address`, `certain`, `price`, `visible`, `currency`,   `created_at`, `updated_at`, `published_at`, `deleted_at`) 
                                                VALUES 
                                                (NULL, '$_c_id', '$_d1', '$_m1', '$_y1', '$_d2', '$_m2', '$_y2', '$_city', NULL, NULL, '$_price', 'on', NULL, current_timestamp(), current_timestamp(), current_timestamp(), NULL);";
                                                mysqli_query($conn2, $insertNewCourse);
                                                // Close the wrapping div
                                                echo "<span style='border: none; padding: 0 5px;height: 20px;width:20px'>✅</span>";
                                                // Create a delete button ('X') styled with a red background, white text color, no border, and padding.
                                                
                            
                                            }
                                            echo "</td></tr>";
                                        }
                                    ?>
                                        
                                <?php } ?>
                            </tbody>
                        </table></div>
                        <!-- <button type="submit"  class="btn btn-primary float-right justify-content-end">Save</button>
                    <button type="button" id="saveAndNext" class="btn btn-primary float-right justify-content-end">Save & Next</button> -->
                
            </div>
                    
                </div>
            </div>
        <?php } ?>


    <?php
    

//////////////////////////////////////////////////////////////// end insertion



    }
    if(!$stopSearch){
        nextPage($c_id,$limit);
    }

}else{
    $query = "SELECT c_id FROM `course_main` WHERE c_id = $cid";
    $c_id = mysqli_query($conn2, $query);
    
    if($c_id = mysqli_fetch_assoc($c_id)){

        $cid = $c_id['c_id'];
        
    }else{
        echo "no course with this id<br><button class='btn btn-primary float-right justify-content-end' onclick='window.location.href = \"/event/addmultiple/\"'>Go To Add Multiple Events</button>";
        exit;
    }
}

if (isset($c_id) ) {
    $query = "SELECT c_id FROM `course_main` WHERE c_id > $c_id ORDER BY `c_id` ASC LIMIT 1";
    $c_id = mysqli_query($conn2, $query);
    if(mysqli_num_rows($c_id) != 0){
        $c_id = mysqli_fetch_assoc($c_id);
        $nxtcid = $c_id['c_id'];
    }else{
        $query = "SELECT c_id FROM `course_main` ORDER BY `c_id` ASC LIMIT 1";
        $c_id = mysqli_query($conn2, $query);
        $c_id = mysqli_fetch_assoc($c_id);
        $nxtcid = $c_id['c_id'];
    }
}
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

    function nextPage($next,$limit){
        echo "<script>
                setTimeout(function() {
                    window.location.href = '/event/addmultiple/$next/$limit';
                }, 3000); // Wait for 3000 milliseconds (3 seconds) before redirecting
            </script>";
    }

    
    ?>
    