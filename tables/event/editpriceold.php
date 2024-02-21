<?php $folderName =  basename(__DIR__); 
if ($_SESSION['userlevel'] > 2 ) {

    $path = dirname(__FILE__);
    include $path.'/conf.php';
    // this code increments the dates based on the start date and end date

    // if mysqli_num_rows($records) > 0
?>

<div class="row">
    <div class="col-10">
        <h1>Fix Prices</h1>
    </div>
</div>
<div class="row">
    <div class="col-10 d-inline-flex"></div>
    <div class="col-2 ">
        <!-- <a href="<?php echo $url ; ?>event/fixdurations/auto" class="btn btn-primary float-right justify-content-end">Automatic <i class="lni lni-reload"></i></a> -->
    </div>
</div>

<hr />


        <script src="<?php echo $url; ?>assets/plugins/datetimepicker/js/picker.js"></script>
        <script src="<?php echo $url; ?>assets/plugins/datetimepicker/js/picker.date.js"></script>
        <div class="card">
    <div class="card-body">
        <h2>Event Renew Price Bulk</h2>
        <div class="table-responsive">
        <div >
            <!-- Table -->
            <table id='empTable' class='table table-striped table-bordered dataTable no-footer display dataTable'>

                    <thead>
                        <tr>
                            <th>City</th>
                            <th>year</th>
                            <th>Price</th>
                            <th>standard Price</th>
                            <th>Ratio</th>
                            <th><?php echo $DBweekname; ?></th>
                            <th>Class</th>
                            <th>Count</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <script>
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
            function loadTable(){
                $('#empTable').DataTable({
                        'processing': true,
                        'serverSide': true,
                        'serverMethod': 'POST',
                        'headers': {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-Requested-Session': '<?php echo session_id(); ?>'
                        },
                        'ajax': {
                            'url':'/tables/event/editpricetable.php',
                            'type': 'POST',
                            'data': function (data) {
                                data.start = data.start;
                                data.length = data.length;
                            }
                            
                        },
                    columns: [
                        { data: 'id' },
                        { data: 'y1' },
                        { data: 'pric' },
                        { data: 'cnt' },
                        { data: 'c_id' },
                        { data: 'eid' }
                        { data: 'cityname' }
                        { data: 'class' }
                        { data: 'citypricecell' }
                    ]
                    // Add any additional DataTable options as needed
                });
            }

            $(document).ready(function() {
                loadTable();
                
                
                $(document).on('click', '.newprice',function() {
                    let cityname = $(this).closest('tr').find('td:first').text().trim();
                    let cityid = $(this).closest('tr').find('input[name="city"]').val();
                    let years = $(this).closest('tr').find('input[name="y1"]').val();
                    let weeks = $(this).closest('tr').find('input[name="<?php echo $DBweekname; ?>"]').val();
                    let classs = $(this).closest('tr').find('input[name="class"]').val();
                    let theprice = $(this).closest('tr').find('input[name="price"]').val();
                    let ws = $(this).closest('tr').find('input[name="ws"]').val();
                    let standardprice = $(this).text();
                    var data = {
                        newprice: standardprice,
                        price: theprice,
                        city: cityid,
                        y1: years,
                        class: classs,
                        citypricecellnm: ws,
                    };
                    data[ws] = standardprice; // Set the property based on the value of ws
                    console.log(data);
                    data['<?php echo $DBweekname; ?>'] = weeks; // Set the property based on the value of ws
                    
                    var confirmation = confirm('Are you sure you want to change price for '+cityname+' Year: ('+years+') Week: ('+weeks+') Class: ('+classs+') from '+theprice+' to '+standardprice+'?');
                    if (confirmation) {
                        $.ajax({
                            type: "POST",
                            url: "updateprice", 
                            data: data,
                            success: function (response) {
                                console.log(response);
                                if (response == "success") {
                                    success_noti("success changed Price.")
                                    loadTable()
                                } else {
                                    error_noti(response)
                                }
                            },
                            failed: function (response) {
                            error_noti(response)
                            }
                        });
                    }
                });


                $(document).on('click', '.save',function() {
                    let cityname = $(this).closest('tr').find('td:first').text().trim();
                    let cityid = $(this).closest('tr').find('input[name="city"]').val();
                    let years = $(this).closest('tr').find('input[name="y1"]').val();
                    let weeks = $(this).closest('tr').find('input[name="<?php echo $DBweekname; ?>"]').val();
                    let classs = $(this).closest('tr').find('input[name="class"]').val();
                    let theprice = $(this).closest('tr').find('input[name="price"]').val();
                    let ws = $(this).closest('tr').find('input[name="ws"]').val();
                    let standardprice = $(this).closest('tr').find('input[name="newprice"]').val();
                    var data = {
                        newprice: standardprice,
                        price: theprice,
                        city: cityid,
                        y1: years,
                        class: classs,
                        citypricecellnm: ws,
                    };
                    data[ws] = standardprice; // Set the property based on the value of ws
                    console.log(data);
                    data['<?php echo $DBweekname; ?>'] = weeks; // Set the property based on the value of ws
                    
                    var confirmation = confirm('Are you sure you want to change price for '+cityname+' Year: ('+years+') Week: ('+weeks+') Class: ('+classs+') from '+theprice+' to '+standardprice+'?');
                    if (confirmation) {
                        $.ajax({
                            type: "POST",
                            url: "updateprice", 
                            data: data,
                            success: function (response) {
                                console.log(response);
                                if (response == "success") {
                                    success_noti("success changed Price.")
                                    loadTable()
                                } else {
                                    error_noti(response)
                                }
                            },
                            failed: function (response) {
                            error_noti(response)
                            }
                        });
                    }
                });

                var tables = $('#example').DataTable({ 
                        buttons: ['copy', 'excel', 'pdf', 'print'],
                        pageLength: 10,
                        lengthMenu: [[10, 25, 50, 100, 200, 500, -1], [10, 25, 50, 100, 200, 500, "All"]]
                        
                    });
                    tables.buttons().container().appendTo('#example_wrapper .col-md-6:eq(0)');
            });
            
            function deleteRow(price, city, className, week,citypricecellnm, citypricecell,x,year,cnt) {
                var confirmation = confirm('Are you sure you want to delete '+city+' class: ('+className+') week: ('+week+') standardprice: ('+citypricecell+') year: ('+year+') price: ('+price+') ? \n\n --> '+cnt+' rows will be deleted!');
                    if (confirmation) {
                        $.ajax({
                            type: "POST",
                            url: "deleteprice", // Change this to the URL of your PHP script
                            data: {
                                price: price,
                                city: city,
                                y1: year,
                                class: className,
                                <?php echo $DBweekname; ?>: week,
                                citypricecellnm: citypricecellnm,
                                citypricecell: citypricecell
                            },
                            success: function (response) {
                                console.log(response);
                                if (response == "1") {
                                    // Remove the deleted row from the HTML table
                                    success_noti("success deleted.");
                                    $('#row-' + x).remove();
                                } else {
                                    error_noti("Failed to delete the row.");
                                }
                            },
                            failed: function (response) {
                                error_noti("Failed to delete the row.");
                            }
                        });
                    }
                }
            </script>
        <?php        
    
?>
    </div>
</div>
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