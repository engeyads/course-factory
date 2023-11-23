<?php
if ($_SESSION['userlevel'] > 9 ) {
$path = dirname(__FILE__);
include $path.'/conf.php'; 
include_once 'functions.php';
if(isset($_POST['action']) && $_POST['action'] == 'check_speed'){
    $speed = getPageSpeedInsightsData($_POST['url']);
    if($speed != 'error'){
       
        $_POST['link'] = $_POST['url'];
        $_POST['speed_test'] = $speed;
        $_POST['speed_date'] = date('Y-m-d H:i:s');
        include 'include/update.php';
        ob_clean();
        // Set the appropriate headers
        header('Content-Type: application/json');
        header('Content-Length: ' . strlen($speed));
        echo $speed;
        ob_flush();
        exit();
    }
}
if(isset($_POST['action'] ) && $_POST['action'] == 'check_google_index'){
    $indexed = is_indexed('sc-domain:agile4training.com',$_POST['url']);
    if (is_string($indexed) && strpos($indexed, '403 Forbidden') !== false) {
        echo $indexed;
    } else {
        $_POST['id'] = $_POST['id'];
        $_POST['link'] = $_POST['url'];
        $_POST['google_date'] = date('Y-m-d H:i:s');
        $_POST['google_index'] = 0;
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
        ob_clean();
        // Set the appropriate headers
        header('Content-Type: application/json');
        header('Content-Length: ' . strlen(json_encode($indexed)));
        echo json_encode($indexed);
        ob_flush();
        exit();
    // }
}
}
if(isset($_POST['action'] ) && $_POST['action'] == 'check_google_sitemap'){
    $response = submitGoogleSitemap('https://'.parse_url($_POST['url'], PHP_URL_HOST), $_POST['url']);
    print_r($response);
}

if(isset($_POST['action'] ) && $_POST['action'] == 'check_yandex_index'){
    $indexed = yandex_index('https:'.parse_url($_POST['url'], PHP_URL_HOST).':443', $_POST['url']);
   
    // print_r($result);
    // if($indexed != 'error'){
    //     $_POST['id'] = $_POST['id'];
    //     $_POST['link'] = $_POST['url'];
    //     $_POST['yandex_index'] = $indexed;
    //     $_POST['yandex_date'] = date('Y-m-d H:i:s');
    //     include 'include/update.php';
    //     ob_clean();
    //     // Set the appropriate headers
    //     header('Content-Type: application/json');
    //     header('Content-Length: ' . strlen($indexed));
    //     echo $indexed;
    //     ob_flush();
    //     exit();
    // }
}
if(isset($_POST['action'] ) && $_POST['action'] == 'check_bing_index'){
    $indexed = is_indexedbulk($_POST['url'], 'bing');
    echo $indexed;

    // if($indexed != 'error'){
    //     $_POST['id'] = $_POST['id'];
    //     $_POST['link'] = $_POST['url'];
    //     $_POST['bing_index'] = $indexed;
    //     $_POST['bing_date'] = date('Y-m-d H:i:s');
    //     include 'include/update.php';
    //     ob_clean();
    //     // Set the appropriate headers
    //     header('Content-Type: application/json');
    //     header('Content-Length: ' . strlen($indexed));
    //     echo $indexed;
    //     ob_flush();
    //     exit();
    // }
}

}else{

  ?>
  <div class="alert border-0 bg-light-warning alert-dismissible fade show py-2">
      <div class="d-flex align-items-center">
          <div class="fs-3 text-warning"><i class="bi bi-exclamation-triangle-fill"></i>
          </div>
          <div class="ms-3">
          <div class="text-warning">You are not allowed to access this page!</div>
          </div>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php

}
?>