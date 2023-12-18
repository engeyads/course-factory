<?php
if ($_SESSION['userlevel'] > 2 ) {
    $path = dirname(__FILE__);
    include $path.'/conf.php';  
$id = $_GET['id'] ?? null; 
$c_id = isset($_GET['course']) ? $_GET['course'] : $_GET['c_id'];

$tablename = 'course';
$httpurl = $url;
$tabletitle = 'Events';  
$folderName =  basename(__DIR__);
$prompt = '';
$system = '';
$validby='';
$theconnection = $conn2;
$updateurl = 'update';
$remoteDirectory = 'images';
$courseId = $row_course_main = GetRow($c_id,'c_id','course_main' , $theconnection);
if(isset($_GET['id'])){
    $row = GetRow($id,'id',$tablename , $theconnection);

    if(!$row){
        http_response_code(404);
        header("Location: $url"."event/edit/$c_id");
    }
}

if($courseId){
    
}else{
    header("Location: $url"."event/view");
}
// echo '<pre>';
// print_r($row_course_main);
// echo '</pre>';

 
//$row_course_main['name'];
// CREATE TABLE `course` (
  //     `d1` int(2) DEFAULT NULL,
//     `m1` int(2) DEFAULT NULL,
//     `y1` int(4) DEFAULT NULL,
//     `d2` int(2) DEFAULT NULL,
//     `m2` int(2) DEFAULT NULL,
//     `y2` int(4) DEFAULT NULL,
//     `address` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
//     `certain` varchar(2) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
//     `price` varchar(6) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
//     `currency` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
//     `hotel_photo` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
//     `hotel_link` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
//     `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
//     `updated_at` timestamp NULL DEFAULT NULL,
//     `published_at` timestamp NULL DEFAULT NULL,
//     `deleted_at` timestamp NULL DEFAULT NULL
//   ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 


$categoryrow = GetRow($row_course_main['course_c'],'id','course_c' , $theconnection);


$Columns = GetTableColumns($tablename,$theconnection);


 
// Function to get the next occurrences of a specific day of the week



 
 
?>
<div class="row">
   
    <div class="col-6">
    <h1>Course Name: <?php echo $row_course_main['name']; ?></h1>
    <h5>Course ID: <?php echo $row_course_main['c_id']; ?></h5>
    <h5>Course Duration: <?php echo $row_course_main[$DBweekname]; ?> week</h5>
    <h5>Course Category: <?php echo $categoryrow['name']; ?></h5>
    <h5>Course Class: <?php echo $class = $categoryrow['class'];; ?></h5>
    <?php
            FormsStart();
            if ($c_id) { 
              echo '<input type="hidden" name="c_id" value="'.$c_id.'">';
            }
 

            $cities = GetForSelect('cities' , $conn2, 'id', 'name' , $order= ' order by name asc ');
            FormsSelect('city','City', $cities , true, 'col-6') ;

            FormsInput('price','Price', 'text', true , 'col-4',false,1,4);
            FormsInput('currency','Currency € $ £ ₺ | USD EURO AED ...', 'text', false , 'col-4 ',false,1,4);
            FormsCheck('certain', 'Certain', 'checkbox',false,'on', 'col-2' ) ;
            if($db_name == 'blackbird-training'){
                FormsCheck('visible', 'Visible (Blackbird Old)', 'checkbox',false,'on', 'col-4' ) ;
            }
            FormsImg('hotel_photo','Hotel Image', 'col-4 row float-start','','hotel_link',$tablename,$remoteDirectory,'hotels','1.35',400); 
            FormsInput('hotel_link','Hotel Link', 'text', false , 'col-4',false,1,4);
            
            ?>
            <div class="row"></div>
            

            <div class="col-6 mb-3">
                <label class="form-label">Start Date</label>
                <input class="result form-control" type="text" placeholder="Start Date" aria-label="Start Date" name="startday" id="startday" value="" required>
                <input id="d1" type="hidden" name="d1" value="">
                <input id="m1" type="hidden" name="m1" value="">
                <input id="y1" type="hidden" name="y1" value="">
            </div>

            <div class="col-6 mb-3">
                <label class="form-label">End Date</label>
                <input class="result form-control" type="text" placeholder="End Date" aria-label="End Date" name="enddate" id="enddate" value="" required>
                <input id="d2" type="hidden" name="d2" value="">
                <input id="m2" type="hidden" name="m2" value="">
                <input id="y2" type="hidden" name="y2" value="">
            </div>

            <?php
            FormsDateTime('enddate','End Date', false, 'col-6') ;
            
            
            // $row['published_at'] =  date now 
             if (!$id) {
                $row['published_at'] = date('Y-m-d');
             }
             FormsDateTimeNew('published_at','Publish Date And Time', false, 'col-6') ;
            FormsEnd(); 
                        
            
            ?>        
    </div>
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <ul class="list-group">
                    <?php
                        $weeknumber = $row_course_main[$DBweekname];
                        if ($class == 'A') {  $classforweek  = ''; }elseif ($class == 'B') { $classforweek  = '_b'; }elseif ($class == 'C') { $classforweek  = '_c';}
                        $query = "SELECT * FROM cities order by name asc";
                        $result = mysqli_query($conn2, $query);
                        $cities = array();
                    ?>
                    <?php 
                        while($rowcity = mysqli_fetch_assoc($result)){ 
                            $cityname =  $rowcity['name'];
                            $pricefield = 'w'. $weeknumber . '_p'. $classforweek; 
                            $cityprice = $rowcity[$pricefield];
                            if ($rowcity['monday'] == 1) {
                                $citystartday = 'Monday';
                            }else{
                                $citystartday = 'Sunday';
                            }
                    ?>
                    <li class="list-group-item   justify-content-between firstlist " role="button">
                        <span id="cityname<?php echo $rowcity['id']; ?>" ><?php echo $cityname; ?></span>
                                
                        <div  class="badge bg-secondary float-right "  id= "citystartdayxxx"><?php echo $citystartday ; ?></div>     
                        <div class="badge bg-primary float-end" >
                            <span id= "cityprice"><?php echo $cityprice; ?></span>
                            <span id= "citycurrency"><?php echo $defaultcurrency; ?></span>         
                        </div>
                    </li>
                    <?php 
                        }   
                    ?>  
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col-6 sundays">
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item     justify-content-between "> <h3><span> Sundays Days</span></h3> </li>
                        <?php
                            $next52 =   getNext('52','Sunday');
                            foreach ($next52 as $key => $value) {
                                $next52[$key] = $value;
                                echo '<li class="list-group-item sundaylist  daylists justify-content-between " role="button"> <span id="sundaystart' . $key . '" > '. $value .'</span> </li>';
                            }
                        ?>  
                    </ul>
                </div>
            </div>
            <div class="col-6 mondays">
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item  justify-content-between "> <h3><span> Mondays Days</span></h3> </li>
                        <?php
                            $next52 =   getNext('52','Monday');
                            foreach ($next52 as $key => $value) {
                                $next52[$key] = $value;
                                echo '<li class="list-group-item mondaylist  daylists justify-content-between " role="button"> <span id="mondaystart' . $key . '" > '. $value .'</span> </li>';
                            }
                        ?>  
                    </ul>
                </div>
            </div>
        </div>            
    </div>
    <?php
    //select courses with thir cities and course name query by c_id from the course table
    
    $tablename = 'course';
    $tabletitle = 'Events';
    $urlslug = $websiteurl .$eventslug;
    $maxlenginfield = 20;
    $theconnection = $conn2;
    $thedbname = $db2_name;
    $editslug = 'edit/'.$c_id;
    
    $trashslug = 'trash';
    $deleteslug = 'delete';
    $viewslug = 'view';
    $ignoredColumns = ['c_id','hotel_link','hotel_photo','address','d1', 'm1', 'y1', 'd2', 'm2', 'y2','deleted_at'];  
    $tooltips = ['start_date','end_date'  ];
    $popups = [];
    $jsonarrays = [];
    $imagePaths = [];
    $urlPaths = [];
    $editPath = 'city';
    $urlPath = 'id';
    // $no_link = true;
    // $no_edits = true;
    $fieldTitles = ['x'=>'Repeat Ratio','id' => 'Event ID','certain' => 'Upcoming', 'start_date' => 'Start Date', 'end_date' => 'End Date', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish','city' => 'City','price' => 'Price','currency' => 'Currency'];
    $dateColumns = ['created_at', 'updated_at','published_at']; // replace with your actual date columns
    $cities = GetForSelect('cities' , $conn2, 'id', 'name');
    $course = GetForSelect('course_main' , $conn2, 'c_id', 'name');
    $dataArrays = [
       'city' => $cities,
       //'c_id' => $course
        // Add more column mappings here
    ];
    $ajaxDataArrays = array(
        array(
            'column' => 'city',
            'table' => 'cities',
            'param1' => 'id',
            'param2' => 'name',
        ),
    );
    // $custom_from = "FROM course
    // LEFT JOIN cities ON course.city = cities.id
    // LEFT JOIN course_main ON course_main.c_id = course.c_id";
    // $custom_select = "SELECT course.id, course_main.c_id , course.created_at, cities.id as 'city' ,cities.x, course.certain, course.visible, TIMESTAMP(CONCAT(y1, '-', LPAD(m1, 2, '0'), '-', LPAD(d1, 2, '0'))) AS start_date, TIMESTAMP(CONCAT(y2, '-', LPAD(m2, 2, '0'), '-', LPAD(d2, 2, '0'))) AS end_date, course.updated_at, course.published_at,course.deleted_at, course.price, course.currency";
    // $custom_where = "WHERE course.c_id=$c_id";

    // $costumeQuery = "$custom_select $custom_from $custom_where";

    $custom_from = "course LEFT JOIN cities ON course.city = cities.id";
    $custom_select = "course.currency,course.price,course.id,  course.created_at, cities.id as 'city' ,cities.x, course.certain, course.visible ,DATE(CONCAT(y1, '-', LPAD(m1, 2, '0'), '-', LPAD(d1, 2, '0'))) AS start_date, DATE(CONCAT(y2, '-', LPAD(m2, 2, '0'), '-', LPAD(d2, 2, '0'))) AS end_date,course.updated_at,course.published_at,course.deleted_at";
    $custom_where = "c_id = $c_id";
    
    $costumeQuery = "SELECT * ,
        DATE(CONCAT(y1, '-', LPAD(m1, 2, '0'), '-', LPAD(d1, 2, '0'))) AS start_date,
        DATE(CONCAT(y2, '-', LPAD(m2, 2, '0'), '-', LPAD(d2, 2, '0'))) AS end_date
        FROM course  WHERE c_id = $c_id AND 1";
    $ignoredColumnsDB = ['hotel_photo','hotel_link','address' ];  // Replace these with your actual column names to ignore
    $additionalColumns = ['start_date', 'end_date','x'];  // Replace these with your actual additional column names
    // echo $costumeQuery;
    $ajaxview= true;
    include 'include/view.php';
    include 'include/logview.php';
    ?>
</div>
<?php 
if ($weeknumber==1){
    $daysdration = 4;
}else if ($weeknumber==2){
    $daysdration = 11;
}else if ($weeknumber==3){
    $daysdration = 18;
}else if ($weeknumber==4){
    $daysdration = 25;
}
 
?>
 <script>
     $('.sundays').hide();
     $('.mondays').hide();
$(document).ready(function() {
    $('.firstlist').click(function() {
        var index = $(this).index();
        
        // Select city
        $('#city').val($('#city option').eq(index + 1).val());
        $('#city').trigger('change.select2');
        
        // Show available dates for selected city
        $('.sundays').hide();
        $('.mondays').hide();
        $('.'+$(this).find('.badge').first().text().toLowerCase()+'s').show();
        
        // Fill price
        var price = $(this).find('#cityprice').text();
        $('#price').val(price);
        
        // Fill currency
        var currency = $(this).find('#citycurrency').text();
        $('#currency').val(currency);
    });

    $('.daylists').click(function() {
        var seldate = new Date($.trim($(this).find('span').text()));
        var selectDate = new Date(seldate);
        selectDate.setDate(seldate.getDate() + <?php echo $daysdration; ?>);
        $('#startday').pickadate('picker').set('select', seldate);
        $('#enddate').pickadate('picker').set('select', selectDate);
        $('#enddate').pickadate('picker').set('min', seldate);
        
        $('#d1').val(seldate.getDate());
        $('#m1').val(seldate.getMonth() + 1);
        $('#y1').val(seldate.getFullYear());
        $('#d2').val(selectDate.getDate());
        $('#m2').val(selectDate.getMonth() + 1);
        $('#y2').val(selectDate.getFullYear());
    });
    
});

$(function() {
    $("form").on("click", ":submit", function(e) {
        if ($("#startday").val() == "") {
            e.preventDefault();
            alert("Please fill out the start date");
            return false;  // Prevent form submission
        }
    });

    "use strict";
    var $startSunday = $('#startday').pickadate({
        selectMonths: true,
        selectYears: true, 
        format: 'yyyy-mm-dd',
        onSet: function(context) {
            var date = new Date(context.select);
            var selectDate = new Date(context.select);
            selectDate.setDate(date.getDate() + <?php echo $daysdration; ?>);

            $endSunday.pickadate('picker').set('select', selectDate);
            $endSunday.pickadate('picker').set('min', date);
            $('#d1').val(date.getDate());
            $('#m1').val(date.getMonth() + 1);
            $('#y1').val(date.getFullYear());
            $('#d2').val(selectDate.getDate());
            $('#m2').val(selectDate.getMonth() + 1);
            $('#y2').val(selectDate.getFullYear());
        }
    });

    var $endSunday = $('#enddate').pickadate({
        selectMonths: true,
        selectYears: true,
        format: 'yyyy-mm-dd',
        min: new Date(),
        onSet: function(context){
            var date = new Date(context.select);
            $('#d2').val(date.getDate());
            $('#m2').val(date.getMonth() + 1);
            $('#y2').val(date.getFullYear());
        }
    });

    // if editing event, fill its date
    <?php if ($id) {
        $d1 = $row['d1'];
        $m1 = $row['m1'];
        $y1 = $row['y1'];
        $d2 = $row['d2'];
        $m2 = $row['m2'];
        $y2 = $row['y2'];
        ?>
        var seldate = new Date("<?php echo $y1.'-'.$m1.'-'.$d1; ?>");
        var selectDate = new Date("<?php echo $y2.'-'.$m2.'-'.$d2; ?>");
        $('#startday').pickadate('picker').set('select', seldate);
        $('#enddate').pickadate('picker').set('select', selectDate);
        $('#enddate').pickadate('picker').set('min', seldate);
        $('#d1').val("<?php echo $d1; ?>");
        $('#m1').val("<?php echo $m1; ?>");
        $('#y1').val("<?php echo $y1; ?>");
        $('#d2').val("<?php echo $d2; ?>");
        $('#m2').val("<?php echo $m2; ?>");
        $('#y2').val("<?php echo $y2; ?>");
    <?php } ?>
});

</script>
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