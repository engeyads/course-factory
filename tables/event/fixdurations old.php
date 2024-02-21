<?php 
    $auto = isset($_GET['auto']) ? ($_GET['auto'] == 'true' ? true : false) : false;
    $path = dirname(__FILE__);
    include $path.'/conf.php';
    ?>  
<div class="row">
    <div class="col-10">
        <h1>Fix Durations</h1>
    </div>
</div>
<div class="row">
    <div class="col-10 d-inline-flex"></div>
    <div class="col-2 ">
        <a href="<?php echo $url ; ?>event/fixdurations/auto" class="btn btn-primary float-right justify-content-end">Automatic <i class="lni lni-reload"></i></a>
    </div>
</div>

<hr />
<div class="card">
    <div class="card-body">
        <script src="<?php echo $url; ?>assets/makitweb/jquery-3.3.1.min.js"></script>
        <script src="<?php echo $url; ?>assets/makitweb/DataTables/datatables.min.js"></script>
        <?php
        // get all course group by d1,m1,y1 and d2,m2,y2
        $query = "SELECT course.id, course.d1, course.m1, course.y1, course.d2, course.m2, course.y2, course_main.$DBweekname,
                TIMESTAMPDIFF(DAY, CONCAT(y1, '-', m1, '-', d1), CONCAT(y2, '-', m2, '-', d2)) + 1 AS days_diff 
                FROM `course` 
                left join course_main on course.c_id = course_main.c_id
                HAVING (days_diff != 5 AND days_diff != 12 AND days_diff != 18 AND days_diff != 26) limit 25";

        $result = mysqli_query($conn2, $query);
        if ($result && mysqli_num_rows($result) > 0) { ?>
        <div class="table-responsive">
            <div id="example_wrapper" class="dataTables_wrapper dt-bootstrap5">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>start</th>
                        <th>end</th>
                        <th>weeks</th>
                        <th>duration</th>
                        <?php if(!$auto){ ?>   
                            <th></th>
                            <th></th>
                        <?php } ?>
                        <!-- <th>count</th> -->
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { 
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
                    }?>
                    <tr>
                        <input type="hidden" name="id" value="<?php echo $id = $row['id']; ?>">
                        <input type="hidden" name="currentDuration" value="<?php echo $days_diff = $row['days_diff']; ?>">
                        <td><?php echo $id; ?></td>
                        <?php
                        $d1 = $row['d1'];
                        $m1 = $row['m1'];
                        $y1 = $row['y1'];
                        ?>
                        <td><?php echo $y1.'-'.$m1.'-'.$d1; ?></td>
                        <?php
                        $d2 = $row['d2'];
                        $m2 = $row['m2'];
                        $y2 = $row['y2'];
                        ?>
                        <td><?php echo $y2.'-'.$m2.'-'.$d2; ?></td>
                        <td><?php echo $weeks = $row[$DBweekname]; ?></td>
                        
                        <?php 

                        // echo '<td>'.$row_count = $row['row_count'];echo '</td>';
                    
                    if($auto){

                        $sqls = "UPDATE course 
                        SET 
                            d2 = DAY(DATE_ADD(CONCAT(y1, '-', m1, '-', d1), INTERVAL $daysdration DAY)),
                            m2 = MONTH(DATE_ADD(CONCAT(y1, '-', m1, '-', d1), INTERVAL $daysdration DAY)),
                            y2 = YEAR(DATE_ADD(CONCAT(y1, '-', m1, '-', d1), INTERVAL $daysdration DAY))
                        WHERE id = '$id'";
                        mysqli_query($conn2, $sqls);

                        
                        ?>
                        <td><b><?php echo $daysdration; ?></b> 
                        <?php if (mysqli_affected_rows($conn2) > 0) { ?>
                            ✅
                        <?php } else { ?>
                            ❌
                        <?php } ?>
                        </td><?php
                        header("Refresh: 5; url=" . $url . "event/fixdurations/auto");
                    }else{ ?>
                        <td><input style="max-width:100px" class="form-control mb-3" type="number" name="newDuration" placeholder="<?php echo $days_diff; ?>" value="<?php echo $days_diff; ?>"></td>

                        <td><b><span role="button" class="btn btn-secondary px-5 newDuration"><?php echo $daysdration;  ?></span></b></td>
                        <td><button class="btn btn-primary float-right justify-content-end save" data-id="<?php echo $daysdration; ?>">Save</button></td><?php
                    }
                    ?></tr><?php
                }
                ?>
                </tbody>
                </table>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.save').click(function() {
            let currentDuration = $(this).closest('tr').find('input[name="currentDuration"]').val();
            let id = $(this).closest('tr').find('input[name="id"]').val();
            let newDuration = $(this).closest('tr').find('input[name="newDuration"]').val();
            var data = {
                id: id,
                newDuration: newDuration,
            };
            
            var confirmation = confirm('Are you sure you want to change Duration for Event id ('+id+') from ('+currentDuration+') days to be ('+newDuration+') days?');
            if (confirmation) {
                $.ajax({
                    type: "POST",
                    url: "updateDuration", 
                    data: data,
                    success: function (response) {
                        console.log(response);
                        if (response == "success") {
                            $(this).closest('tr').find('input[name="currentDuration"]').val($(this).text().trim());
                            $(this).closest('tr').find('.form-control.newDuration').val($(this).text().trim());
                            $(this).remove();
                        } else {
                            alert("Failed to delete the row.");
                        }
                    },
                    failed: function (response) {
                        alert("Failed to delete the row.");
                    }
                });
            }
        });

        $('.newDuration').click(function() {
            let currentDuration = $(this).closest('tr').find('input[name="currentDuration"]').val();
            let id = $(this).closest('tr').find('input[name="id"]').val();
            let newDuration = $(this).text();
            var data = {
                id: id,
                newDuration: newDuration,
            };
            
            var confirmation = confirm('Are you sure you want to change Duration for Event id ('+id+') from ('+currentDuration+') days to be ('+newDuration+') days?');
            if (confirmation) {
                $.ajax({
                    type: "POST",
                    url: "updateDuration", 
                    data: data,
                    success: function (response) {
                        if (response == "success") {
                            $(this).closest('tr').remove();
                        } else {
                            alert("Failed to delete the row.");
                        }
                    },
                    failed: function (response) {
                        alert("Failed to delete the row.");
                    }
                });
            }
        });
        
        var table1 = $('#example').DataTable({ 
            buttons: ['copy', 'excel', 'pdf', 'print'],
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100, 200, 500, -1], [10, 25, 50, 100, 200, 500, "All"]]
            
        });

        table1.buttons().container().appendTo('#example_wrapper .col-md-6:eq(0)');
    });
</script>