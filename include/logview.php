<?php
// Query the admin_log table

if(!isset($adminajaxview)){
    $adminajaxview = false;
}

?>

<h1>Admin Log</h1>
<hr />
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
        <?php 
        if($adminajaxview){ 
            $adminTable = 'admin_log';
            $adminColumns = ['user'
            ,'row_id'
            ,'columnname'
            ,'oldData'
            ,'newData'
            ,'action'
            ,'date'];
        ?>
        <!-- jQuery Library -->
        <script src="<?php echo $url ?>assets/makitweb/jquery-3.3.1.min.js"></script>
        <!-- Datatable JS -->
        <script src="<?php echo $url ?>assets/makitweb/DataTables/datatables.min.js"></script>
            <div >
            <!-- Table -->
            <table id='empTable1' class='table table-striped table-bordered dataTable no-footer display dataTable'>
                    
                    <thead>
                        <tr>
                            <th>User</th> 
                            <th>ID</th> 
                            <th>Field</th>
                            <th>Old Value</th>
                            <th>New Value</th>
                            <th>Action</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                </table>
            </div>
        
        <!-- Script -->
        <script>
        

            $(document).ready(function(){
                $('#empTable1').DataTable({
                    'processing': true,
                    'serverSide': true,
                    'order': [[6, 'desc']],
                    'serverMethod': 'post',
                    'headers': {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-Requested-Session': '<?php echo session_id(); ?>'
                    },
                    'ajax': {
                        'url':'/include/adminajaxfile.php',
                        'data': function (data) {
                            data.start = data.start;
                            data.length = data.length;
                            // Include the table name in the request data
                            data.tablename = '<?php echo $adminTable; ?>';
                            data.fortablename = '<?php echo $tablename; ?>';
                            data.columns = '<?php echo implode(",", $adminColumns); ?>';

                        }
                    },
                    'columns': [
<?php foreach ($adminColumns as $columnName){ ?>
                        { data: '<?php echo $columnName ?>'},
<?php } ?>
                        
],
        // Add paging options
        lengthMenu: [[10, 25, 50, 100, 200, 500, -1], [10, 25, 50, 100, 200, 500]],
        pageLength: 25,
dom: 'lBfrtip',
        buttons: [
            'copy', 'excel', 'pdf', 'print'
        ]
                });
            });
        </script>
            <?php 
            }else{ 
                $adminLogQuery = "SELECT * FROM admin_log WHERE tablename = '$tablename' ORDER BY date DESC";
                $adminLogResult = mysqli_query($theconnection, $adminLogQuery);
                if (!$adminLogResult) {
                    echo "Error: " . mysqli_error($theconnection); 
                }
                ?>
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                        <tr>
                            <th>User</th> 
                            <th>ID</th> 
                            <th>Field</th>
                            <th>Old Value</th>
                            <th>New Value</th>
                            <th>Action</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                <tbody>
                    <?php
                    // Fetch and print each row
                    while ($adminLog = mysqli_fetch_assoc($adminLogResult)) {
                        echo "<tr>";
                        echo "<td>" . $adminLog['user'] . "</td>";
                        echo "<td>" . $adminLog['row_id'] . "</td>";
                        echo "<td>" . $adminLog['columnname'] . "</td>";
                        echo "<td>" . $adminLog['oldData'] . "</td>";
                        echo "<td>" . $adminLog['newData'] . "</td>";
                        echo "<td>" . $adminLog['action'] . "</td>";                        
                        echo "<td>" . $adminLog['date'] . "</td>";
                        echo "</tr>";
                    }
                    // Free result set
                    mysqli_free_result($adminLogResult);
                    ?>
                </tbody>
                
            </table>
            <?php } ?>
        </div>
    </div>
</div>