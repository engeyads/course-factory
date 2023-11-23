<?php
if ($_SESSION['userlevel'] > 2 ) {
    // this code increments the dates based on the start date and end date
    $path = dirname(__FILE__);
    include $path.'/conf.php';   
    $custom_select = "`course`.`id`,`course_main`.`$DBweekname` ,TIMESTAMP(CONCAT(y1, '-', LPAD(m1, 2, '0'), '-', LPAD(d1, 2, '0'))) AS start_date, TIMESTAMP(CONCAT(y2, '-', LPAD(m2, 2, '0'), '-', LPAD(d2, 2, '0'))) AS end_date";
    $custom_where = " TIMESTAMP(CONCAT(y1, '-', LPAD(m1, 2, '0'), '-', LPAD(d1, 2, '0'))) <= CURDATE()";
    $query = "SELECT $custom_select FROM `course` left join `course_main` on course.c_id = course_main.c_id
    WHERE $custom_where limit 25";
    $records = mysqli_query($conn2, $query);
    // if mysqli_num_rows($records) > 0
?>
<?php if (mysqli_num_rows($records) > 0) { ?>
        <div class="card">
            <div class="card-body">
                <h2>Found Doplicated Events</h2>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered dataTable no-footer display dataTable" role="grid">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Start Date</th>
                                <th>New Start Date</th>
                                <th>end Date</th>
                                <th>New end Date</th>
                                <th>Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                while ($row = mysqli_fetch_assoc($records)) {
                                    echo '<tr>';
                                    echo '<td>'.$row['id'].'</td>';  
                                    $eventDate =  date_create($row['start_date']) ;
                                    $eventDate->add(new DateInterval('P52W'));
                                    $resultDate = $eventDate->format('Y-m-d H:i:s');
                                    $resultDated = new DateTime($resultDate);
                                    $y1 = $resultDated->format('Y');
                                    $m1 = $resultDated->format('m');
                                    $d1 = $resultDated->format('d');
                                    $eventDate =  $row['start_date'] ;
                                    echo '<td>'.$row['start_date'].'</td>';
                                    echo '<td>'.$resultDate.'</td>';
                                    switch ($row[$DBweekname]){
                                        case 2:
                                        $duration = 11;
                                        break;
                                        case 3:
                                        $duration = 17;
                                        break;
                                        case 4:
                                        $duration = 25;
                                        break;
                                        // default case is 1
                                        default: $duration = 4;
                                    }
                                    $endDate = new DateTime($resultDate);
                                    $endDate->add(new DateInterval('P' . $duration . 'D'));
                                    $resultEndDate = $endDate->format('Y-m-d H:i:s');
                                    $y2 = $endDate->format('Y');
                                    $m2 = $endDate->format('m');
                                    $d2 = $endDate->format('d');
                                    echo '<td>'.$row['end_date'].'</td>';
                                    echo '<td>'.$resultEndDate.'</td>';
                                    $sql = "UPDATE course 
                                        SET y1 = '$y1', m1 = '$m1', d1 = '$d1' , d2 = $y2,m2 = $m2,y2 = $d2, certain=null
                                    WHERE id = '".$row['id']."'";
                                    //mysqli_query($conn2, $sql);
                                    //mysqli_commit($conn2);
                                    ?><td><?php
                                    if (mysqli_affected_rows($conn2) > 0) {
                                        echo "✅";
                                    } else {
                                        echo "❌";
                                    }
                                    ?></td></tr><?php
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
<?php } ?>
<?php
    if(isset($resultDate) && $resultDate != null){
        echo "Date after adding 52 weeks: " . $resultDate . PHP_EOL;
    }else{
    echo "<h1>All events are up to date.</h1>";
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