<?php
session_start();
include '../../include/functions.php';
include '../../include/db.php';
if (!isset($_SESSION['db']) || empty($_SESSION['db'])) {
    die("Error: Database session not set or empty.");
}

error_reporting(E_ALL);
$lvl = $_SESSION['userlevel'];



## Read value
$draw = isset($_POST['draw']) ? $_POST['draw'] : 1;
$row = isset($_POST['start']) ? ($_POST['start'] >=0 ? $_POST['start'] : 0) : 0;
$rowperpage = isset($_POST['length']) ? ($_POST['length'] >=0 ? $_POST['length'] : 10) : 10; // Rows display per page
$columnIndex = isset($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0; // Column index
$columnName = isset($_POST['columns'][$columnIndex]['data']) ? $_POST['columns'][$columnIndex]['data'] : 'id'; // Column name
switch($columnName){
    case 'Id':
        $columnName = 'id';
        break;
    case 'Link':
        $columnName = 'link';
        break;
    case 'UpdatedAt':
        $columnName = 'updated_at';
        break;
    case 'SpeedTest':
        $columnName = 'speed_test';
        break;
    case 'SpeedDate':
        $columnName = 'speed_date';
        break;
    case 'GoogleIndex':
        $columnName = 'google_index';
        break;
    case 'Indexing State':
        $columnName = 'indexingState';
        break;
    case 'Verdict':
        $columnName = 'verdict';
        break;
    case 'Coverage State':
        $columnName = 'coverageState';
        break;
    case 'Robots Txt State':
        $columnName = 'robotsTxtState';
        break;
    case 'Last Crawl Time':
        $columnName = 'lastCrawlTime';
        break;
    case 'Page Fetch State':
        $columnName = 'pageFetchState';
        break;
    case 'Crawl State':
        $columnName = 'pageFetchState';
        break;
    case 'Google Canonical':
        $columnName = 'googleCanonical';
        break;
    case 'GoogleDate':
        $columnName = 'google_date';
        break;
    case 'CreatedAt':
        $columnName = 'created_at';
        break;
    default:
        
        break;
}
$columnSortOrder = isset($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 'asc'; // asc or desc
$searchValue = isset($_POST['search']['value']) ? mysqli_real_escape_string($conn2, $_POST['search']['value']) : ''; // Search value

## Search query
$searchQuery = " ";
if ($searchValue != '') {
     	 	 	 	 	 	 	 	 	 	
    $searchQuery = " AND (id like '%" . $searchValue . "%' OR 
    link like '%" . $searchValue . "%' OR 
    updated_at like '%" . $searchValue . "%' OR 
    speed_test like '%" . $searchValue . "%' OR
    speed_date like '%" . $searchValue . "%' OR
    google_index like '%" . $searchValue . "%' OR
    indexingState like '%" . $searchValue . "%' OR
    verdict like '%" . $searchValue . "%' OR
    coverageState like '%" . $searchValue . "%' OR
    robotsTxtState like '%" . $searchValue . "%' OR
    lastCrawlTime like '%" . $searchValue . "%' OR
    pageFetchState like '%" . $searchValue . "%' OR
    googleCanonical like '%" . $searchValue . "%' OR
    google_date like '%" . $searchValue . "%' OR
    created_at like '%" . $searchValue . "%' ) ";
}

## Total number of records without filtering
$query1 = "SELECT COUNT(*) AS allcount FROM seo";
$sel = mysqli_query($conn2, $query1);
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of records with filtering
$query1 = "SELECT COUNT(*) AS allcount FROM seo WHERE 1 $searchQuery ";
$sel = mysqli_query($conn2, $query1);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

if($_POST['length'] == -1) {
    $limit = "";
} else {
    $limit = "LIMIT $row, $rowperpage";
}
## Fetch records
$empQuery = "SELECT id, link, updated_at, speed_test, speed_date, google_index, indexingState, verdict, coverageState, robotsTxtState, lastCrawlTime, pageFetchState, googleCanonical, google_date, created_at
FROM seo WHERE 1 $searchQuery 
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
        "Id" => $records['id'],
        "Link" => '<a href="'.$records['link'].'" target="_blank">'.$records['link'].'</a>',
        "UpdatedAt" => $records['updated_at'],
        "SpeedTest" => '<div class="td-container"><span class="badge '.($records['speed_test'] == null ? 'bg-secondary' : ($records['speed_test'] > 0.98 ? 'bg-success' : 'bg-danger' )).'">'.($records['speed_test'] == null ? 'Not Checked' : ($records['speed_test']*100).' %').'</span><span class="btn btn-primary" data-action="check_speed"><i class="lni lni-rocket"></i> '.($records['speed_test'] == null ? 'Check' : 'reCheck').'</span></div>',
        "SpeedDate" => $records['speed_date'],
        "GoogleIndex" => '<div class="td-container"><span class="badge '.($records['google_index'] != 1 ? 'bg-secondary' : 'bg-success').'">'.($records['google_index'] != 1 ? ($records['google_date'] == null ? 'Not Checked' : 'Not Indexed') : 'Indexed').'</span><span class="btn btn-primary" data-action="check_google_index"><i class="lni lni-reload"></i> Check</span></div>',
        "IndexingState" => $records['indexingState'],
        "Verdict" => $records['verdict'],
        "CoverageState" => $records['coverageState'],
        "RobotsTxtState" => $records['robotsTxtState'],
        "LastCrawlTime" => $records['lastCrawlTime'],
        "PageFetchState" => $records['pageFetchState'],
        "GoogleCanonical" => $records['googleCanonical'] != '' ? '<a href="'.$records['googleCanonical'].'" target="_blank">'.$records['googleCanonical'].'</a>' : '',
        "GoogleDate" => $records['google_date'],
        "CreatedAt" => $records['created_at'],
        "Delete" => '<span target="_blank" href="#" class="delete-link" data-id="'.$records['id'] .'"><i class="btn btn-danger bi bi-x-circle-fill"></i></span>',
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