<?php $folderName =  basename(__DIR__); 
    $path = dirname(__FILE__);
include $path.'/conf.php'; ?>
        <script src="<?php echo $url;?>assets/plugins/datetimepicker/js/picker.js"></script>
        <script src="<?php echo $url;?>assets/plugins/datetimepicker/js/picker.date.js"></script>
        <div class="row">
    <div class="col-10">
        <h1>Fix Duplicates</h1>
    </div>
</div>
<div class="row">
    <div class="col-10 d-inline-flex"></div>
    <div class="col-2 ">
        <!-- <a href="<?php //echo $url ; ?>event/fixdurations/auto" class="btn btn-primary float-right justify-content-end">Automatic <i class="lni lni-reload"></i></a> -->
    </div>
</div>

<hr />
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
                            <th>link</th>
                            <th>edit</th>
                            <th>trash</th>
                            <th>delete</th>
                            <th>save</th>
                        </tr>
                    </thead>
                <!-- Your table content will be filled by DataTables -->
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        var dataTable =$('#empTable').DataTable({
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
                { data: 'coursename' },
                { data: 'cityname' },
                { data: 'price' },
                { data: 'start_date' },
                { data: 'end_date' },
                { data: 'Link'},
                { data: 'Edit'},
                { data: 'Trash'},
                { data: 'Delete'},
                { data: 'Save' },
            ],
            // Add paging options
            lengthMenu: [[10, 25, 50, 100, 200, 500, -1], [10, 25, 50, 100, 200, 500,'All']],
            pageLength: 10,
            dom: 'lBfrtip',
            buttons: [
                'copy','excel' //{
                    // extend: 'excel',
                    // exportOptions: {
                    //     columns: [<?php //foreach ($columnNamesdisplay as $columnName){ ?>'<?php //echo $columnName ?>', <?php //} ?>] 
                    // }
                //}
                , 'pdf', 'print'
            ]

            // Add any additional DataTable options as needed
        });
    
        // setInterval(function() {
        //     dataTable.draw(false);
        // }, 10000); // 10000 milliseconds = 10 seconds
        

    // Initialize end date picker


    $('#empTable tbody').on('click', 'tr .start', function () {
        var rowdata = dataTable.row($(this).parent().parent()).data();
        var data = $(this).val();
        if (data) {
            let daysdration = 4;
            // Assuming your date columns are 'start_date' and 'end_date'
            var startDate = data;
            var endDate = data;
            switch (rowdata.duration) {
                case 2:
                    daysdration = 12;
                    break;
                case 3:
                    daysdration = 18;
                    break;
                case 4:
                    daysdration = 26;
                    break;
                // default case is 1
                default:
                    daysdration = 5;
            }
            var end = $(this).closest('tr').find('.end');
            var start = $(this).pickadate({
                selectMonths: true,
                selectYears: true,
                format: 'yyyy-mm-dd',
                onSet: function (context) {
                    // Find the corresponding end date input field and update its value
                    var startDate = context.select;
                    var endDate = new Date(startDate);
                    endDate.setDate(endDate.getDate() + daysdration);
                    // Format the end date and set it as the value of the end input
                    end.val(endDate.toISOString().split('T')[0]); // Format as yyyy-mm-dd
                    
                    
                }
            });
            // Set the start and end dates based on the selected row's dates
            // end.pickadate('picker').set('select', endDate);
        }
    });

    $('#empTable tbody').on('click', 'tr .save', function () {
        var rowdata = dataTable.row($(this).parent().parent()).data();
        var formData = {
            id: rowdata.ids_to_delete,
            c_id: rowdata.c_id,
            city: rowdata.city,
            crsname : rowdata.course_name,
            ctyname: rowdata.city_name,
            price: $(this).closest('tr').find('input[name="price"]').val(),
            startday: $(this).closest('tr').find('.start').val(),
            endday: $(this).closest('tr').find('.end').val()
        };
        $.ajax({
            type: 'POST',
            url: "<?php echo $url; ?>event/updateduplicate", // Use the form's action attribute as the URL
            data: formData,
            success: function(response) {
                // Handle the AJAX response, e.g., display a success message
                // console.log(response);
                success_noti("success edited.")
                dataTable.draw(false);
            },
            error: function(error) {
                // Handle errors, e.g., display an error message
                console.error(error);
                error_noti(error)
            }
        });
        // console.log(formData);
        
    });


    

$(document).on('click', '.delete-link', function(event) {
        event.preventDefault();
        if (confirm('Are you sure you want to delete this item?')) {
            let success = deleteItem($(this).data('id'));
            if(success){
                $(this).closest('tr').remove();
            }
        }
    });

    $(document).on('click', '.trash-link', function(event) {
    event.preventDefault();
    var $this = $(this); // Cache the jQuery object
    if (confirm('Are you sure you want to trash this item?')) {
         trashItem($(this).data('id'),$(this)) ;
    }
});

// Function to handle delete action
function deleteItem(id) {

    $.ajax({
        type: 'GET',
        url: '<?php echo $url . 'event/delete/' ?>' + id,
        contentType: 'application/x-www-form-urlencoded',
        success: function(response) {
            if (response.message == "Delete successful." ) {
                success_noti("successully deleted.")
                dataTable.draw(false);
            } else {
                error_noti(response.message);
            }
        },
        error: function() {
            error_noti("Failed to delete the row.");
            success = false;
        }
    });
}

// Function to handle trash action
function trashItem(id, $this) {
    // Make an AJAX request to the delete endpoint
    $.ajax({
        type: 'GET',
        url: '<?php echo $url . 'event/trash/' ?>' + id,
        contentType: 'application/x-www-form-urlencoded',
        success: function(response) {
            console.log( response);
            if (response.un ) {
                success_noti("successully untrashed.")
            } else {
                success_noti("successully trashed.")
            }
            dataTable.draw(false);
            if (typeof adminTable !== 'undefined') {
                adminTable.draw(false);
            }
        },
        error: function() {
            error_noti("Failed to trash the row.");
        }
    });
}});  
</script>
