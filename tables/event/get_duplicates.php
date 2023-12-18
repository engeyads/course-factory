<?php
session_start();
include '../../include/functions.php';
include '../../include/db.php';
if (!isset($_SESSION['db']) || empty($_SESSION['db'])) {
    die("Error: Database session not set or empty.");
}

error_reporting(E_ALL);
$lvl = $_SESSION['userlevel'];

switch($db_name){
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
        $newdbstype = false;
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

## Read value
$draw = isset($_POST['draw']) ? $_POST['draw'] : 1;
$row = isset($_POST['start']) ? ($_POST['start'] >=0 ? $_POST['start'] : 0) : 0;
$rowperpage = isset($_POST['length']) ? ($_POST['length'] >=0 ? $_POST['length'] : 10) : 10; // Rows display per page
$columnIndex = isset($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0; // Column index
$columnName = isset($_POST['columns'][$columnIndex]['data']) ? $_POST['columns'][$columnIndex]['data'] : 'id'; // Column name
$columnSortOrder = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc'; // asc or desc
$searchValue = isset($_POST['search']['value']) ? mysqli_real_escape_string($conn2, $_POST['search']['value']) : ''; // Search value

## Search query
$searchQuery = " ";
if ($searchValue != '') {
    $searchQuery = " AND (c_id like '%" . $searchValue . "%' OR 
        city like '%" . $searchValue . "%' OR 
        price like '%" . $searchValue . "%' OR 
        start_date like '%" . $searchValue . "%' OR 
        end_date like '%" . $searchValue . "%') ";
}

$sql = "SELECT id,c_id, name,$DBweekname  FROM course_main ORDER BY name";
$courseMain = mysqli_query($conn2, $sql);

$courseMainOptions = []; // Initialize the array

while ($rows = mysqli_fetch_assoc($courseMain)) {
    $id = $rows['c_id'];
    $courseName = $rows['name'];
    switch ($rows[$DBweekname]) {
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
        default:
            $daysdration = 4;
    }

    $courseMainOptions[] = array(
        'id' => $id,
        'name' => $courseName,
        "$DBweekname" => $daysdration
    );
}

$sql = "SELECT id, name, monday FROM cities ORDER BY name";
$cityies = mysqli_query($conn2, $sql);

$citiesOptions = []; // Initialize the array

while ($rows = mysqli_fetch_assoc($cityies)) {
    $id = $rows['id'];
    $cityName = $rows['name'];

    $citiesOptions[] = array(
        'id' => $id,
        'name' => $cityName
    );
}

// echo '<br><br><pre>';
// print_r($courseMainOptions);
// echo '</pre><br><br>';
// echo '<br><br><pre>';
// print_r($citiesOptions);
// echo '</pre><br><br>';


## Total number of records without filtering
$query1 = "SELECT COUNT(*) AS allcount FROM course WHERE (c_id, d1, m1, y1, d2, m2, y2, city) IN (SELECT c_id, d1, m1, y1, d2, m2, y2, city FROM course GROUP BY c_id, d1, m1, y1, d2, m2, y2, city HAVING COUNT(*) > 1)";
$sel = mysqli_query($conn2, $query1);
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of records with filtering
$query1 = "SELECT COUNT(*) AS allcount FROM course WHERE (c_id, d1, m1, y1, d2, m2, y2, city) IN (SELECT c_id, d1, m1, y1, d2, m2, y2, city FROM course GROUP BY c_id, d1, m1, y1, d2, m2, y2, city HAVING COUNT(*) > 1)" . $searchQuery;
$sel = mysqli_query($conn2, $query1);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$empQuery = "SELECT id AS ids_to_delete, id, c_id, city, price, d1, m1, y1, d2, m2, y2, DATE(TIMESTAMP(CONCAT(course.y1, '-', LPAD(course.m1, 2, '0'), '-', LPAD(course.d1, 2, '0')))) AS start_date, DATE(TIMESTAMP(CONCAT(course.y2, '-', LPAD(course.m2, 2, '0'), '-', LPAD(course.d2, 2, '0')))) AS end_date 
        FROM course 
        WHERE (c_id, d1, m1, y1, d2, m2, y2, city) IN (
            SELECT c_id, d1, m1, y1, d2, m2, y2, city
            FROM course
            GROUP BY c_id, d1, m1, y1, d2, m2, y2, city
            HAVING COUNT(*) > 1
        )
        $searchQuery
        ORDER BY $columnName $columnSortOrder 
        LIMIT $row, $rowperpage";

$empRecords = mysqli_query($conn2, $empQuery);
$data = array();

if (!$empRecords) {
    echo "Error: " . mysqli_error($conn2);
    // You might want to log the error or handle it more gracefully in a production environment
} else {
while ($records = mysqli_fetch_assoc($empRecords)) {
    // var_dump($records);
    $data[] = array(
        "ids_to_delete" => $records['ids_to_delete'],
        "c_id" => $records['c_id'],
        "city" => $records['city'],
        "price" => $records['price'],
        "start_date" => $records['start_date'],
        "end_date" => $records['end_date'],
        "course_name" => (isset($courseMainOptions[$records['c_id']]['name']) ? $courseMainOptions[$records['c_id']]['name'] : ' '),
        "city_name" => (isset($citiesOptions[$records['city']]['name']) ? $citiesOptions[$records['city']]['name'] : ' '),
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
?>