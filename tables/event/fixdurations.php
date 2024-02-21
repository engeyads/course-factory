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
<?php if($auto){ ?>
    <div id="timer-container">
  <svg id="timer-svg" width="200" height="200">
    <circle id="timer-progress" cx="100" cy="100" r="90"></circle>
    <text id="timer-text" x="50%" y="50%" dominant-baseline="middle" text-anchor="middle">
      <tspan id="timer-seconds">5</tspan>
      <tspan> s</tspan>
    </text>
  </svg>
</div>
<?php } ?>
    <div class="card-body">
        <script src="<?php echo $url; ?>assets/makitweb/jquery-3.3.1.min.js"></script>
        <script src="<?php echo $url; ?>assets/makitweb/DataTables/datatables.min.js"></script>
        
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
                            <th>standard</th>
                            <th>save</th>
                        <?php } ?>
                        <!-- <th>count</th> -->
                    </tr>
                </thead>
                <tbody>
                
                </tbody>
                </table>
            </div>
            
        </div>
    </div>
</div>
<script>$(document).ready(function() {
    <?php if($auto){ ?>
    // auto next in 5 seconds
    var timerSeconds = 10; // Initial countdown time in seconds
    var countdownTimer;

    function updateTimer() {
        // Update the timer element with the current countdown value
        $('#timer-seconds').text(timerSeconds);
        // Update the stroke-dashoffset to create the visual countdown effect
        var dashOffset = (timerSeconds / 10) * 565; // 565 is the circumference of the circle
        $('#timer-progress').css('stroke-dashoffset', dashOffset);
    }

    function startTimer() {
        // Show the initial timer
        updateTimer();

        // Start the countdown
        countdownTimer = setInterval(function () {
            timerSeconds--;

            if (timerSeconds <= 0) {
                // When the countdown reaches 0, reload the DataTable and reset the timer
                table1.ajax.reload();
                timerSeconds = 10; // Reset the countdown time
                updateTimer();
            } else {
                // Update the timer during the countdown
                updateTimer();
            }
        }, 1000); // Update every 1000 milliseconds (1 second)
    }

    // Start the timer when the document is ready
    $(document).ready(function () {
        startTimer();

        // DataTable drawCallback
        table1.on('draw.dt', function () {
            // Check the total records in the DataTable
            var totalRecords = table1.page.info().recordsTotal;

            if (totalRecords === 0) {
                // Hide the timer and clear the interval if there are no rows
                clearInterval(countdownTimer);
                $('#timer-container').hide();
            }
        });
    });

<?php } ?>
    
        $(document).on('click', '.save',function() {
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
                    data: {
                        id: id,
                        newDuration: newDuration-1,
                    },
                    success: function (response) {
                        if (response == "success") {
                            table1.ajax.reload(null, false); 
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

        $(document).on('click', '.newDuration',function() {
            let currentDuration = $(this).closest('tr').find('input[name="currentDuration"]').val();
            let id = $(this).closest('tr').find('input[name="id"]').val();
            let newDuration = $(this).text();
            var data = {
                id: id,
                newDuration: newDuration-1,
            };
            
            var confirmation = confirm('Are you sure you want to change Duration for Event id ('+id+') from ('+currentDuration+') days to be ('+newDuration+') days?');
            if (confirmation) {
                $.ajax({
                    type: "POST",
                    url: "updateDuration", 
                    data: data,
                    success: function (response) {
                        if (response == "success") {
                            table1.ajax.reload(null, false); 
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
        serverSide: true, // Enable server-side processing
        processing: true, // Show processing indicator
        ajax: {
            url: '<?php echo $url; ?>tables/event/fixdurationtable.php<?php echo $auto ? '?auto=true' : '';?>', // URL to your server-side script
            type: 'POST',
            data: function (d) {
                // Include additional parameters if needed
                d.start = d.start || 0;
                d.length = d.length || 10;
            }
        },
        columns: [
            { data: 'id' },
            { data: 'start' },
            { data: 'end' },
            { data: 'weeks' },
            { data: 'duration' },
            <?php if(!$auto){ ?>   
            { data: 'standard'},
            { data: 'save'},
            <?php } ?> 
        ],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, 200, 500, -1], [10, 25, 50, 100, 200, 500, 'All']]
    });
});
</script>