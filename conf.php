<?php
session_start();
error_reporting(E_ALL);
date_default_timezone_set('Europe/Istanbul');
require 'include/db.php';
require 'include/functions.php';
require 'include/router.php';
require 'include/checktable.php';
if (isset($_SESSION['username']) && isset($_SESSION['userlevel'])) {
    // Session is active
    $username = $_SESSION['username'];
    $userlevel = $_SESSION['userlevel'];
    $photo = GetRow($username, 'username','users', $conn);

    if (isset($photo['photo']) && !empty($photo['photo'])) {
        $userphoto = $url.'assets/images/avatars/'.$photo['photo'];
    } else {
        $userphoto = $url.'assets/images/avatars/avatar-' . rand(1, 15) . '.png';
    }
} else {
    // Session is not active, redirect to the login page
    header("Location: " . $url . "login.php");
    exit();
}
if (!isset($_SESSION['db']) || empty($_SESSION['db'])) {
    $users = GetRow($_SESSION['username'], 'username','users', $conn);
    $_SESSION['user_id'] =  $users['id'];
    $query = "SELECT user_db.db_id,db.company FROM user_db,db WHERE user_db.user_id = '" . mysqli_real_escape_string($conn, $users['id']) . "' AND db.deleted_at IS NULL ORDER BY db.id LIMIT 1 ";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['db'] = $row['db_id'];
        $_SESSION['co'] = $row['company'];
        header("Location: $url");
    }
}

$db = GetRow($_SESSION['db'], 'id','db', $conn);

if (isset($_SESSION['db_name'])) {
    $db_name = $_SESSION['db_name'];
} else {
    // Handle the case where 'db_name' is not set
    $db_name = ''; // or set it to a default value
}
$currentDateTime = date('Y-m-d H:i:s');
if (isset($db['googleapikey']) && !empty($db['googleapikey'])) {
    $gapi = $db['googleapikey'];
} else {
    $gapi = '';
}

if($_SESSION['userlevel'] < 9){
    $editable = false;
}else{
    $editable = true;
}

$gptmode = 'gpt-4-1106-preview';
$ftpServer = $db['ftp_server'];
$ftpUsername = $db['ftp_username'];
$ftpPassword = $db['ftp_password'];
$ftpPort = $db['ftp_port'];

$remoteDirectory = '';
$defaultcurrency = '€';
$categoriestablename = '';
$citiestablename = '';
$citiescountryimgurl = "";
$citiessliderimgurl = "";
$citiesimgurl = "";
switch ($db_name) {
    case "blackbird-training":
        $categoriesimgurl = "https://blackbird-training.com/image/images/cardCategories/";
        $citiescountryimgurl = "https://blackbird-training.com/image/images/flags/";
        $citiessliderimgurl = "https://blackbird-training.com/image/images/bg/";
        $citiesimgurl = "https://blackbird-training.com/image/images/cities/";
        $websiteurl = "https://blackbird-training.com/";
        $sitemap = "https://blackbird-training.com/site-map.php";
        $categoriestablename = 'course_c';
        $categoriesslug = 'course-';
        $citiestablename = 'cities';
        $citiesslug = 'course-';
        $courseslug = 'course-';
        $eventslug = 'event/';
        $defaultcurrency = '£';
        $pageend = '.htm';
        $dashedname = true;
        break;
    case "blackbird-training.co.uk":
        $categoriesimgurl = "https://blackbird-training.co.uk/assets/images/cardCategories/";
        $citiescountryimgurl = "https://blackbird-training.co.uk/assets/images/flags/";
        $citiessliderimgurl = "https://blackbird-training.co.uk/assets/images/bg/";
        $citiesimgurl = "https://blackbird-training.co.uk/assets/images/cities/";
        $courseimgurl = "https://blackbird-training.co.uk/assets/images/courses/";
        $websiteurl = "https://blackbird-training.co.uk/";
        $sitemap = "https://blackbird-training.co.uk/sitemap.xml";
        $categoriestablename = 'course_c';
        $categoriesslug = 'programs/';
        $citiestablename = 'cities';
        $citiesslug = 'city/';
        $courseslug = 'course/';
        $eventslug = 'event/';
        $pageend = '';
        $dashedname = false;
        break;
    case "agile4training":
        $categoriesimgurl = "https://agile4training.com/assets/images/cardCategories/";
        $citiescountryimgurl = "https://agile4training.com/assets/images/flags/";
        $citiessliderimgurl = "https://agile4training.com/assets/images/bg/";
        $citiesimgurl = "https://agile4training.com/assets/images/cities/";
        $courseimgurl = "https://agile4training.com/assets/images/courses/";
        $websiteurl = "https://agile4training.com/";
        $sitemap = "https://agile4training.com/sitemap.xml";
        $categoriestablename = 'course_c';
        $categoriesslug = 'programs/';
        $citiestablename = 'cities';
        $citiesslug = 'c/';
        $courseslug = 'course/';
        $eventslug = 'event/';
        $pageend = '';
        $dashedname = false;
        break;
    case "agile4training ar":
        $categoriesimgurl = "https://agile4training.com/assets/images/cardCategories/";
        $citiescountryimgurl = "https://agile4training.com/assets/images/flags/";
        $citiessliderimgurl = "https://agile4training.com/assets/images/bg/";
        $citiesimgurl = "https://agile4training.com/assets/images/cities/";
        $courseimgurl = "https://agile4training.com/assets/images/courses/";
        $websiteurl = "https://agile4training.com/ar/";
        $sitemap = "https://agile4training.com/ar/sitemap.xml";
        $categoriestablename = 'course_c';
        $categoriesslug = 'programs/';
        $citiestablename = 'cities';
        $citiesslug = 'c/';
        $courseslug = 'course/';
        $eventslug = 'event/';
        $pageend = '';
        $dashedname = false;
        break;
    
    case "mercury_dubai":
        $categoriesimgurl = "https://mercury-training.net/assets/images/gallery/";
        $websiteurl = "https://mercury-training.net/";
        $sitemap = "https://mercury-training.net/sitemap.php";
        $categoriestablename = 'course_c';
        $categoriesslug = '';
        $citiestablename = 'cities';
        $citiesslug = 'index.php?keyword=&city=';
        $courseslug = 'c/';
        $eventslug = 'p/';
        $pageend = '.html';
        $dashedname = false;
        break;
    case "mercury arabic":
        $categoriesimgurl = "https://mercury-training.com/ar/assets/images/gallery/";
        $websiteurl = "https://mercury-training.com/ar/";
        $sitemap = "https://mercury-training.com/ar/sitemap.php";
        $categoriestablename = 'course_c';
        $categoriesslug = '';
        $citiestablename = 'cities';
        $citiesslug = 'index.php?keyword=&city=';
        $courseslug = 'c/';
        $eventslug = 'p/';
        $pageend = '.html';
        $dashedname = false;
        break;
    case "mercury english":
        $categoriesimgurl = "https://mercury-training.com/assets/images/gallery/";
        $websiteurl = "https://mercury-training.com/";
        $sitemap = "https://mercury-training.com/sitemap.php";
        $categoriestablename = 'course_c';
        $categoriesslug = '';
        $citiestablename = 'cities';
        $citiesslug = 'index.php?keyword=&city=';
        $courseslug = 'c/';
        $eventslug = 'p/';
        $pageend = '.html';
        $dashedname = false;
        break;
    case "Euro Wings En":
        $categoriesimgurl = "https://eurowingstraining.com/image/uc/F/";
        $websiteurl = "https://eurowingstraining.com/";
        $sitemap = "https://eurowingstraining.com/sitemap.php";
        $categoriestablename = 'course_c';
        $categoriesslug = 'course-';
        $citiestablename = 'cities';
        $citiesslug = 'course-';
        $courseslug = 'course-';
        $eventslug = 'p/';
        $pageend = '.htm';
        $dashedname = true;
        break;
    case "Euro Wings Ar":
        $categoriesimgurl = "https://eurowingstraining.com/Arabic/image/uc/F/";
        $websiteurl = "https://eurowingstraining.com/Arabic/";
        $sitemap = "https://eurowingstraining.com/Arabic/sitemap.php";
        $categoriestablename = 'course_c';
        $categoriesslug = 'course-';
        $citiestablename = 'cities';
        $citiesslug = 'course-';
        $courseslug = 'course-';
        $eventslug = 'p/';
        $pageend = '.htm';
        $dashedname = true;
        break;
    case "mercury-training":
        $categoriesimgurl = "https://mercury-training.com/assets/images/gallery/";
        $websiteurl = "https://mercury-training.com/";
        $categoriestablename = 'course_c';
        $categoriestitle= 'Categories';
        $sitemap = "https://mercury-training.com/sitemap.php";
        $categoriesslug = '';
        $citiesslug = 'index.php?keyword=&city=';
        $courseslug = 'c/';
        $eventslug = 'p/';
        $pageend = '.html';
        $dashedname = false;
        break;
    
    default:
        echo " ";
        break;
}
?>