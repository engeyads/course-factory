<?php 
session_start();


include '../../include/functions.php';
include '../../include/db.php';
if (!isset($_SESSION['db']) || empty($_SESSION['db'])) {
    die("Error: Database session not set or empty.");
}

switch($_SESSION['db_name']){
    case 'agile4training':
        $DBweekname = 'week';
        $newdbstype = true;
    break;
    case 'agile4training ar':
        $DBweekname = 'week';
        $newdbstype = true;
    break;
    case 'blackbird-training':
        $DBweekname = 'weeks';
        $newdbstype = false;
    break;
    case 'blackbird-training.co.uk':
        $DBweekname = 'week';
        $newdbstype = true;
    break;
    case 'mercury english':
        $DBweekname = 'week';
        $newdbstype = false;
    break;
    case 'mercury arabic':
        $DBweekname = 'week';
    break;
    case 'Euro Wings En':
        $DBweekname = 'weeks';
        $newdbstype = false;
    break;
    case 'Euro Wings Ar':
        $DBweekname = 'weeks';
        $newdbstype = false;
    break;
    default:
    $DBweekname = 'week';
    $newdbstype = false;
    break;
}
//error_reporting(E_ALL);
$lvl = $_SESSION['userlevel'];

    $start = isset($_GET['start']) ? $_GET['start'] : '';
    $priceprobcnt = 0;
    $custom_select = "SELECT *, course.price as pric,count(*) as cnt,course_main.c_id as cid,course.id as eid";
    $custom_from = "FROM course";
    $join = " LEFT JOIN course_main on course_main.c_id=course.c_id";
    $join2 = " LEFT JOIN course_c on course_c.id=course_main.course_c";
    $join3 = " LEFT JOIN cities on cities.id=course.city";
    $custom_where = " WHERE 1 ";
    $custom_group = " GROUP BY  pric,course.city,course_c.class,course_main.$DBweekname,y1 ORDER BY course.city,course_main.$DBweekname ,course_c.class,y1 ASC ";
    $custom_limit = " ";

    $query = "$custom_select $custom_from $join $join2 $join3 $custom_where $custom_group $custom_limit";
    
    $records = mysqli_query($conn2, $query);

    if (mysqli_num_rows($records) > 0) {
        
    echo '<div id="thetable"><table  class="table table-striped table-bordered dataTable no-footer display dataTable" role="grid">';
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

        $x = 0;
        while ($row = mysqli_fetch_assoc($records)) {?> 
            <tr id="row-<?php echo $x++; ?>">
            

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
                                    $priceprobcnt++;
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
                    <td><?php echo $row['cnt'] == 1 ? "<a href='".$url."event/edit/".$row['cid']."/".$row['eid']."'>".$row['cnt']."</a>" : $row['cnt']; ?></td>
                    <input type="hidden" name="ws" value="<?php  echo $citypricecell; ?>">
                    <input type="hidden" name="<?php echo $citypricecell; ?>" value="<?php echo (isset($row[$citypricecell]) ? $row[$citypricecell] : 'NULL' ); ?>">
                    <input type="hidden" name="city" value="<?php echo $row['city']; ?>">
                    <input type="hidden" name="<?php echo $DBweekname; ?>" value="<?php echo $row[$DBweekname]; ?>">
                    <input type="hidden" name="class" value="<?php echo $row['class']; ?>">

                    <td><span role="button" class="btn btn-primary px-5 float-right justify-content-end save"  data-id="<?php echo $row['pric']; ?>">Save</span></td>
                    <td><span onclick="deleteRow('<?php echo $row['pric'] == '' ? 'NULL': $row['pric']; ?>', '<?php echo $row['city']; ?>', '<?php echo $row['class']; ?>', '<?php echo $row[$DBweekname]; ?>','<?php echo $citypricecell == '' ? 'NULL': $citypricecell; ?>' ,'<?php echo  $citypricecell == '' ? 'NULL': $row[$citypricecell]; ?>','<?php echo ($x-1); ?>','<?php echo $row['y1']; ?>','<?php echo $row['cnt']; ?>')" class="btn btn-danger float-right justify-content-end" data-id="<?php echo $row['pric'] == '' ? 'NULL': $row['pric']; ?>"><i class="bi bi-x"></i></span></td>

        </td>
    </tr>
    <?php } 


    echo "<div id='thecnt'>";
    if($row_cnt = mysqli_num_rows($records)){

        ?>

        
        <div class="alert border-0 bg-light-warning alert-dismissible fade show py-2">
        <div class="d-flex align-items-center">
            <div class="fs-3 text-warning"><i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div class="ms-3">
            <div class="text-warning"> <?php  echo "Found ".$priceprobcnt." of ".$row_cnt; ?> !!</div>
            </div>
        </div>
        </div>
        <?php }else{ ?>
        <div class="alert border-0 bg-light-success alert-dismissible fade show py-2">
        <div class="d-flex align-items-center">
            <div class="fs-3 text-success"><i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="ms-3">
            <div class="text-success">Everything is ok! keep going.</div>
            </div>
        </div>
        </div>
        <?php } 
    echo "</div>";

    }else{
        echo "<h2>Nothing found!</h2>";
    }
?>