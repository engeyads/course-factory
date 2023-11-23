<pre><?php
if (isset($_POST['results']) && !empty($_POST['results'])) {
    $jsonarray = json_decode($_POST['results'], true);
    // rest of your code
} else {
    echo "No results posted.";
    exit();
}
$subtag_id = $_POST['subtag_id'];
foreach ($jsonarray as $key => $value) {
    $courseName = mysqli_real_escape_string($conn, $value['courseName']);
    $keywords = implode(',', $value['keywords']);
    $keywordsprearray = explode(',', $keywords);
    $jsonkeywordsarray = [];
    foreach ($keywordsprearray as $keywordpre) {
        $jsonkeywordsarray[] = ['value' => trim($keywordpre)];
    }
    $thejson = json_encode($jsonkeywordsarray, JSON_PRETTY_PRINT);
    $checkSql = "SELECT * FROM `course_main` WHERE `name` = '$courseName' ";
    $checkResult = mysqli_query($conn, $checkSql);
    if (mysqli_num_rows($checkResult) > 0) {
        echo "The course '$courseName' already exists <br />";
    } else {
        $insertSql = "INSERT INTO `course_main` (`name`, `subtag_id`, `keyword`) VALUES ('$courseName', '$subtag_id', '$thejson')";
        $insertResult = mysqli_query($conn, $insertSql);
        if ($insertResult) {
            echo "The course '$courseName' inserted successfully  <br />";
        } else {
            echo "The course '$courseName' Failed to insert  <br />";
        }
    }
}
?>