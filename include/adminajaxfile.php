<?php
session_start();
include 'functions.php';
include 'db.php';

 if (!isset($_SESSION['db']) || empty($_SESSION['db'])) {
    die("Error: Database session not set or empty.");
}
error_reporting(E_ALL);
$lvl = $_SESSION['userlevel'];
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$tablename = $_POST['tablename']; // Table name (passed through AJAX request)
$fortablename = $_POST['fortablename']; // Table name (passed through AJAX request)
$columnNames = explode(",", $_POST['columns']); // Convert the comma-separated string to an array of column names
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = mysqli_real_escape_string($conn2, $_POST['search']['value']); // Search value
$maxlenginfield =20;
$tooltips = ['title'];
$popups = ['description', 'keyword', 'text', 'about','broshoure','overview'];
$jsonarrays = ['keyword', 'xx'];

## Search 
$searchQuery = " ";
if ($searchValue != '') {
    foreach ($columnNames as $columnName) {
        if( $columnName == 'edit' || $columnName == 'trash' || $columnName == 'delete' || $columnName == 'start_date' || $columnName == 'end_date' ) {
        }else{

                $searchQuery .= " OR LOWER($columnName) LIKE LOWER('%$searchValue%')";
            
        }
    }
    // Remove the initial ' OR ' from $searchQuery
    $searchQuery = ' AND (' . mb_substr($searchQuery, 4, 'UTF-8') . ')';

}

$sortColumn = $columnNames[$columnIndex]; // Get the column name for sorting

$limit = "LIMIT $row, $rowperpage";
$where = "tablename='$fortablename' ". (!empty(trim($searchQuery)) ? " $searchQuery " : '');
$order = "ORDER BY $sortColumn $columnSortOrder";
$custom_where = " $where $order $limit";

## Total number of records without filtering
$sel = mysqli_query($conn2, "SELECT COUNT(*) AS allcount FROM $tablename");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];
## Total number of records with filtering
$sel = mysqli_query($conn2, "SELECT COUNT(*) AS allcount FROM $tablename WHERE $where");
$records = mysqli_fetch_assoc($sel);
if ($records !== null) {
    $totalRecordwithFilter = $records['allcount'];
} else {
    // Handle the case where $records is null
    $totalRecordwithFilter = 0; // or any other default value you want to assign
}
## Fetch records
$columnNamesStr = implode(', ', $columnNames); // Converts array to comma-separated string
$empQuery = "SELECT * FROM $tablename WHERE $custom_where";
$empRecords = mysqli_query($conn2, $empQuery);
$data = array();
$modals = array();
while ($row = mysqli_fetch_assoc($empRecords)) {
    // Use a temporary array to hold the data for each row
    $rowData = array();
    $i=0;
    $tooltip = false;
    $popup = false;
    $jsonarray = false;
    if(in_array($row['columnname'], $tooltips)  ){
        $tooltip = true;
    }
    if(in_array($row['columnname'], $popups)  ){
        $popup = true;
    }
    if(in_array($row['columnname'], $jsonarrays)  ){
        $jsonarray = true;
    }
    foreach ($columnNames as $columnName) {
        
        if(!empty($row[$columnName])){
            $cellContent = $row[$columnName];
            if($cellContent && $tooltips && $tooltip && ($columnName == 'newData' || $columnName == 'oldData')){
                $tooltipContent = $cellContent;
                
                $cellContent = $cellContent !== null && mb_strlen($cellContent, 'UTF-8') >  $maxlenginfield ? mb_substr($cellContent, 0, $maxlenginfield, 'UTF-8') . '...' : $cellContent;
                
                $rowData[$columnName] = '<td  data-toggle="tooltip" data-placement="top"
                    title="' . htmlspecialchars($tooltipContent) . '">' . $cellContent . '</td>';
            }elseif ($cellContent && $jsonarrays && $jsonarray && ($columnName == 'newData' || $columnName == 'oldData')) {
            // Handle the case when the cell content is a non-empty JSON array
            $popupContent = json_decode($cellContent, true);
            if (is_array($popupContent)) {
                $popupValues = array_column($popupContent, 'value');
                $popupContent = implode("<br>", $popupValues);
            } else {
                $popupContent = $popupContent['value'] ?? '';
            }
            $btnContent = strip_tags($cellContent);
            if ($cellContent !== null && mb_strlen($cellContent, 'UTF-8') > $maxlenginfield) {
                $cellContent = mb_substr($cellContent, 0, $maxlenginfield, 'UTF-8') . '...';
                $btnContent = mb_strlen($popupContent, 'UTF-8') > $maxlenginfield ? mb_substr($popupContent, 0, $maxlenginfield, 'UTF-8') . '...' : $popupContent;
                $btnContent = strip_tags($btnContent);
                $btnContent = htmlspecialchars($btnContent, ENT_QUOTES);
            }
            $rowData[$columnName] = '<button type="button" class="btn btn-secondary m-0 p-2"
            onclick="showPopup(\''. htmlspecialchars($popupContent, ENT_QUOTES) . '\',\'' . $columnName . '\')">' . $btnContent . '</button>';
        } elseif ($cellContent && $popups && $popup && ($columnName == 'newData' || $columnName == 'oldData')) {
            $popupContent = $row[$columnName]; 
            $popupContent = strip_tags($popupContent);
            $popupContent = str_replace('"', '\"', $popupContent);
            $popupContent = str_replace("'", "\'", $popupContent);
            $popupContent = str_replace("\n", "<br>", $popupContent);

            if ($cellContent !== null && mb_strlen($cellContent, 'UTF-8') > $maxlenginfield) {
                $btnContent = mb_strlen((string)$cellContent, 'UTF-8') > $maxlenginfield ? mb_substr($cellContent, 0, $maxlenginfield, 'UTF-8') . '...' : $cellContent; 
            }
            
            $rowData[$columnName] = '<button type="button" class="btn btn-secondary m-0 p-2 popup-btn" data-content="' . htmlspecialchars($popupContent, ENT_QUOTES) .'" data-subject="' . $columnName .'">' . $btnContent .'</button>';
            }else {
                // If the cell content does not contain "webp", use the regular value
                $rowData[$columnName] = '<span >'.$row[$columnName].'</span>';
            }
        }else{
            $rowData[$columnName] = '';
        }
    }
    
    // Add the trash and delete links if the userlevel condition is met
    // Add the row data to the main data array
    $data[] = $rowData;
}

## Response


$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);

