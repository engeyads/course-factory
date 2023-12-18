<?php
session_start();


include '../../include/functions.php';
include '../../include/db.php';
if (!isset($_SESSION['db']) || empty($_SESSION['db'])) {
    die("Error: Database session not set or empty.");
}

switch($_SESSION['db_name']){
    case 'agile4training':
        $DBweekname = 'week';
        $newdbstype = true;
    break;
    case 'agile4training ar':
        $DBweekname = 'week';
        $newdbstype = true;
    break;
    case 'blackbird-training':
        $DBweekname = 'weeks';
        $newdbstype = false;
    break;
    case 'blackbird-training.co.uk':
        $DBweekname = 'week';
        $newdbstype = true;
    break;
    case 'mercury english':
        $DBweekname = 'week';
        $newdbstype = false;
    break;
    case 'mercury arabic':
        $DBweekname = 'week';
    break;
    case 'Euro Wings En':
        $DBweekname = 'weeks';
        $newdbstype = false;
    break;
    case 'Euro Wings Ar':
        $DBweekname = 'weeks';
        $newdbstype = false;
    break;
    default:
    $DBweekname = 'week';
    $newdbstype = false;
    break;
}
//error_reporting(E_ALL);
$lvl = $_SESSION['userlevel'];
  
    $query = "SELECT id AS ids_to_delete, id, c_id, city, price,d1, m1, y1, d2, m2, y2, DATE(TIMESTAMP(CONCAT(course.y1, '-', LPAD(course.m1, 2, '0'), '-', LPAD(course.d1, 2, '0')))) AS start_date, DATE(TIMESTAMP(CONCAT(course.y2, '-', LPAD(course.m2, 2, '0'), '-', LPAD(course.d2, 2, '0')))) AS end_date
        FROM course
        WHERE (c_id, d1, m1, y1, d2, m2, y2, city) IN (
            SELECT c_id, d1, m1, y1, d2, m2, y2, city
            FROM course
            GROUP BY c_id, d1, m1, y1, d2, m2, y2, city
            HAVING COUNT(*) > 1
        )
         ORDER BY RAND() LIMIT 50
        ";
    $records = mysqli_query($conn2, $query);
    
    if (mysqli_num_rows($records) > 0) {
        
        // gets all the course_main and put them in an array to be used in the select as options
        $sql = "SELECT `id`,`c_id`, `name`, `$DBweekname`  FROM `course_main` ORDER BY `name`";
        $courseMain = mysqli_query($conn2, $sql);
        while ($row = mysqli_fetch_assoc($courseMain)) {
            $id = $row['c_id'];
            $courseName = $row['name'];
            switch ($row[$DBweekname]){
                case 2:
                $daysdration = 11;
                break;
                case 3:
                $daysdration = 17;
                break;
                case 4:
                $daysdration = 25;
                break;
                // default case is 1
                default: $daysdration = 4;
            }
            $courseMainOptions[] = array(
                'id' => $id,
                'name' => $courseName,
                "$DBweekname" => $daysdration
            );
        }
        // gets all the cities and put them in an array to be used in the select as options
        $sql = "SELECT id, name,monday FROM cities ORDER BY name";
        $cityies = mysqli_query($conn2, $sql);
        while ($row = mysqli_fetch_assoc($cityies)) {
            $id = $row['id'];
            $cityName = $row['name'];
            
            $citiesOptions[] = array(
                'id' => $id,
                'name' => $cityName
            );
        }
        
        
    echo '<div id="thetable"><table  class="table table-striped table-bordered dataTable no-footer display dataTable" role="grid">
        <thead>
            <tr>
                <th>ID</th>
                <th>Course</th>
                <th>City</th>
                <th>Price</th>
                <th>Start Date</th>
                <th>end Date</th>
                
            </tr>
        </thead>
        <tbody>';           
            while ($row = mysqli_fetch_assoc($records)) { 
                $eid = $row['ids_to_delete'];

                echo '<tr data-id="'.$eid.'">';
                echo '<td>'.$eid.'</td>';
                
                echo '<td>';
                
                echo '<select name="c_id" class="single-select form-select float-right justify-content-end table-select" role="textbox">';
                foreach ($courseMainOptions as $option) {
                    $id = $option['id'];
                    $courseName = $option['name'];
                    $isSelected = $row['c_id'] == $id ? 'selected' : '';
                    $optionWeeks = $option[$DBweekname];

                    echo "<option data-week='$daysdration' data-weeks='$optionWeeks' value='$id' $isSelected>$courseName</option>";
                }
                echo '</select>
                </td>
                <td>
                <select class="single-select form-select float-right justify-content-end table-select" name="city" required="" data-select2-id="course_main" tabindex="-1" aria-hidden="true">';
                foreach ($citiesOptions as $option) {
                    $id = $option['id'];
                    $cityName = $option['name'];
                    $isSelected = $row['city'] == $id ? 'selected' : '';
                    echo "<option value='$id' $isSelected>$cityName</option>";
                }
                echo '</select>
                </td>
                <td><input style="max-width:100px" class="form-control mb-3" type="number" step="100" name="price" value="'.$row['price'].'"></td>
                <td>
                <input id="start-'. $eid.'" class="result form-control" style="max-width:150px" type="text" placeholder="Start Date" aria-label="Start Date" name="startday" value="'. $row['start_date'].'" required></td>
                <td>
                <input id="end-'.$eid.'" class="result form-control" style="max-width:150px" type="text" placeholder="End Date" aria-label="End Date" name="endday" value="'.$row['end_date'].'" required></td>
                <td><button class="btn btn-primary float-right justify-content-end save" data-id="'.$eid.'">Save</button></td>';
                ?><script>
                    <?php if(!isset($strict)){
                        echo '"use strict";';
                        $strict = 1;
                    } ?>
                    
                    var start<?php echo $eid; ?> = $('#start-<?php echo $eid; ?>').pickadate({
                        selectMonths: true,
                        selectYears: true, 
                        format: 'yyyy-mm-dd',
                        onSet: function(context) {
                            var date = new Date(context.select);

                            // Retrieve data-week attribute from the selected option
                            var selectedOption = $('select[name="c_id"]').find(':selected');
                            var optionWeeks = selectedOption.data('weeks');
                            var optionWeek = selectedOption.data('week');

                            // Calculate new date based on optionWeek
                            var selectDate = new Date(context.select);
                            selectDate.setDate(date.getDate() + optionWeek);
                            $('#end-<?php echo $eid; ?>').val(selectDate.getFullYear()+'-'+(selectDate.getMonth() + 1)+'-'+selectDate.getDate());
                            end<?php echo $eid; ?>.pickadate('picker').set('select', selectDate);
                            end<?php echo $eid; ?>.pickadate('picker').set('min', date);
                        }
                    });
                    
                    var end<?php echo $eid; ?> = $('#end-<?php echo $eid; ?>').pickadate({
                        selectMonths: true,
                        selectYears: true, 
                        format: 'yyyy-mm-dd',
                        onSet: function(context) {
                            
                        }
                    });
                    end<?php echo $eid; ?>.pickadate('picker').set('min', '<?php echo $row['start_date']; ?>');
                </script>
                <?php echo '</tr>';
            }
                        
            echo '</tbody>
        </table></div>';
        }
        ?>
        <script>
            $(document).ready(function() {
                $('.save').on('click', function(e) {
                    e.preventDefault(); // Prevent default form submission

                    var row = $(this).closest('tr');
                    var formData = {
                        id: row.data('id'),
                        c_id: row.find('select[name="c_id"]').val(),
                        city: row.find('select[name="city"]').val(),
                        price: row.find('input[name="price"]').val(),
                        startday: row.find('input[name="startday"]').val(),
                        endday: row.find('input[name="endday"]').val()
                    };
                    console.log(formData);
                    $.ajax({
                        type: 'POST',
                        url: "<?php echo $url; ?>event/updateduplicate", // Use the form's action attribute as the URL
                        data: formData,
                        success: function(response) {

                            $('#table-responsive').load('<?php echo $url; ?>tables/event/fixduplicatetable.php', function() {
                                // Callback function is executed after content is loaded
                                // Initialize select2 on select elements

                                var thetable = $(this).find('#thetable');
                                var specificContent = $(this).find('#thecnt');
                                $('.cnt').html(specificContent.html());
                                $(this).html(thetable.html());
                                
                            });

                        },
                        error: function(error) {
                            // Handle errors, e.g., display an error message
                            console.error(error);
                        }
                    });
                });
            });
        </script>
<?php
$querycnt = "SELECT id AS ids_to_delete, id, c_id, city, price,d1, m1, y1, d2, m2, y2, DATE(TIMESTAMP(CONCAT(course.y1, '-', LPAD(course.m1, 2, '0'), '-', LPAD(course.d1, 2, '0')))) AS start_date, DATE(TIMESTAMP(CONCAT(course.y2, '-', LPAD(course.m2, 2, '0'), '-', LPAD(course.d2, 2, '0')))) AS end_date
    FROM course
    WHERE (c_id, d1, m1, y1, d2, m2, y2, city) IN (
        SELECT c_id, d1, m1, y1, d2, m2, y2, city
        FROM course
        GROUP BY c_id, d1, m1, y1, d2, m2, y2, city
        HAVING COUNT(*) > 1
    )
     
    ";
    $recordscnt = mysqli_query($conn2, $querycnt);
    echo "<div id='thecnt'>";
    if($row_cnt = mysqli_num_rows($recordscnt)){

        $row_cnt = $row_cnt / 2;
        ?>

        
        <div class="alert border-0 bg-light-warning alert-dismissible fade show py-2">
        <div class="d-flex align-items-center">
            <div class="fs-3 text-warning"><i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div class="ms-3">
            <div class="text-warning"> <?php  echo "Found ".$row_cnt; ?> !!</div>
            </div>
        </div>
        </div>
        <?php }else{ ?>
        <div class="alert border-0 bg-light-success alert-dismissible fade show py-2">
        <div class="d-flex align-items-center">
            <div class="fs-3 text-success"><i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="ms-3">
            <div class="text-success">Everything is ok! keep going.</div>
            </div>
        </div>
        </div>
        <?php } 
    echo "</div>";
    ?>