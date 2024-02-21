<?php $folderName =  basename(__DIR__); 
    $path = dirname(__FILE__);
include $path.'/conf.php'; ?>
        <script src="<?php echo $url;?>assets/plugins/datetimepicker/js/picker.js"></script>
        <script src="<?php echo $url;?>assets/plugins/datetimepicker/js/picker.date.js"></script>
        <div class="row">
    <div class="col-10">
        <h1>Fix Price</h1>
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
                            <th style="width:50%">City</th>
                            <th style="width:3%">Year</th>
                            <th style="width:13%">Price</th>
                            <th style="width:13%">Standard</th>
                            <th style="width:3%">Ratio</th>
                            <th style="width:3%">Weeks</th>
                            <th style="width:3%">Class</th>
                            <th style="width:3%">Count</th>
                            <th style="width:3%"></th>
                            <th style="width:3%"></th>
                            <th style="width:3%"></th>
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
                    'url':'/tables/event/get_prices.php',
                    'type': 'POST',
                    'data': function (data) {
                        data.start = data.start;
                        data.length = data.length;
                    }
                    
                },
            columns: [
                { data: 'City' },
                { data: 'Year' },
                { data: 'Price' },
                { data: 'Standard' },
                { data: 'Ratio' },
                { data: 'Weeks' },
                { data: 'Class' },
                { data: 'Count' },
                { data: 'Link'},
                { data: 'Delete'},
                { data: 'Save' },
            ],
            "columnDefs": [
                // { "visible": false, "targets": [8,9,10 ] },
                { "orderable": false, "targets": [8,9,10 ] }
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
        let rowdata = dataTable.row($(this).parent().parent()).data();
        var formData = {
            ctyname: rowdata.city_name,
            newprice: $(this).parent().parent().find('.theprice').val(),
            price: rowdata.price,
            city: rowdata.city,
            y1: rowdata.year,
            class: rowdata.class,
            citypricecellnm: rowdata.citypricecellnm,
            [rowdata.citypricecell]: rowdata.citypricecell,
            <?php echo $DBweekname ?>: rowdata.weeks,

        };
        
        $.ajax({
            type: 'POST',
            url: "<?php echo $url; ?>event/updateprice", // Use the form's action attribute as the URL
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

    $('#empTable tbody').on('click', 'tr .newprice', function () {
        let rowdata = dataTable.row($(this).parent().parent().parent()).data();
        var formData = {
            ctyname: rowdata.city_name,
            newprice: rowdata.citypricecellnm,
            price: rowdata.price,
            city: rowdata.city,
            y1: rowdata.year,
            class: rowdata.class,
            citypricecellnm: rowdata.citypricecellnm,
            [rowdata.citypricecell]: rowdata.citypricecell,
            <?php echo $DBweekname ?>: rowdata.weeks,

        };
        
        $.ajax({
            type: 'POST',
            url: "<?php echo $url; ?>event/updateprice", // Use the form's action attribute as the URL
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
        var rowdata = dataTable.row($(this).parent().parent()).data();

        var year=rowdata.year ;
        var price=rowdata.price ;
        var city=rowdata.city ;
        var classs=rowdata.class ;
        var week=rowdata.weeks ;
        var citypricecell=rowdata.citypricecell ;
        var cityprice=rowdata.cityprice;

        if (confirm('Are you sure you want to delete this items?')) {
            let success = deleteItem(year, price, city, classs, week, citypricecell, cityprice);
            if(success){
                $(this).closest('tr').remove();
            }
        }
    });

    

    // Function to handle delete action
    function deleteItem(year ,price ,city ,classs ,week ,citypricecell ,cityprice ) {

        $.ajax({
            type: 'POST',
            url: '<?php echo $url . 'event/deleteprice' ?>',
            contentType: 'application/x-www-form-urlencoded',
            data:{
                y1 : year ,
                price : price ,
                city : city ,
                class : classs ,
                <?php echo $DBweekname ?>: week,
                citypricecell : citypricecell ,
                citypricecellnm : citypricecell ,
                cityprice : cityprice
            },
            success: function(response) {
                if (response == 1) {
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


    

});  
let isMousePressed = false;

function setStep(elm,stepValue) {
    const input = elm;
    input.step = stepValue;
    isMousePressed = true;
}

function resetStep(elm) {
    const input = elm;
    input.step = 'any';
    isMousePressed = false;
}

function handleKeyDown(event, input) {
    if (isMousePressed) {
        // If the mouse is pressed, do nothing on key press
        event.preventDefault();
    }

    if (event.key === 'ArrowUp' || event.key === 'ArrowDown') {
        // Handle up and down arrow keys
        const step = 100;
        const currentValue = parseFloat(input.value);
        const adjustedValue = event.key === 'ArrowUp' ? currentValue + step : currentValue - step;
        input.value = adjustedValue;
        event.preventDefault();
    }
}
</script>
