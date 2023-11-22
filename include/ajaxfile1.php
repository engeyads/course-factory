<?php
session_start();
include 'functions.php';
include 'db.php';

 if (!isset($_SESSION['db']) || empty($_SESSION['db'])) {
    die("Error: Database session not set or empty.");
}
$lvl = $_SESSION['userlevel'];
## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$tablename = $_POST['tablename']; // Table name (passed through AJAX request)
$columnNames = explode(",", $_POST['columns']); // Convert the comma-separated string to an array of column names
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = mysqli_real_escape_string($conn2, $_POST['search']['value']); // Search value
if(isset($_POST['dataArrays'])) { $dataArrays =  $_POST['dataArrays']; }else { $dataArrays = ''; }
if(isset($_POST['ignoredColumns'])) { $ignoredColumns =  $_POST['ignoredColumns']; }else { $ignoredColumns = ''; }
if(isset($_POST['custom_from'])) { $custom_from =  $_POST['custom_from']; }else { $custom_from = $tablename; }
if(isset($_POST['imagePaths'])){ $imagePaths = $_POST['imagePaths']; }
if(isset($_POST['popups'])){ $popups = explode(",", $_POST['popups']); }
if(isset($_POST['jsonarrays'])){ $jsonarrays = explode(",", $_POST['jsonarrays']); }
if(isset($_POST['maxlenginfield'])) { $maxlenginfield = $_POST['maxlenginfield']; } else { $maxlenginfield = ''; }
if(isset($_POST['urll'])) { $url = $_POST['urll']; }else { $url = ''; }
if(isset($_POST['folderName'])) { $folderName = $_POST['folderName']; }else { $folderName = ''; }
if(isset($_POST['editslug'])) { $editslug = $_POST['editslug']; }else { $editslug = ''; }
if(isset($_POST['trashslug'])) { $trashslug = $_POST['trashslug']; }else { $trashslug = ''; }
if(isset($_POST['tooltips'])) { $tooltips =  array_filter(explode(',', $_POST['tooltips'])); }else { $tooltips = ''; }
if(isset($_POST['urlPaths'])) { $urlPaths =  $_POST['urlPaths']; }else { $urlPaths = ''; }
if(isset($_POST['urlslug'])) { $urlslug =  $_POST['urlslug']; }else { $urlslug = ''; }
if(isset($_POST['dateColumns'])) { $dateColumns =  $_POST['dateColumns']; }else { $dateColumns = ''; }
if(isset($_POST['gsc'])) { $gsc =  $_POST['gsc']; }else { $gsc = ''; }
if(isset($_POST['noedits'])) { $noedits =  true; }else { $noedits = false; }
if(isset($_POST['pagelength'])) { $rowperpage = $pagelength =  $_POST['pagelength']; }else { $pagelength = 10; }
## Search 
$searchQuery = " ";
if ($searchValue != '') {
    foreach ($columnNames as $columnName) {
        if(array_key_exists($columnName, $ignoredColumns) || $columnName == 'edit' || $columnName == 'trash' || $columnName == 'delete') { }else{
            $searchQuery .= " OR LOWER($columnName) LIKE LOWER('%$searchValue%')";
        }
    }
    // Remove the initial ' OR ' from $searchQuery
    $searchQuery = ' AND (' . substr($searchQuery, 4) . ')';

}
if(isset($_POST['costumeQuery'])) { $costumeQuery =  $_POST['costumeQuery']; }else { $costumeQuery = ''; }
$sortColumn = $columnNames[$columnIndex]; // Get the column name for sorting
if(isset($_POST['custom_select'])) { $custom_select =  $_POST['custom_select']; }else { $custom_select = "*"; }
if(isset($_POST['custom_where']) && !empty($_POST['custom_where'])) {
    $limit = "LIMIT $row, $rowperpage";
    $where = '1 ' . (!empty(trim($searchQuery)) ? " $searchQuery " : '') . ' AND ' . $_POST['custom_where'] . ' ';
    $order = "ORDER BY $sortColumn $columnSortOrder";
    $custom_where = " $where $order $limit";
}else {
    $limit = "LIMIT $row, $rowperpage";
    $where = '1 '. (!empty(trim($searchQuery)) ? " $searchQuery " : '');
    $order = "ORDER BY $sortColumn $columnSortOrder";
    $custom_where = " $where $order $limit";
}
## Total number of records without filtering
$sel = mysqli_query($conn2, "SELECT COUNT(*) AS allcount FROM $custom_from");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];
## Total number of records with filtering
$sel = mysqli_query($conn2, "SELECT COUNT(*) AS allcount FROM $custom_from WHERE $where");
$records = mysqli_fetch_assoc($sel);
if ($records !== null) {
    $totalRecordwithFilter = $records['allcount'];
} else {
    // Handle the case where $records is null
    $totalRecordwithFilter = 0; // or any other default value you want to assign
}
## Fetch records
$columnNamesStr = implode(', ', $columnNames); // Converts array to comma-separated string
$empQuery = "SELECT $custom_select FROM $custom_from WHERE $custom_where";
$empRecords = mysqli_query($conn2, $empQuery);
$data = array();
$modals = array();
while ($row = mysqli_fetch_assoc($empRecords)) {
    // Use a temporary array to hold the data for each row
    $rowData = array();
    $i=0;
    if(!$noedits){
        $rowData['Edit'] = '';
        $rowData['Trash'] = '';
        $rowData['Delete'] = '';
    }
    if ($row['deleted_at'] == NULL) {
        $deleteclass = '';
    } else {
        $deleteclass = 'text-decoration-line-through text-danger';
    }
    foreach ($columnNames as $columnName) {
        $cellContent = $row[$columnName];
        if (!empty($cellContent) && in_array($columnName, $dateColumns)) {
            $cellContent = time_ago($cellContent);
        }
        if ( $dataArrays && array_key_exists($columnName, $dataArrays)) {
            $dataArray = $dataArrays[$columnName];
            $dataValue = $row[$columnName];
            $cellContent = $dataArray[$dataValue] ?? '';
        }
        if(!empty($row[$columnName])){
            if($cellContent && $tooltips && in_array($columnName, $tooltips)){
                $tooltipContent = $cellContent;
                
                $cellContent = $cellContent !== null && strlen($cellContent) >  $maxlenginfield ? substr($cellContent, 0, $maxlenginfield) . '...' : $cellContent;
                
                $rowData[$columnName] = '<td class="' . $deleteclass . '" data-toggle="tooltip" data-placement="top"
                    title="' . htmlspecialchars($tooltipContent) . '">' . $cellContent . '</td>';
            }elseif ($row[$columnName] && strpos($row[$columnName], 'webp') !== false) {
                if (array_key_exists($columnName, $imagePaths)) {
                    $imagePath = $imagePaths[$columnName]; // Get the image path for the current column
                } else {
                    $imagePath = ''; // Set a default image path or handle other cases as needed
                }
                // If the cell content contains "webp", generate the image tag
                $rowData[$columnName] = '<a href="#" data-toggle="modal" data-target="#imageModal-' . $columnName . '-'. $row['id'] .'">
                    <img src="' . $imagePath . $row[$columnName] . '" alt="' . $columnName . '" alt="glyphicon-glyphicon" style="width:100px; height:auto;">
                        </a><div class="modal fade" id="imageModal-'. $columnName.'-'.$row['id'] . '" tabindex="-' . $i . '"
                        role="dialog" aria-labelledby="imageModalLabel-' . $row['id'] . '"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="imageModalLabel-' . $row['id'] . '">' . $imagePath .'
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                                <div class="modal-body">
                                    <img src="' . $imagePath . $row[$columnName] .'"
                                        alt="' . $columnName . '" style="width:100%; height:auto;">
                                </div>
                            </div>
                        </div>
                    </div>';
                
        } elseif( $urlPaths && array_key_exists($columnName, $urlPaths)){
            $urlColumn = $urlPaths[$columnName];
            $urlValue = $row[$urlColumn];
            $rowData[$columnName] = '<a target="_blank" href="' . $urlslug . $urlValue.'">' . $cellContent . '</a>';
        } elseif ($cellContent && $jsonarrays && in_array($columnName, $jsonarrays)) {
            // Handle the case when the cell content is a non-empty JSON array
            $popupContent = json_decode($cellContent, true);
            if (is_array($popupContent)) {
                $popupValues = array_column($popupContent, 'value');
                $popupContent = implode("<br>", $popupValues);
            } else {
                $popupContent = $popupContent['value'] ?? '';
            }
            $btnContent = strip_tags($cellContent);
            if ($cellContent !== null && strlen($cellContent) > $maxlenginfield) {
                $cellContent = substr($cellContent, 0, $maxlenginfield) . '...';
                $btnContent = strlen($popupContent) > $maxlenginfield ? substr($popupContent, 0, $maxlenginfield) . '...' : $popupContent;
                $btnContent = strip_tags($btnContent);
                $btnContent = htmlspecialchars($btnContent, ENT_QUOTES);
            }
            $rowData[$columnName] = '<button type="button" class="btn btn-secondary m-0 p-2"
            onclick="showPopup(\''. htmlspecialchars($popupContent, ENT_QUOTES) . '\',\'' . $columnName . '\')">' . $btnContent . '</button>';
        } elseif ($cellContent && $popups && in_array($columnName, $popups)) {
            $popupContent = $row[$columnName]; 
            $popupContent = strip_tags($popupContent);
            $popupContent = str_replace('"', '\"', $popupContent);
            $popupContent = str_replace("'", "\'", $popupContent);
            $popupContent = str_replace("\n", "<br>", $popupContent);

            if ($cellContent !== null && strlen($cellContent) > $maxlenginfield) {
                $btnContent = strlen((string)$cellContent) > $maxlenginfield ? substr($cellContent, 0, $maxlenginfield) . '...' : $cellContent; 
            }
            
            $rowData[$columnName] = '<button type="button" class="btn btn-secondary m-0 p-2 popup-btn" data-content="' . htmlspecialchars($popupContent, ENT_QUOTES) .'" data-subject="' . $columnName .'">' . $btnContent .'</button>';
            }elseif ($columnName === $gsc['indexed']){
                $indexingStatus = checkIndexingStatus($urlslug . $urlValue);
                $rowData[$columnName] =  '<td class="' . $deleteclass . '">' . $indexingStatus . '</td>';
            } else {
                // If the cell content does not contain "webp", use the regular value
                $rowData[$columnName] = '<span class="'.$deleteclass.'">'.$row[$columnName].'</span>';
            }
        }else{
            $rowData[$columnName] = '';
        }
    }
    if(!$noedits){
        $rowData['Edit'] = '<a href="'.$url . $folderName . '/'. $editslug .'/' . $row['id'] .'">
            <i class="bi bi-pencil-square"></i>
        </a>';
        if ($lvl > 2) {
            $rowData['Trash'] = '<a href="' . $url . $folderName . '/' . $trashslug . '/' . $row['id'] . '" class="confirmAction">'
                . (($row['deleted_at'] == NULL) ? '<i class="bi bi-trash"></i>' : '<i class="bi bi-arrow-counterclockwise text-danger"></i>')
                . '</a>';
        }
        
        if ($lvl > 9) {
            $rowData['Delete'] = '<a href="#" class="delete-link" data-id="'.$row['id'] .'"><i class="bi bi-x "></i></a>';
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