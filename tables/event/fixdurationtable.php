<?php 
session_start();


include '../../include/functions.php';
include '../../include/db.php';
if (!isset($_SESSION['db']) || empty($_SESSION['db'])) {
    die("Error: Database session not set or empty.");
}

## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = mysqli_real_escape_string($conn2,$_POST['search']['value']); // Search value
$start = isset($_GET['start']) ? $_GET['start'] : 0;
$length = isset($_GET['length']) ? $_GET['length'] : 10;
$orderColumnIndex = $_POST['order'][0]['column'];
$orderDirection = $_POST['order'][0]['dir'];
$auto = isset($_GET['auto']) ? ($_GET['auto'] == 'true' ? true : false) : false;

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










## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " and (emp_name like '%".$searchValue."%' or 
        email like '%".$searchValue."%' or 
        city like'%".$searchValue."%' ) ";
}

## Total number of records without filtering

$sql = "SELECT COUNT(*) AS allcount
FROM (
    SELECT TIMESTAMPDIFF(DAY, CONCAT(y1, '-', m1, '-', d1), CONCAT(y2, '-', m2, '-', d2)) + 1 AS days_diff
    FROM `course`
    LEFT JOIN course_main ON course.c_id = course_main.c_id
    HAVING (days_diff != 5 AND days_diff != 12 AND days_diff != 18 AND days_diff != 26)
) AS subquery;";
$sel = mysqli_query($conn2,$sql);

$records = mysqli_fetch_assoc($sel);
if ($records !== null) {
    $totalRecords = $records['allcount'];
} else {
    // Handle the case where $records is null
    $totalRecords = 0; // or any other default value you want to assign
}
## Total number of records with filtering
$sel = mysqli_query($conn2,"SELECT COUNT(*) AS allcount
FROM (SELECT TIMESTAMPDIFF(DAY, CONCAT(y1, '-', m1, '-', d1), CONCAT(y2, '-', m2, '-', d2)) + 1 AS days_diff FROM `course` left join course_main on course.c_id = course_main.c_id WHERE 1 $searchQuery HAVING (days_diff != 5 AND days_diff != 12 AND days_diff != 18 AND days_diff != 26)
) AS subquery;");
$records = mysqli_fetch_assoc($sel);
if ($records !== null) {
    $totalRecordwithFilter = $records['allcount'];
} else {
    // Handle the case where $records is null
    $totalRecordwithFilter = 0; // or any other default value you want to assign
}

## Fetch records
$Query = "SELECT course.id, course.d1, course.m1, course.y1, course.d2, course.m2, course.y2, course_main.$DBweekname,
TIMESTAMPDIFF(DAY, CONCAT(y1, '-', m1, '-', d1), CONCAT(y2, '-', m2, '-', d2)) + 1 AS days_diff 
FROM `course` left join course_main on course.c_id = course_main.c_id WHERE 1 $searchQuery HAVING (days_diff != 5 AND days_diff != 12 AND days_diff != 18 AND days_diff != 26)
limit $row,$rowperpage";
$Records = mysqli_query($conn2, $Query);
$data = array();

while ($row = mysqli_fetch_assoc($Records)) {
    switch ($row[$DBweekname]){
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

    if($auto){

        $sqls = "UPDATE course 
        SET 
            d2 = DAY(DATE_ADD(CONCAT(y1, '-', m1, '-', d1), INTERVAL $daysdration DAY)),
            m2 = MONTH(DATE_ADD(CONCAT(y1, '-', m1, '-', d1), INTERVAL $daysdration DAY)),
            y2 = YEAR(DATE_ADD(CONCAT(y1, '-', m1, '-', d1), INTERVAL $daysdration DAY))
        WHERE id = '".$row['id']."'";
        mysqli_query($conn2, $sqls);
        $stat = '';
        if (mysqli_affected_rows($conn2) > 0) {
            $stat = "✅";
        } else {
            $stat = "❌";
        }

$data[] = array(
    'id' => $row['id'],
    'start' => $row['y1'].'-'.$row['m1'].'-'.$row['d1'],
    'end' => $row['y2'].'-'.$row['m2'].'-'.$row['d2'],
    "weeks" => $row[$DBweekname],
    'duration' => ($daysdration+1)." $stat",
    'standard' => "<b><span class='px-5 newDuration' data-value='".$daysdration."'>".($daysdration+1)." </span></b>",
    'save' => '<input type="hidden" name="id" value="'.$row['id'].'">
    <input type="hidden" name="currentDuration" value="'.$row['days_diff'].'"><button class="btn btn-primary float-right justify-content-end save" id="save" data-id="'.$daysdration.'">Save</button>'
);
    }else{
        $data[] = array(
            'id' => $row['id'],
            'start' => $row['y1'].'-'.$row['m1'].'-'.$row['d1'],
            'end' => $row['y2'].'-'.$row['m2'].'-'.$row['d2'],
            "weeks" => $row[$DBweekname],
            'duration' => "<input style='max-width:100px' class='form-control mb-3' type='number' name='newDuration' placeholder='".$row['days_diff']."' value='".$row['days_diff']."'>",
            'standard' => "<b><span role='button' class='btn btn-secondary px-5 newDuration' data-value='".$daysdration."'>".($daysdration+1)."</span></b>",
            'save' => '<input type="hidden" name="id" value="'.$row['id'].'">
            <input type="hidden" name="currentDuration" value="'.$row['days_diff'].'"><button class="btn btn-primary float-right justify-content-end save" id="save" data-id="'.$daysdration.'">Save</button>'
        );
    }
}

## Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);






