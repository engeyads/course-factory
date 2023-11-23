<?php
$path = dirname(__FILE__);
include $path.'/conf.php';

// echo '<pre>';
// print_r($_POST);
// echo '</pre>';

// ... (previous code)

$data = $_POST;
$theid = $data['id']; // Assuming 'id' is provided in your form
$thec_id = $data['c_id'];
$courseData = array();
$nextValue = null;
if (isset($data['next'])) {
    if($data['next'] != ''){
        $nextValue = $data['next'];
    }
}

foreach ($data as $key => $value) {
    $parts = explode('-', $key);

    if ($parts[0] === 'price') {
        $courseId = $parts[1];
        $courseData[$courseId]['c_id'] = $courseId;
        $courseData[$courseId]['price'] = $value;
    } elseif (is_numeric($parts[0])) {
        $cityId = $parts[0];
        $index = $parts[2];
        
        // Extract city-specific start and end dates
        $startDateKey = "$cityId-startdate-$index";
        $endDateKey = "$cityId-enddate-$index";
        
        if (isset($courseData[$cityId])) {
            $courseData[$cityId]['startdate'][$index] = $data[$startDateKey];
            $courseData[$cityId]['enddate'][$index] = $data[$endDateKey];
        } else {
            $courseData[$cityId] = array(
                'city' => $cityId,
                'startdate' => array($index => $data[$startDateKey]),
                'enddate' => array($index => $data[$endDateKey])
            );
        }
    }
}
$_POST = array();
$values = array();
foreach ($courseData as $cityId => $cityCourses) {
    foreach ($cityCourses['startdate'] as $index => $startdate) {
        $enddate = $cityCourses['enddate'][$index];
        
        $startComponents = explode('-', $startdate);
        $endComponents = explode('-', $enddate);
        
        //$_POST['id'] = $theid;
        $_POST['city'] = $cityId;
        $_POST['c_id'] = $thec_id;
        $_POST['price'] = $cityCourses['price'];
        $_POST['y1'] = $startComponents[0];
        $_POST['m1'] = $startComponents[1];
        $_POST['d1'] = $startComponents[2];
        $_POST['y2'] = $endComponents[0];
        $_POST['m2'] = $endComponents[1];
        $_POST['d2'] = $endComponents[2];
        $customDateTime = new DateTime();
        $dateString = $customDateTime->format('Y-m-d');
        $_POST['published_at'] = $dateString;
        include 'include/update.php';
    }
}
echo $data['c_id'];
if($nextValue){
    header('Location: '.$url.'event/addmultiple/'.$nextValue);
}else{
    header('Location: '.$url.'event/addmultiple/'.$data['c_id']);
}
exit();
?>