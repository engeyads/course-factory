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
switch($columnName){
    case 'Weeks':
        $columnName = $DBweekname;
        break;
    case 'Standard':
        $columnName = 'CASE WHEN w1_p <> course.price THEN w1_p ELSE CASE WHEN w2_p <> course.price THEN w2_p ELSE CASE WHEN w3_p <> course.price THEN w3_p ELSE CASE WHEN w4_p <> course.price THEN w4_p ELSE CASE WHEN w1_p_b <> course.price THEN w1_p_b ELSE CASE WHEN w1_p_b <> course.price THEN w1_p_b ELSE CASE WHEN w2_p_b <> course.price THEN w2_p_b ELSE CASE WHEN w3_p_b <> course.price THEN w3_p_b ELSE CASE WHEN w4_p_b <> course.price THEN w4_p_b ELSE CASE WHEN w1_p_c <> course.price THEN w1_p_c ELSE CASE WHEN w2_p_c <> course.price THEN w2_p_c ELSE CASE WHEN w3_p_c <> course.price THEN w3_p_c ELSE CASE WHEN w4_p_c <> course.price THEN w4_p_c ELSE NULL END END END END END END END END END END END END END';
        break;
    case 'Year':
        $columnName = "y1";
        break;
    case 'City':
        $columnName = "cities.name";
        break;
    case 'Ratio':
        $columnName = "x";
        break;
    case 'Count':
        $columnName = "cnt";
        break;
    case 'Price':
        $columnName = "pric";
        break;
    default:
        
        break;
}
$columnSortOrder = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc'; // asc or desc
$searchValue = isset($_POST['search']['value']) ? mysqli_real_escape_string($conn2, $_POST['search']['value']) : ''; // Search value
$urlslug = $websiteurl . $eventslug;
## Search query
$searchQuery = " ";
if ($searchValue != '') {
    $searchQuery = " AND (cities.name like '%" . $searchValue . "%' OR 
    course.y1 like '%" . $searchValue . "%' OR 
    course.price like '%" . $searchValue . "%' OR 
    w1_p like '%" . $searchValue . "%' OR
    w2_p like '%" . $searchValue . "%' OR
    w3_p like '%" . $searchValue . "%' OR
    w4_p like '%" . $searchValue . "%' OR
    w1_p_b like '%" . $searchValue . "%' OR
    w2_p_b like '%" . $searchValue . "%' OR
    w3_p_b like '%" . $searchValue . "%' OR
    w4_p_b like '%" . $searchValue . "%' OR
    w1_p_c like '%" . $searchValue . "%' OR
    w2_p_c like '%" . $searchValue . "%' OR
    w3_p_c like '%" . $searchValue . "%' OR
    w4_p_c like '%" . $searchValue . "%' ) ";
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
$query1 = "SELECT COUNT(*) AS allcount FROM (SELECT cities.name as city_name, course.price as pric,count(*) as cnt,course_main.c_id as cid,course.id as eid
FROM course
LEFT JOIN course_main on course_main.c_id=course.c_id
LEFT JOIN course_c on course_c.id=course_main.course_c
LEFT JOIN cities on cities.id=course.city
GROUP BY  pric,course.city,course_c.class,course_main.$DBweekname,y1 ) as t";
$sel = mysqli_query($conn2, $query1);
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of records with filtering
$query1 = "SELECT COUNT(*) AS allcount FROM (SELECT cities.name as city_name, course.price as pric,count(*) as cnt,course_main.c_id as cid,course.id as eid
FROM course
LEFT JOIN course_main on course_main.c_id=course.c_id
LEFT JOIN course_c on course_c.id=course_main.course_c
LEFT JOIN cities on cities.id=course.city
WHERE 1 $searchQuery 
GROUP BY  pric,course.city,course_c.class,course_main.$DBweekname,y1 ) as t ";
$sel = mysqli_query($conn2, $query1);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

if($_POST['length'] == -1) {
    $limit = "";
} else {
    $limit = "LIMIT $row, $rowperpage";
}
## Fetch records
$empQuery = "SELECT *,cities.name as city_name, course.price as pric,count(*) as cnt,course_main.c_id as cid,course.id as eid
FROM course
LEFT JOIN course_main on course_main.c_id=course.c_id
LEFT JOIN course_c on course_c.id=course_main.course_c
LEFT JOIN cities on cities.id=course.city
WHERE 1 $searchQuery 
GROUP BY  pric,course.city,course_c.class,course_main.$DBweekname,y1 
ORDER BY $columnName $columnSortOrder 
$limit";

$empRecords = mysqli_query($conn2, $empQuery);
$data = array();

if (!$empRecords) {
    echo "Error: " . mysqli_error($conn2);
    // You might want to log the error or handle it more gracefully in a production environment
} else {
while ($records = mysqli_fetch_assoc($empRecords)) {
    $citypricecell = ($records[$DBweekname] > 4 || $records[$DBweekname] <= 0 ? '' : 'w'.$records[$DBweekname].'_p'.(strtolower((isset($records['class']) ? $records['class'] : ' ')) == 'a'? '' : '_'.strtolower((isset($records['class']) ? $records['class'] : 'Undefined'))));
    
    switch ($records['class']) {
        case 'B': $classforweek  = '_b';
        break;
        case 'C': $classforweek  = '_c';
        break;
        // default case is A
        default: $classforweek  = '';
    }
    
    
    // var_dump($records);
    $data[] = array(
        "citypricecellnm" => $citypricecell == '' ? 'Custom Cannot Edit' : $records[$citypricecell],
        "citypricecell" => $citypricecell,
        "classforweek" => $classforweek,
        "year" => $records['y1'],
        "city" => $records['city'],
        "price" => $records['pric'],
        "class" => $records['class'],
        "weeks" => $records[$DBweekname],
        "City" => '<span class="'.($records['deleted_at'] ? "text-decoration-line-through text-danger" : "" ).'">'.$records['city_name'].'</span>',
        "Year" => '<span class="'.($records['deleted_at'] ? "text-decoration-line-through text-danger" : "" ).'">'.$records['y1'].'</span>',
        "Price" => '<input style="max-width:100px" onmousedown="setStep(this,100)" onmouseup="resetStep(this)" onwheel="setStep(this,100)" oninput="resetStep(this)" onkeydown="handleKeyDown(event, this)" class="form-control mb-3 theprice" id="customInput" type="number" name="newprice" placeholder="'.$records['pric'].'" value="'.$records['pric'].'">',
        "Standard" => $citypricecell == '' ? 'Custom Cannot Edit' : ( $records[$citypricecell] != $records['pric'] ? "<b><span role='button' class='btn btn-secondary px-5 newprice'>".( $records[$citypricecell] != '' ? trim($records[$citypricecell]) : '')." </span></b>" : ""),
        "Ratio" => '<span class="'.($records['deleted_at'] ? "text-decoration-line-through text-danger" : "" ).'">'.$records['x'.$classforweek].'</span>',
        "Weeks" => '<span class="'.($records['deleted_at'] ? "text-decoration-line-through text-danger" : "" ).'">'.$records[$DBweekname].'</span>',
        "Class" => '<span class="'.($records['deleted_at'] ? "text-decoration-line-through text-danger" : "" ).'">'.$records['class'].'</span>',
        "Count" => '<span class="'.($records['deleted_at'] ? "text-decoration-line-through text-danger" : "" ).'">'.$records['cnt'].'</span>',
        "Link" => ($records['cnt'] == "1" ? '<a target="_blank" href="'.$url.'event/edit/'.$records['cid'].'/'.$records['eid'].'"><i class="btn btn-warning bx bx-edit"></i></a>' : '<a target="_blank" href="'.$url.'event/view/0/'.$records['city'].'/0/0/'.$records['y1'].'/0/0/0/0/0/'.strtolower($records['class']).'/'.$records[$DBweekname].'/'.$records['pric'].'"><i class="btn btn-info bx bx-search-alt-2"></i></a>' ),
        "Delete" => '<span class="delete-link" data-id="'.$records['id'] .'"><i class="btn btn-danger bi bi-x-circle-fill"></i></span>',
        "Save" => '<span class="float-right justify-content-end save" data-id="'.$records['id'].'"><i class="btn btn-primary bx bx-save"></i></span>',
        "city_name" => $records['city_name'],
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