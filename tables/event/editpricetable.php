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
    $searchQuery = " AND (id like '%" . $searchValue . "%' OR 
    y1 like '%" . $searchValue . "%' OR 
    pric like '%" . $searchValue . "%' OR 
    cnt like '%" . $searchValue . "%' OR 
    c_id like '%" . $searchValue . "%' OR 
    eid like '%" . $searchValue . "%' OR 
    cityname like '%" . $searchValue . "%' OR 
    class like '%" . $searchValue . "%' OR 
    citypricecell like '%" . $searchValue . "%') ";

        
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
$query1 = "SELECT COUNT(*) AS allcount, course.price as pric,course_main.c_id as cid,course.id as eid FROM course LEFT JOIN course_main on course_main.c_id=course.c_id LEFT JOIN course_c on course_c.id=course_main.course_c LEFT JOIN cities on cities.id=course.city WHERE 1 GROUP BY pric,course.city,course_c.class,course_main.$DBweekname,y1";
$sel = mysqli_query($conn2, $query1);
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of records with filtering
$query1 = "SELECT COUNT(*) AS allcount, course.price as pric,course_main.c_id as cid,course.id as eid FROM course LEFT JOIN course_main on course_main.c_id=course.c_id LEFT JOIN course_c on course_c.id=course_main.course_c LEFT JOIN cities on cities.id=course.city WHERE 1 $searchQuery GROUP BY pric,course.city,course_c.class,course_main.$DBweekname,y1";
$sel = mysqli_query($conn2, $query1);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$empQuery = "SELECT course.id AS id,y1, course.price as pric,count(*) as cnt,course_main.c_id as cid,course.id as eid, cities.name as cityname,course_c.class as class,CASE
WHEN course_main.$DBweekname > 4 OR course_main.$DBweekname <= 0 THEN ''
ELSE CONCAT('w', course_main.$DBweekname, '_p', 
            CASE
                WHEN LOWER(COALESCE(class, '')) = 'a' THEN ''
                ELSE CONCAT('_', LOWER(COALESCE(class, 'Undefined')))
            END)
END AS citypricecell
        FROM course LEFT JOIN course_main on course_main.c_id=course.c_id LEFT JOIN course_c on course_c.id=course_main.course_c LEFT JOIN cities on cities.id=course.city
        WHERE 1 $searchQuery
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
        "id" => $records['id'],
        "y1" => $records['y1'],
        "pric" => $records['pric'],
        "cnt" => $records['cnt'],
        "c_id" => $records['cid'],
        "eid" => $records['eid'],
        "cityname" => $records['cityname'],
        "class" => $records['class'],
        "citypricecell" => $records['citypricecell'],
        // "course_name" => (isset($courseMainOptions[$records['c_id']]['name']) ? $courseMainOptions[$records['c_id']]['name'] : ' '),
        // "city_name" => (isset($citiesOptions[$records['city']]['name']) ? $citiesOptions[$records['city']]['name'] : ' '),
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
header('Content-Type: application/json');

ob_clean();
echo json_encode($response);
ob_flush();
?>