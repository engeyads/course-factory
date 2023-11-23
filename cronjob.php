<?php
// session_start();
// ob_start(); 
// date_default_timezone_set('Europe/Istanbul');
 // Create a connection
 include 'include/functions.php';
$conn2 = new mysqli($servername, $username, $password);

// Check the connection
if ($conn2->connect_error) {
    die("Connection failed: " . $conn2->connect_error);
}

// Select the database
if ($conn2->select_db($database)) {
   
    


if(isset($getaction)){
    switch($getaction){
        case 'speed_cron':
            if(isset($getco) && !empty($getco)){

                //if(!isset($_SESSION['username'])){
                    include 'include/db.php';
                //}
                $db = GetRow($getco, 'id','db', $conn);
                if(!isset($_SESSION['username'])){
                    include 'include/db.php';
                    $cronjob = 'cronjob';
                }
                $tablename = 'seo';
                $theconnection = $conn2;
                $currentDateTime = date('Y-m-d H:i:s');
                $query = "SELECT *
                FROM seo
                WHERE (speed_date < updated_at OR speed_date IS NULL)
                    AND deleted_at IS NULL
                ORDER BY id ASC
                LIMIT 1;";
                $result = mysqli_query($conn2, $query);
                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $speed = getPageSpeedInsightsData($row['link']);
                    if($speed != 'error'){
                        $_POST['id'] = $row['id'];
                        $_POST['link'] = $row['link'];
                        $_POST['speed_test'] = $speed;
                        $_POST['speed_date'] = date('Y-m-d H:i:s');
                        $_POST['updated_at'] = $row['updated_at'];
                        include 'include\update.php';
                        $response = json_encode(array('status' => 200, 'speed' => $speed*100,'last_updated' => $row['updated_at'] ,'last_check' => date('Y-m-d H:i:s')));
                        ob_start(); 
                        // Set the appropriate headers
                        header('Content-Type: application/json');
                        header("HTTP/1.0 200 OK");
                        header('Content-Length: ' . strlen($response)); // Set Content-Length to the length of the JSON response
                        echo $response;
                        ob_flush();
                        exit();
                    }
                }
            }
        break;
        case 'google_cron':
            if(isset($getco) && !empty($getco)){
                if(isset($geturl) && !empty($geturl)){
                    include 'vendor/autoload.php';

                    include 'include/db.php';
                    $db = GetRow($getco, 'company','db', $conn);
                    if(!isset($_SESSION['username'])){
                        include 'include/db.php';
                        $cronjob = 'cronjob';
                    }
                    $tablename = 'seo';
                    $theconnection = $conn2;
                    $currentDateTime = date('Y-m-d H:i:s');
                    $query = "SELECT *
                    FROM seo
                    WHERE (google_date < updated_at OR google_date IS NULL)
                        AND deleted_at IS NULL
                    ORDER BY id ASC
                    LIMIT 1;";
                    $result = mysqli_query($conn2, $query);
                    if ($result && mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        $indexed = is_indexed('sc-domain:'.$geturl,$row['link']);
                        if (is_string($indexed) && strpos($indexed, '403 Forbidden') !== false) {
                            echo json_encode($indexed);
                        } else {
                            $_POST['id'] = $row['id'];
                            $_POST['link'] = $row['link'];
                            $_POST['google_date'] = date('Y-m-d H:i:s');
                            $_POST['google_index'] = 0;
                            $_POST['updated_at'] = $row['updated_at'];
                        if (isset($indexed['inspectionResult']['indexStatusResult']['verdict'])) {
                                $_POST['verdict'] = $indexed['inspectionResult']['indexStatusResult']['verdict'];
                            }else{
                                $_POST['verdict'] = '';
                            }
                            if (isset($indexed['inspectionResult']['indexStatusResult']['coverageState'])) {
                                $_POST['coverageState'] = $indexed['inspectionResult']['indexStatusResult']['coverageState'];
                            }else{
                                $_POST['coverageState'] = '';
                            }
                            if (isset($indexed['inspectionResult']['indexStatusResult']['robotsTxtState'])) {
                                $_POST['robotsTxtState'] = $indexed['inspectionResult']['indexStatusResult']['robotsTxtState'];
                            }else{
                                $_POST['robotsTxtState'] = '';
                            }
                            if (isset($indexed['inspectionResult']['indexStatusResult']['indexingState'])) {
                                $_POST['indexingState'] = $indexed['inspectionResult']['indexStatusResult']['indexingState'];
                                if($indexed['inspectionResult']['indexStatusResult']['indexingState'] == 'INDEXING_ALLOWED'){
                                    $_POST['google_index'] = 1;
                                }
                            }else{
                                $_POST['indexingState'] = '';
                            }
                            if (isset($indexed['inspectionResult']['indexStatusResult']['lastCrawlTime'])) {
                                $_POST['lastCrawlTime'] = $indexed['inspectionResult']['indexStatusResult']['lastCrawlTime'];
                            }else{
                                $_POST['lastCrawlTime'] = '';
                            }
                            if (isset($indexed['inspectionResult']['indexStatusResult']['pageFetchState'])) {
                                $_POST['pageFetchState'] = $indexed['inspectionResult']['indexStatusResult']['pageFetchState'];
                            }else{
                                $_POST['pageFetchState'] = '';
                            }
                            if (isset($indexed['inspectionResult']['indexStatusResult']['googleCanonical'])) {
                                $_POST['googleCanonical'] = $indexed['inspectionResult']['indexStatusResult']['googleCanonical'];
                            }else{
                                $_POST['googleCanonical'] = '';
                            }
                            $indexed['indexed'] = $_POST['google_index'];
                            include 'include/update.php';
                            $response = json_encode(array('status' => 200, 'index_status' => $indexed, 'last_updated' => $row['updated_at'] ,'last_check' => date('Y-m-d H:i:s')));
                            ob_clean();
                            // Set the appropriate headers
                            header('Content-Type: application/json');
                            header('Content-Length: ' . strlen(json_encode($indexed)));
                            header('Content-Length: ' . strlen($response)); // Set Content-Length to the length of the JSON response
                            echo $response;  // Modified line
                            ob_flush();
                            exit();
                        }
                    }
                    
                }else{
                    ob_clean();
                    // Set the appropriate headers
                    header('Content-Type: application/json');
                    header("HTTP/1.0 400 Bad Request");
                    echo json_encode('Bad Request: Missing URL Parameter');
                    ob_flush();
                    exit();
                }
            }else{
                ob_clean();
                // Set the appropriate headers
                header('Content-Type: application/json');
                header("HTTP/1.0 400 Bad Request");
                echo json_encode('Bad Request: Missing URL Parameter');
                ob_flush();
                exit();
            }
        break;
        case 'fixold':
            if(isset($getco) && !empty($getco)){
                
                if(isset($conn2)){
                $cronjob = 'cronjob';
                if(isset($getlimit)){
                    if(is_numeric($getlimit)){
                        $limit = $getlimit;
                    }else{
                        $limit = 25;
                    }
                }
                
                $currentDateTime = date('Y-m-d H:i:s');
                $tablename = 'course';
                
                $theconnection = $conn2;
                $custom_select = "* ,TIMESTAMP(CONCAT(y1, '-', LPAD(m1, 2, '0'), '-', LPAD(d1, 2, '0'))) AS start_date, TIMESTAMP(CONCAT(y2, '-', LPAD(m2, 2, '0'), '-', LPAD(d2, 2, '0'))) AS end_date";
                $custom_where = " TIMESTAMP(CONCAT(y1, '-', LPAD(m1, 2, '0'), '-', LPAD(d1, 2, '0'))) <= CURDATE()";
                $query = "SELECT $custom_select FROM `course` WHERE $custom_where limit $limit";
                $records = mysqli_query($conn2, $query);
                if (mysqli_num_rows($records) > 0) { 
                    $arr = [];
                        while ($row = mysqli_fetch_assoc($records)) {
                            
                            $id= $row['id'];
                            $eventDate =  date_create($row['start_date']) ;
                            $eventDate->add(new DateInterval('P52W'));
                            $resultDate = $eventDate->format('Y-m-d H:i:s');
                            $resultDated = new DateTime($resultDate);
                            $y1 = $resultDated->format('Y');
                            $m1 = $resultDated->format('m');
                            $d1 = $resultDated->format('d');
                            $eventDate =  $row['start_date'] ;
                            
                            $endDate =  date_create($row['end_date']) ;
                            $endDate->add(new DateInterval('P52W'));
                            $resultEndDate = $endDate->format('Y-m-d H:i:s');
                            $resultEndDated = new DateTime($resultEndDate);
                            $y2 = $resultEndDated->format('Y');
                            $m2 = $resultEndDated->format('m');
                            $d2 = $resultEndDated->format('d');
                            $endDate =  $row['end_date'] ;
                            
                            
                            $_POST['id'] = $row['id'];
                            $_POST['y1'] = $y1;
                            $_POST['m1'] = $m1;
                            $_POST['d1'] = $d1;
                            $_POST['y2'] = $y2;
                            $_POST['m2'] = $m2;
                            $_POST['d2'] = $d2;
                            $_POST['certain'] = null;

                            include 'include/update.php';
                            $arr['data'][] = ['db' => $database,'id' => $id,'data'=>[
                                'start_date' => $row['start_date'],
                                'new_start_date' => $resultDate,
                                'end_date' => $row['end_date'],
                                'new_end_date' => $resultEndDate,
                                'updated_at' => date('Y-m-d H:i:s')
                            ]];
                        }
                    }
                if(isset($resultDate) && $resultDate != null){
                    //$arr[]= ["Date after adding 52 weeks: " . $resultDate];
                }else{
                    $arr[]= ['db' => $database,'message' => "All events are up to date."];
                }
                $response = json_encode(array('status' => 200,  $arr,'last_updated' => date('Y-m-d H:i:s') ,'last_check' => date('Y-m-d H:i:s')));
                ob_start(); 
                // Set the appropriate headers
                header('Content-Type: application/json');
                header("HTTP/1.0 200 OK");
                header('Content-Length: ' . strlen($response)); // Set Content-Length to the length of the JSON response
                echo $response;
                ob_flush();
                
                }else{
                    ob_clean();
                    // Set the appropriate headers
                    header('Content-Type: application/json');
                    header("HTTP/1.0 400 Bad Request");
                    echo json_encode(array('status'=> 400,'value'=>'Bad Request: DB not found!'));
                    ob_flush();
                    exit();
                }
            }else{
                ob_clean();
                // Set the appropriate headers
                header('Content-Type: application/json');
                header("HTTP/1.0 400 Bad Request");
                echo json_encode('Bad Request: Missing URL Parameter');
                ob_flush();
                exit();
            }
        break;
        default:
            ob_clean();
            // Set the appropriate headers
            header('Content-Type: application/json');
            header("HTTP/1.0 400 Bad Request");
            echo json_encode('Bad Request: Missing URL Parameter');
            ob_flush();
            exit();
    }
}
} else {
    echo "Error selecting database: " . $conn2->error;
}

// Close the connection (when you're done)


$conn2->close();
?>