<?php
    $path = dirname(__FILE__);
    include $path.'/conf.php';   
    
if($_SERVER['REQUEST_METHOD'] === 'GET'){
    if($_SESSION['userlevel'] > 9){
        $id = $_GET['id'];
        $duration = $_GET['newDuration'];
    }
}elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id = $_POST['id'];
    $duration = $_POST['newDuration'];
}else{
    exit;
}
    // update columns in course table using id by make date using columns d1,m1,y1 from their date to new $duration storing new duration in d2,m2,y2 
    echo $sql = "UPDATE course 
    SET 
        d2 = DAY(DATE_ADD(CONCAT(y1, '-', m1, '-', d1), INTERVAL $duration DAY)),
        m2 = MONTH(DATE_ADD(CONCAT(y1, '-', m1, '-', d1), INTERVAL $duration DAY)),
        y2 = YEAR(DATE_ADD(CONCAT(y1, '-', m1, '-', d1), INTERVAL $duration DAY))
    WHERE id = '$id'";

    mysqli_query($conn2, $sql);
    if (mysqli_affected_rows($conn2) > 0) {
        $response = 'success';
        //header("Refresh: 1; url=" . $url . "event/fixdurations");
    } else {
        $response = 'Error: ' . mysqli_error($conn2);
    }
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    // Clear any previously generated output
    ob_clean();

    // Set the appropriate headers
    header('Content-Length: ' . strlen($response));
    // Send the JSON response
    echo $response;
    // Flush the output buffer
    ob_flush();
    exit;
}
?>
