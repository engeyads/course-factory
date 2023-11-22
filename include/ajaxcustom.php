<?php
session_start();
include 'functions.php';
include 'db.php';

if (!isset($_SESSION['db']) || empty($_SESSION['db'])) {
    die("Error: Database session not set or empty.");
}

error_reporting(E_ALL);

$lvl = $_SESSION['userlevel'];

$db_name = $_SESSION['db']; // Assuming you have $db_name set somewhere

switch ($db_name) {
    case 'agile4training':
    case 'agile4training ar':
        $DBweekname = 'week';
        $newdbstype = true;
        break;
    case 'blackbird-training':
    case 'blackbird-training.co.uk':
        $DBweekname = 'weeks';
        $newdbstype = false;
        break;
    case 'mercury english':
        $DBweekname = 'week';
        $newdbstype = false;
        break;
    case 'mercury arabic':
        $DBweekname = 'week';
        break;
    case 'Euro Wings En':
    case 'Euro Wings Ar':
        $DBweekname = 'weeks';
        $newdbstype = false;
        break;
    default:
        $DBweekname = 'week';
        $newdbstype = false;
        break;
}

## Read value
$draw = isset($_POST['draw']) ? $_POST['draw'] : 1;
$row = isset($_POST['start']) ? $_POST['start'] : 0;
$rowperpage = isset($_POST['length']) ? $_POST['length'] : 25;
$columnIndex = isset($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
$columnName = isset($_POST['columns'][$columnIndex]['data']) ? $_POST['columns'][$columnIndex]['data'] : 'id';
$columnSortOrder = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc';
$searchValue = isset($_POST['search']['value']) ? mysqli_real_escape_string($conn2, $_POST['search']['value']) : '';

## Search
$searchQuery = " ";
$columns = array(); // Dynamically get all column names
$sel = mysqli_query($conn2, "DESCRIBE course");
while ($column = mysqli_fetch_assoc($sel)) {
    $columns[] = $column['Field'];
}

if ($searchValue != '') {
    $searchConditions = array();
    foreach ($columns as $column) {
        $searchConditions[] = "$column LIKE '%$searchValue%'";
    }
    $searchQuery = " AND (" . implode(" OR ", $searchConditions) . ") ";
}

## Total number of records without filtering
$sel = mysqli_query($conn2, "SELECT count(*) as allcount FROM course");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of records with filtering
$sel = mysqli_query($conn2, "SELECT count(*) as allcount FROM course left join course_main on course.c_id = course_main.c_id HAVING (days_diff != 5 AND days_diff != 12 AND days_diff != 18 AND days_diff != 26) WHERE 1 " . $searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$query = "SELECT course.id, course.d1, course.m1, course.y1, course.d2, course.m2, course.y2, course_main.$DBweekname, TIMESTAMPDIFF(DAY, CONCAT(y1, '-', m1, '-', d1), CONCAT(y2, '-', m2, '-', d2)) + 1 AS days_diff  FROM `course` left join course_main on course.c_id = course_main.c_id HAVING (days_diff != 5 AND days_diff != 12 AND days_diff != 18 AND days_diff != 26) limit $row, $rowperpage";

$empRecords = mysqli_query($conn2, $query);
$output = array(
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $totalRecords,
    'recordsFiltered' => $totalRecordwithFilter,
    'data' => array(),
);

while ($row = mysqli_fetch_assoc($empRecords)) {
    // Modify date columns
    $days_duration = 0;

    switch ($row[$DBweekname]) {
        case 2:
            $days_duration = 11;
            break;
        case 3:
            $days_duration = 18;
            break;
        case 4:
            $days_duration = 25;
            break;
        // default case is 1
        default:
            $days_duration = 4;
    }
    $start_date = $row['y1'] . '-' . $row['m1'] . '-' . $row['d1'];
    $end_date = $row['y2'] . '-' . $row['m2'] . '-' . $row['d2'];

    $output['data'][] = array(
        'id' => $row['id'],
        'start_date' => $start_date,
        'end_date' => $end_date,
        'week' => $row[$DBweekname],
        'days_diff' => $row['days_diff'],
        'newDuration' => '<input style="max-width:100px" class="form-control mb-3" type="number" name="newDuration" placeholder="' . $row['days_diff'] . '" value="' . $row['days_diff'] . '">',
        'btnSecondary' => '<b><span role="button" class="btn btn-secondary px-5 newDuration">' . $days_duration . '</span></b>',
        'btnPrimary' => '<button class="btn btn-primary float-right justify-content-end" data-id="' . $days_duration . '">Save</button>',
    );
}

echo json_encode($output);
?>
