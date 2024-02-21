<?php session_start();
include '../../include/functions.php';
include '../../include/db.php';
if (!isset($_SESSION['db']) || empty($_SESSION['db'])) {
    die("Error: Database session not set or empty.");
}
$currentDateTime = date('Y-m-d H:i:s');

switch ($_SESSION['db_name']) {
    case "blackbird-training":
        $sitemap = "https://blackbird-training.com/site-map.php";
        break;
    case "blackbird-training.co.uk":
        $sitemap = "https://blackbird-training.co.uk/sitemap.xml";
        break;
    case "agile4training":
        $sitemap = "https://agile4training.com/sitemap.xml";
        break;
    case "agile4training ar":
        $sitemap = "https://agile4training.com/ar/sitemap.xml";
        break;
    case "mercury_dubai":
        $sitemap = "https://mercury-training.net/sitemap.php";
        break;
    case "mercury arabic":
        $sitemap = "https://mercury-training.com/ar/sitemap.php";
        break;
    case "mercury english":
        $sitemap = "https://mercury-training.com/sitemap.php";
        break;
    case "Euro Wings En":
        $sitemap = "https://eurowingstraining.com/sitemap.php";
        break;
    case "Euro Wings Ar":
        $sitemap = "https://eurowingstraining.com/Arabic/sitemap.php";
        break;
    case "mercury-training":
        $sitemap = "https://mercury-training.com/sitemap.php";
        break;
    
    default:
        echo " ";
        break;
}


error_reporting(E_ALL);
if ($_SESSION['userlevel'] > 9 ) {
    // Fetch the XML content from the sitemap
    include 'conf.php';   
    if(is_array($sitemap)){
        
    foreach ($sitemap as $sitemapUrl) {
        $siteurl = 'https://' . parse_url($sitemapUrl, PHP_URL_HOST) . '/';
        $sitemapContent = file_get_contents($sitemapUrl);
        if ($sitemapContent) {
            // Parse the sitemap XML
            $xml = simplexml_load_string($sitemapContent);
    
            if ($xml) {
                // Initialize an array to store the data
                $data = [];
    
                foreach ($xml->url as $url) {
                    // Fetch individual URL data here if needed
                    $urlData = [
                        'loc' => (string)$url->loc,
                        'lastmod' => (string)date('Y-m-d h:i:s',strtotime($url->lastmod)),
                    ];
    
                    $data[] = $urlData;
                }

                // Update existing data, insert new data, and mark deleted data in the 'seo' table
                foreach ($data as $entry) {
                    $link = mysqli_real_escape_string($conn2, $entry['loc']);
                    $updated_at = mysqli_real_escape_string($conn2, $entry['lastmod']);
    
                    // Check if the URL already exists in the 'seo' table
                    $query = "SELECT * FROM seo WHERE link = '$link'";
                    $result = mysqli_query($conn2, $query);
    
                    if (mysqli_num_rows($result) > 0  ) {
                        $row = mysqli_fetch_assoc($result);
                        if(strtotime($updated_at) > strtotime($row['updated_at'])){

                            // Update existing data
                            $_POST['id'] = $row['id'];
                            $_POST['updated_at'] = $updated_at;
                            $_POST['deleted_at'] = NULL;
                            $sql = 'SELECT * FROM seo WHERE id = '.$_POST['id'];
                            $result = mysqli_query($conn2, $sql);
                            if(mysqli_num_rows($result) > 0){
                                $sql = 'update seo set updated_at = "'.$_POST['updated_at'].'" where id = '.$_POST['id'];
                            }
                        }
                        
                    } else {
                        // Insert new data
                        $_POST['link'] = $link;
                        $sql = 'INSERT INTO seo (link, updated_at) VALUES ("'.$_POST['link'].'", "'.$currentDateTime.'")';
                        
                        mysqli_query($conn2, $sql);

                    }
                    //echo 'inserted ' . $entry['loc'];
                    
                }
    
                // if (!empty(array_column($gindex, 'loc'))) {
                //     $url_list = implode(',', array_column($gindex, 'loc'));
                //     $query = "UPDATE seo SET deleted_at = NOW() WHERE deleted_at IS NULL AND link NOT IN ($url_list)";
                //     mysqli_query($conn2, $query);
                // }
    
                $res['type'] = 'Update';
                $res['success'] = true;
                $res['message'] = 'Data updated successfully!.';
                //redirect to the listing page with success message in post data
                //$_SESSION['success'] = true;
                //$_SESSION['msg'] = 'Data updated successfully!';
                //header('Location: view');
            } else {
                $res['type'] = 'Update';
                $res['success'] = false;
                $res['message'] = 'Failed to parse sitemap XML.';
                //$_SESSION['success'] = false;
                //$_SESSION['msg'] = 'Failed to parse sitemap XML.';
                //header('Location: view');
            }
        } else {
            $res['type'] = 'Update';
            $res['success'] = false;
            $res['message'] = 'Failed to fetch sitemap content.';
            //$_SESSION['success'] = false;
            //$_SESSION['msg'] = 'Failed to fetch sitemap content.';
            //header('Location: view');
        }
        
    }

    // Now $sitemapContent contains the merged content of all <urlset> elements
    }else{
        $siteurl = 'https://'.parse_url($sitemap, PHP_URL_HOST).'/';
        $sitemapContent = file_get_contents($sitemap);
        if ($sitemapContent) {
            // Parse the sitemap XML
            $xml = simplexml_load_string($sitemapContent);
    
            if ($xml) {
                // Initialize an array to store the data
                $data = [];
    
                foreach ($xml->url as $url) {
                    // Fetch individual URL data here if needed
                    $urlData = [
                        'loc' => (string)$url->loc,
                        'lastmod' => (string)date('Y-m-d h:i:s',strtotime($url->lastmod)),
                    ];
    
                    $data[] = $urlData;
                }
                
                // Update existing data, insert new data, and mark deleted data in the 'seo' table
                foreach ($data as $entry) {
                    $link = mysqli_real_escape_string($conn2, $entry['loc']);
                    $updated_at = mysqli_real_escape_string($conn2, $entry['lastmod']);
    
                    // Check if the URL already exists in the 'seo' table
                    $query = "SELECT * FROM seo WHERE link = '$link'";
                    $result = mysqli_query($conn2, $query);
    
                    if (mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        // Update existing data
                        $_POST['id'] = $row['id'];
                        $_POST['updated_at'] = $updated_at;
                        $_POST['deleted_at'] = NULL;
                        $sql = 'SELECT * FROM seo WHERE id = '.$_POST['id'];
                            $result = mysqli_query($conn2, $sql);
                            if(mysqli_num_rows($result) > 0){
                                $sql = 'update seo set updated_at = "'.$_POST['updated_at'].'" where id = '.$_POST['id'];
                            }
                        
                    } else {
                        // Insert new data
                        $google ='';
                        $gindexdate ='';
                        $yandex ='';
                        $yindexdate ='';
                        if(isset($google_index) && $google_index != NULL){
                            $google = ", google_index, google_date";
                            $gindexdate = ", ". $google_index .", '".$google_date."'";
                        }
                        if(isset($yandex_index) && $yandex_index != NULL){
                            $yandex = ", yandex_index, yandex_date";
                            $yindexdate = ", ". $yandex_index .", '".$yandex_date."'";
                        }
                        $_POST['link'] = $link;
                        $_POST['updated_at'] = $updated_at;
                        $sql = 'INSERT INTO seo (link, updated_at) VALUES ("'.$_POST['link'].'", "'.$currentDateTime.'")';
                        
                        mysqli_query($conn2, $sql);
                    }
                }

                $res['type'] = 'Update';
                $res['success'] = true;
                $res['message'] = 'Data updated successfully!.';

                //redirect to the listing page with success message in post data
                //$_SESSION['success'] = true;
                //$_SESSION['msg'] = 'Data updated successfully!';
                //header('Location: view');
            } else {
                $res['type'] = 'Update';
                $res['success'] = false;
                $res['message'] = 'Failed to parse sitemap XML.';
                //$_SESSION['success'] = false;
                //$_SESSION['msg'] = 'Failed to parse sitemap XML.';
                //header('Location: view');
            }
        } else {
            $res['type'] = 'Update';
            $res['success'] = false;
            $res['message'] = 'Failed to fetch sitemap content.';
            //$_SESSION['success'] = false;
            //$_SESSION['msg'] = 'Failed to fetch sitemap content.';
            //header('Location: view');
        }
    }

    // Clear any previously generated output
if(isset($isLocal)){
    header('Content-Type: application/json');
    ob_clean();
}
// Echo the JSON response
echo json_encode($res);

if(isset($isLocal)){
    ob_flush();
}
exit();

} else {
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