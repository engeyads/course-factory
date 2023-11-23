<?php
if ($_SESSION['userlevel'] > 2 ) {
    if(isset($_GET['c_id'])){
        header("Location: " . $url . "event/edit/" . $_GET['c_id']);
    }
    $path = dirname(__FILE__);
    include $path.'/conf.php';   
    $tablename = 'course_main' ;
    $row = GetRow($id,'id',$tablename , $theconnection);
    if ($id){ $c_id = $row['c_id']; }else { $c_id = ''; }
    $Columns = GetTableColumns($tablename,$theconnection);

    $course = GetForSelect('course_main' , $conn2, 'c_id', 'name');
    ?><form method="GET" action="">
    <?php
    FormsSelect('c_id', 'Select the Course to add Event For', $course, true, 'col-6');
    ?>
    <button type="submit" class="btn btn-primary">Next!</button>
</form><?php
}
?>
