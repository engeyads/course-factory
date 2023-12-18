<?php
if ($_SESSION['userlevel'] > 2 ) {
    // this code increments the dates based on the start date and end date

    $path = dirname(__FILE__);
    include $path.'/conf.php';   

  
    echo "<h2 class='cnt'></h2>";
        
    ?>
    <script src="<?php echo $url;?>assets/plugins/datetimepicker/js/picker.js"></script>
        <script src="<?php echo $url;?>assets/plugins/datetimepicker/js/picker.date.js"></script>
        <div class="card">
            <div class="card-body">
                <div id="table-responsive" class="table-responsive">

                </div>
            </div>
        </div>
        <script>
            $(document).ready(function() {

                $('#table-responsive').load('<?php echo $url; ?>tables/event/fixduplicatetable.php', function() {
                    var thetable = $(this).find('#thetable');
                    var specificContent = $(this).find('#thecnt');
                    $('.cnt').html(specificContent.html());
                    $(this).html(thetable.html());
                    
                });


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
                            // Handle the AJAX response, e.g., display a success message
                            // console.log(response);
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