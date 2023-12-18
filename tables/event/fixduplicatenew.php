<?php $folderName =  basename(__DIR__); ?>
        <script src="<?php echo $url;?>assets/plugins/datetimepicker/js/picker.js"></script>
        <script src="<?php echo $url;?>assets/plugins/datetimepicker/js/picker.date.js"></script>
        <div class="card">
    <div class="card-body">
        <div class="table-responsive">
        <div >
            <!-- Table -->
            <table id='empTable' class='table table-striped table-bordered dataTable no-footer display dataTable'>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Course ID</th>
                        <th>City</th>
                        <th>Price</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                    </tr>
                </thead>
        <!-- Your table content will be filled by DataTables -->
    </table>

    <script>
        $(document).ready(function(){
                $('#empTable').DataTable({
                    'processing': true,
                    'serverSide': true,
                    'serverMethod': 'POST',
                    'headers': {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-Requested-Session': '<?php echo session_id(); ?>'
                    },
                    'ajax': {
                        'url':'/tables/event/get_duplicates.php',
                        'type': 'POST',
                        'data': function (data) {
                            data.start = data.start;
                            data.length = data.length;
                        }
                        
                    },
                columns: [
                    { data: 'ids_to_delete' },
                    { data: 'c_id' },
                    { data: 'city' },
                    { data: 'price' },
                    { data: 'start_date' },
                    { data: 'end_date' }
                ]
                // Add any additional DataTable options as needed
            });
        });
    </script>
