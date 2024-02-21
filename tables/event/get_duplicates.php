<?php
session_start();
include '../../include/functions.php';
include '../../include/db.php';
if (!isset($_SESSION['db']) || empty($_SESSION['db'])) {
    die("Error: Database session not set or empty.");
}

error_reporting(E_ALL);
$lvl = $_SESSION['userlevel'];

switch($_SESSION['db_name']){
    case 'agile4training':
        $websiteurl = "https://agile4training.com/";
        $eventslug = 'event/';
        $DBweekname = 'week';
        $newdbstype = true;
    break;
    case 'agile4training ar':
        $websiteurl = "https://agile4training.com/ar/";
        $eventslug = 'event/';
        $DBweekname = 'week';
        $newdbstype = true;
    break;
    case 'blackbird-training':
        $websiteurl = "https://blackbird-training.com/";
        $eventslug = 'event/';
        $DBweekname = 'weeks';
        $newdbstype = false;
    break;
    case 'blackbird-training.co.uk':
        $websiteurl = "https://blackbird-training.co.uk/";
        $eventslug = 'event/';
        $DBweekname = 'week';
        $newdbstype = true;
    break;
    case 'mercury english':
        $websiteurl = "https://mercury-training.com/";
        $eventslug = 'p/';
        $DBweekname = 'week';
        $newdbstype = false;
    break;        

    case 'mercury arabic':
        $websiteurl = "https://mercury-training.com/ar/";
        $eventslug = 'p/';
        $DBweekname = 'week';
        $newdbstype = false;
        break;
    case 'Euro Wings En':
        $websiteurl = "https://eurowingstraining.com/";
        $eventslug = 'p/';
        $DBweekname = 'weeks';
        $newdbstype = false;
    break;
    case 'Euro Wings Ar':
        $websiteurl = "https://eurowingstraining.com/Arabic/";
        $eventslug = 'p/';
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
$urlslug = $websiteurl . $eventslug;
## Search query
$searchQuery = " ";
if ($searchValue != '') {
    $searchQuery = " AND (course.id like '%" . $searchValue . "%' OR 
    course_main.name like '%" . $searchValue . "%' OR 
    cities.name like '%" . $searchValue . "%' OR 
    course.price like '%" . $searchValue . "%' OR 
    DATE(TIMESTAMP(CONCAT(course.y1, '-', LPAD(course.m1, 2, '0'), '-', LPAD(course.d1, 2, '0'))))  like '%" . $searchValue . "%' OR 
    DATE(TIMESTAMP(CONCAT(course.y2, '-', LPAD(course.m2, 2, '0'), '-', LPAD(course.d2, 2, '0'))))  like '%" . $searchValue . "%') ";
}

// $sql = "SELECT id,c_id, name,$DBweekname  FROM course_main ORDER BY name";
// $courseMain = mysqli_query($conn2, $sql);

// $courseMainOptions = []; // Initialize the array

// while ($rows = mysqli_fetch_assoc($courseMain)) {
//     $id = $rows['c_id'];
//     $courseName = $rows['name'];
//     switch ($rows[$DBweekname]) {
//         case 2:
//             $daysdration = 11;
//             break;
//         case 3:
//             $daysdration = 17;
//             break;
//         case 4:
//             $daysdration = 25;
//             break;
//         // default case is 1
//         default:
//             $daysdration = 4;
//     }

//     $courseMainOptions[] = array(
//         'id' => $id,
//         'name' => $courseName,
//         "$DBweekname" => $daysdration
//     );
// }

// $sql = "SELECT id, name, monday FROM cities ORDER BY name";
// $cityies = mysqli_query($conn2, $sql);

// $citiesOptions = []; // Initialize the array

// while ($rows = mysqli_fetch_assoc($cityies)) {
//     $id = $rows['id'];
//     $cityName = $rows['name'];

//     $citiesOptions[] = array(
//         'id' => $id,
//         'name' => $cityName
//     );
// }

// echo '<br><br><pre>';
// print_r($courseMainOptions);
// echo '</pre><br><br>';
// echo '<br><br><pre>';
// print_r($citiesOptions);
// echo '</pre><br><br>';


## Total number of records without filtering
$query1 = "SELECT COUNT(*) AS allcount FROM course LEFT JOIN course_main on course_main.c_id=course.c_id LEFT JOIN cities on cities.id=course.city WHERE (course.c_id, d1, m1, y1, d2, m2, y2, course.city) IN (SELECT course.c_id, d1, m1, y1, d2, m2, y2, course.city FROM course GROUP BY course.c_id, d1, m1, y1, d2, m2, y2, course.city HAVING COUNT(*) > 1)";
$sel = mysqli_query($conn2, $query1);
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of records with filtering
$query1 = "SELECT COUNT(*) AS allcount FROM course LEFT JOIN course_main on course_main.c_id=course.c_id LEFT JOIN cities on cities.id=course.city WHERE (course.c_id, d1, m1, y1, d2, m2, y2, course.city) IN (SELECT course.c_id, d1, m1, y1, d2, m2, y2, course.city FROM course GROUP BY course.c_id, d1, m1, y1, d2, m2, y2, course.city HAVING COUNT(*) > 1)" . $searchQuery;
$sel = mysqli_query($conn2, $query1);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

if($_POST['length'] == -1) {
    $limit = "";
} else {
    $limit = "LIMIT $row, $rowperpage";
}
## Fetch records
$empQuery = "SELECT course_main.id as cid, course_main.$DBweekname as duration, course.id AS ids_to_delete, course.id, course.c_id, course.city,course_main.name as coursename,cities.name as cityname, course.price, d1, m1, y1, d2, m2, y2, DATE(TIMESTAMP(CONCAT(course.y1, '-', LPAD(course.m1, 2, '0'), '-', LPAD(course.d1, 2, '0')))) AS start_date, DATE(TIMESTAMP(CONCAT(course.y2, '-', LPAD(course.m2, 2, '0'), '-', LPAD(course.d2, 2, '0')))) AS end_date, course.deleted_at
        FROM course 
        LEFT JOIN course_main on course_main.c_id=course.c_id
        LEFT JOIN cities on cities.id=course.city
        WHERE (course.c_id, d1, m1, y1, d2, m2, y2, course.city) IN (
            SELECT c_id, d1, m1, y1, d2, m2, y2, course.city
            FROM course
            GROUP BY course.c_id, d1, m1, y1, d2, m2, y2, course.city
            HAVING COUNT(*) > 1
        )
        $searchQuery
        ORDER BY $columnName $columnSortOrder 
        $limit";

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
        "cityname" => '<a class="'.($records['deleted_at'] ? "text-decoration-line-through text-danger" : "" ).'" href="/cities/edit/'.$records['city'].'">'.$records['cityname'].'</a>',
        "coursename" => '<a class="'.($records['deleted_at'] ? "text-decoration-line-through text-danger" : "" ).'" href="/courses/edit/'.$records['cid'].'">'.$records['coursename'].'</a>',
        "duration" => $records['duration'],
        "price" => '<input style="max-width:100px" class="form-control mb-3" type="number" step="100" name="price" value="'.$records['price'].'">',
        "start_date" => '<input id="start-'. $records['ids_to_delete'].'" class="result form-control start" style="max-width:150px" type="text" placeholder="Start Date" aria-label="Start Date" name="startday" value="'. $records['start_date'].'" required>',
        "end_date" => '<input id="end-'.$records['ids_to_delete'].'" class="result form-control end" style="max-width:150px" type="text" placeholder="End Date" aria-label="End Date" name="endday" value="'.$records['end_date'].'" readonly>',
        "course_name" => $records['coursename'],
        "city_name" => $records['cityname'],
        "Link" => '<a target="_blank" href="' . $urlslug . $records['ids_to_delete'] .'"><i class="btn btn-info bx bx-world"></i></a>',
        "Edit" => '<a target="_blank" href="'.$url . 'event/edit/'.$records['c_id'] .'/' . $records['id'] .'"><i class="btn btn-warning bx bx-edit"></i></a>',
        "Trash" => '<span target="_blank" href="#" class="trash-link" data-id="'.$records['id'] .'" class="confirmAction">' . ($records['deleted_at'] ? '<i class="btn btn-danger bi bi-arrow-counterclockwise "></i>' : '<i class="btn btn-danger bi bi-trash "></i>') .'</span>',
        "Delete" => '<span target="_blank" href="#" class="delete-link" data-id="'.$records['id'] .'"><i class="btn btn-danger bi bi-x-circle-fill"></i></span>',
        "Save" => '<span class="float-right justify-content-end save" data-id="'.$records['ids_to_delete'].'"><i class="btn btn-primary bx bx-save"></i></span>',
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
if(isset($isLocal)){
    header('Content-Type: application/json');
    ob_clean();
}
echo json_encode($response);
if(isset($isLocal)){
    ob_flush();
}
?>