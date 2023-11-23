<?php
if ($_SESSION['userlevel'] > 2 ) {
    // this code increments the dates based on the start date and end date

    $path = dirname(__FILE__);
    include $path.'/conf.php';   
    // $custom_select = "SELECT `id`, `c_id`, `d1`, `m1`, `y1`, `d2`, `m2`, `y2`, `city`, COUNT(*) AS `count`, TIMESTAMP(CONCAT(y1, '-', LPAD(m1, 2, '0'), '-', LPAD(d1, 2, '0'))) AS start_date, TIMESTAMP(CONCAT(y2, '-', LPAD(m2, 2, '0'), '-', LPAD(d2, 2, '0'))) AS end_date";
    // $custom_from = "FROM `course`";
    // $custom_where = "WHERE 1";
    // $custom_group = "GROUP BY `c_id`, `d1`, `m1`, `y1`, `d2`, `m2`, `y2`, `city` HAVING COUNT(*) > 1";
    // $custom_limit = "LIMIT 500";


    $query = "SELECT id AS ids_to_delete, id, c_id, city, price,d1, m1, y1, d2, m2, y2, DATE(TIMESTAMP(CONCAT(course.y1, '-', LPAD(course.m1, 2, '0'), '-', LPAD(course.d1, 2, '0')))) AS start_date, DATE(TIMESTAMP(CONCAT(course.y2, '-', LPAD(course.m2, 2, '0'), '-', LPAD(course.d2, 2, '0')))) AS end_date
        FROM course
        WHERE (c_id, d1, m1, y1, d2, m2, y2, city) IN (
            SELECT c_id, d1, m1, y1, d2, m2, y2, city
            FROM course
            GROUP BY c_id, d1, m1, y1, d2, m2, y2, city
            HAVING COUNT(*) > 1
        )
         ORDER BY RAND() LIMIT 4
        ";
        
        $querycnt = "SELECT id AS ids_to_delete, id, c_id, city, price,d1, m1, y1, d2, m2, y2, DATE(TIMESTAMP(CONCAT(course.y1, '-', LPAD(course.m1, 2, '0'), '-', LPAD(course.d1, 2, '0')))) AS start_date, DATE(TIMESTAMP(CONCAT(course.y2, '-', LPAD(course.m2, 2, '0'), '-', LPAD(course.d2, 2, '0')))) AS end_date
        FROM course
        WHERE (c_id, d1, m1, y1, d2, m2, y2, city) IN (
            SELECT c_id, d1, m1, y1, d2, m2, y2, city
            FROM course
            GROUP BY c_id, d1, m1, y1, d2, m2, y2, city
            HAVING COUNT(*) > 1
        )
         
        ";
    
    $records = mysqli_query($conn2, $query);
    $recordscnt = mysqli_query($conn2, $querycnt);
    
    $row_cnt = mysqli_num_rows($recordscnt);
    $row_cnt = $row_cnt / 2;
    echo "<h2>Found $row_cnt Duplicated Events</h2>";


    if (mysqli_num_rows($records) > 0) {
        ?>
        <script src="<?php echo $url;?>assets/plugins/datetimepicker/js/picker.js"></script>
        <script src="<?php echo $url;?>assets/plugins/datetimepicker/js/picker.date.js"></script>
        <?php
        // gets all the course_main and put them in an array to be used in the select as options
        $sql = "SELECT id,c_id, name,$DBweekname  FROM course_main ORDER BY name";
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
        
        
    ?>
        <div class="card">
            <div class="card-body">
                <h2>Found Duplicated Events</h2>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered dataTable no-footer display dataTable" role="grid">
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
                        <tbody>
                            <?php
                                while ($row = mysqli_fetch_assoc($records)) {
                                    $eid = $row['ids_to_delete'];
                                    ?> <form action="<?php echo $url; ?>event/updateduplicate" method="POST"> 
                                        <input type="hidden" name="id" value="<?php echo $eid ?>">
                                        <?php
                                    echo '<tr>';
                                    echo '<td>'.$eid.'</td>';
                                    ?>
                                    <td>
                                    <select name="c_id" class="single-select" role="textbox">
                                    <?php foreach ($courseMainOptions as $option) {
                                        $id = $option['id'];
                                        $courseName = $option['name'];
                                        $isSelected = $row['c_id'] == $id ? 'selected' : '';
                                        
                                        echo "<option data-weeks='".$option[$DBweekname]."' value='$id' $isSelected>$courseName</option>";
                                    } ?>
                                    </select>
                                    </td>
                                    <?php
                                    ?>
                                    <td>
                                    <select name="city" class="single-select" role="textbox">
                                    <?php foreach ($citiesOptions as $option) {
                                        $id = $option['id'];
                                        $cityName = $option['name'];
                                        $isSelected = $row['city'] == $id ? 'selected' : '';
                                        
                                        echo "<option value='$id' $isSelected>$cityName</option>";
                                    } ?>
                                    </select>
                                    </td>
                                    <?php
                                    echo '<td><input style="max-width:100px" class="form-control mb-3" type="number" step="100" name="price" value="'.$row['price'].'"></td>';
                                    ?><td>
                                    <input id="start-<?php echo $eid; ?>" class="result form-control" style="max-width:150px" type="text" placeholder="Start Date" aria-label="Start Date" name="startday" value="<?php echo $row['start_date']; ?>" required></td><?php
                                    ?><td>
                                    <input id="end-<?php echo $eid; ?>" class="result form-control" style="max-width:150px" type="text" placeholder="End Date" aria-label="End Date" name="endday" value="<?php echo $row['end_date']; ?>" required></td><?php
                                    echo '<td><button class="btn btn-primary float-right justify-content-end" data-id="'.$eid.'">Save</button></td>';
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
                                                var selectDate = new Date(context.select);
                                                selectDate.setDate(date.getDate() + <?php echo $daysdration; ?>);
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
                                    </script><?php
                                    echo '</tr></form>';
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php
        
        // $query = "SELECT GROUP_CONCAT(id) AS ids_to_delete
        //     FROM course
        //     WHERE (c_id, d1, m1, y1, d2, m2, y2, city) IN (
        //         SELECT c_id, d1, m1, y1, d2, m2, y2, city
        //         FROM course
        //         GROUP BY c_id, d1, m1, y1, d2, m2, y2, city
        //         HAVING COUNT(*) > 1
        //     )";
        
        // $records = mysqli_query($conn2, $query);

        // if (mysqli_num_rows($records) > 0) {
        //     $row = mysqli_fetch_assoc($records);
        //     $idsToDelete = $row['ids_to_delete'];
        
        //     // Delete duplicate rows except one based on all columns
        //     $sqlDelete = "DELETE FROM course WHERE id IN ($idsToDelete) AND id NOT IN (
        //         SELECT MIN(id) FROM course GROUP BY c_id, d1, m1, y1, d2, m2, y2, city HAVING COUNT(*) > 1
        //     )";
        
        //     mysqli_query($conn2, $sqlDelete);
        //     $deletedRowsCount = mysqli_affected_rows($conn2);
        
        //     if ($deletedRowsCount > 0) {
        //         echo "Duplicate rows deleted successfully.";
        //     } else {
        //         echo "No duplicate rows deleted.";
        //     }
        // } else {
        //     echo "No duplicate rows found.";
        // }
    }else{
        echo "<h2>No duplicate rows found.</h2>";
    }
?>
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
    <?php
}
?>