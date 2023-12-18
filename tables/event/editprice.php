<?php
if ($_SESSION['userlevel'] > 2 ) {
    // this code increments the dates based on the start date and end date

$start = isset($_GET['start']) ? $_GET['start'] : '';

    $path = dirname(__FILE__);
    include $path.'/conf.php';   
    $custom_select = "SELECT *, course.price as pric,count(*) as cnt";
    $custom_from = "FROM course";
    $join = " LEFT JOIN course_main on course_main.c_id=course.c_id";
    $join2 = " LEFT JOIN course_c on course_c.id=course_main.course_c";
    $join3 = " LEFT JOIN cities on cities.id=course.city";
    $custom_where = " WHERE 1 ";
    $custom_group = " GROUP BY  pric,course.city,course_c.class,course_main.$DBweekname,y1 ORDER BY course.city,course_main.$DBweekname ,course_c.class,y1 ASC ";
    $custom_limit = " ";

    $query = "$custom_select $custom_from $join $join2 $join3 $custom_where $custom_group $custom_limit";
    
    $records = mysqli_query($conn2, $query);
    // if mysqli_num_rows($records) > 0
?>

<script src="<?php echo $url; ?>assets/makitweb/jquery-3.3.1.min.js"></script>
<script src="<?php echo $url; ?>assets/makitweb/DataTables/datatables.min.js"></script>
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
        <h2>Event Renew Price Bulk</h2>
            <!-- <form action="">
                <input id="start" class="result form-control" style="max-width:150px" type="number" min="2018" placeholder="Start Year" aria-label="Start Year" name="start" value="<?php echo isset($_GET['start']) ? $_GET['start'] : ''; ?>" >
                <button type="submit" class="btn btn-secondary px-5">search</button>
            </form> -->
            <!-- <input id="end" class="result form-control" style="max-width:150px" type="text" placeholder="Start Date" aria-label="end Date" name="endday" value="<?php // echo isset($_GET['end']) ? $_GET['end'] : ''; ?>" > -->
                
    <?php
    if (mysqli_num_rows($records) > 0) {
        // gets all the course_main and put them in an array to be used in the select as options
        $sql = "SELECT id,c_id, name,$DBweekname  FROM course_main ORDER BY name";
        $courseMain = mysqli_query($conn2, $sql);
        while ($row = mysqli_fetch_assoc($courseMain)) {
            $id = $row['c_id'];
            $courseName = $row['name'];
            switch ($row[$DBweekname ]){
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
        
        <div class="table-responsive">
            <div id="example_wrapper" class="dataTables_wrapper dt-bootstrap5">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
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
                    <tbody>
                        <?php
                        $x = 0;
                            while ($row = mysqli_fetch_assoc($records)) {?> 
                                <tr id="row-<?php echo $x++; ?>">
                                
                                    <form action="<?php echo $url; ?>event/updateprice" method="POST">
                                        <?php $citypricecell = ($row[$DBweekname] > 4 || $row[$DBweekname] <= 0 ? '' : 'w'.$row[$DBweekname].'_p'.(strtolower((isset($row['class']) ? $row['class'] : ' ')) == 'a'? '' : '_'.strtolower((isset($row['class']) ? $row['class'] : 'Undefined')))); ?>
                                        <td><?php 
                                            foreach ($citiesOptions as $option) {
                                                $id = $option['id'];
                                                $cityName = $option['name'];
                                                if( $row['city'] == $id ) {
                                                    echo $cityName;
                                                }
                                            } ?>
                                        </td>
                                        <td>
                                            <input type="hidden" name="y1" value="<?php echo $row['y1']; ?>">
                                            <?php echo $row['y1']; ?>
                                        </td>
                                        <?php
                                        echo '<input type="hidden" name="price" value="'.$row['pric'].'">';
                                        echo '<td><input style="max-width:100px" onmousedown="setStep(this,100)" onmouseup="resetStep(this)" onwheel="setStep(this,100)" oninput="resetStep(this)" onkeydown="handleKeyDown(event, this)" class="form-control mb-3 theprice" id="customInput" type="number" name="newprice" placeholder="'.$row['pric'].'" value="'.$row['pric'].'"></td>';
                                        ?>
                                        <td><?php
                                            if(isset($row[$citypricecell])){

                                                if($row[$citypricecell] != null && ($row[$citypricecell] <= 4 || $row[$citypricecell] > 0)){
                                                    if ($row['pric'] !=  trim($row[$citypricecell])){
                                                        ?><b><span role="button" class="btn btn-secondary px-5 newprice"><?php echo $row[$citypricecell] != '' ? trim($row[$citypricecell]) : ''; ?></span></b><?php
                                                    }
                                                }   
                                            }
                                            ?>
                                        </td>
                                
                                        <td><?php 
                                            $class = $row['class'];

                                            switch ($class) {
                                                case 'B': $classforweek  = '_b';
                                                break;
                                                case 'C': $classforweek  = '_c';
                                                break;
                                                // default case is A
                                                default: $classforweek  = '';
                                            }
                                            
                                            echo $row['x'.$classforweek]; ?>
                                        </td>
                                        <td><?php 
                                            echo $row[$DBweekname]; ?>
                                        </td>
                                        <td><?php echo $row['class']; ?></td>
                                        <td><?php echo $row['cnt']; ?></td>
                                        <input type="hidden" name="ws" value="<?php  echo $citypricecell; ?>">
                                        <input type="hidden" name="<?php echo $citypricecell; ?>" value="<?php echo (isset($row[$citypricecell]) ? $row[$citypricecell] : 'NULL' ); ?>">
                                        <input type="hidden" name="city" value="<?php echo $row['city']; ?>">
                                        <input type="hidden" name="<?php echo $DBweekname; ?>" value="<?php echo $row[$DBweekname]; ?>">
                                        <input type="hidden" name="class" value="<?php echo $row['class']; ?>">

                                        <td><button class="btn btn-primary float-right justify-content-end" data-id="<?php echo $row['pric']; ?>">Save</button></td>
                                        <td><span onclick="deleteRow('<?php echo $row['pric'] == '' ? 'NULL': $row['pric']; ?>', '<?php echo $row['city']; ?>', '<?php echo $row['class']; ?>', '<?php echo $row[$DBweekname]; ?>','<?php echo $citypricecell == '' ? 'NULL': $citypricecell; ?>' ,'<?php echo  $citypricecell == '' ? 'NULL': $row[$citypricecell]; ?>','<?php echo ($x-1); ?>','<?php echo $row['y1']; ?>','<?php echo $row['cnt']; ?>')" class="btn btn-danger float-right justify-content-end" data-id="<?php echo $row['pric'] == '' ? 'NULL': $row['pric']; ?>"><i class="bi bi-x"></i></span></td>
                                        </form>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
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

            $(document).ready(function() {
                $('.newprice').click(function() {
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
                                    $(this).closest('tr').find('input[name="price"]').val($(this).text().trim());
                                    $(this).closest('tr').find('.form-control.theprice').val($(this).text().trim());
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
                                if (response == "success") {
                                    // Remove the deleted row from the HTML table
                                    $('#row-' + x).remove();
                                } else {
                                    alert("Failed to delete the row.");
                                }
                            },
                            failed: function (response) {
                                alert("Failed to delete the row.");
                            }
                        });
                    }
                }
            </script>
        <?php        
    }else{
        echo "<h2>Nothing found!</h2>";
    }
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