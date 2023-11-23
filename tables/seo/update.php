<?php
if ($_SESSION['userlevel'] > 9 ) {
    // Fetch the XML content from the sitemap
    include $path.'/conf.php';   
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
                            include 'include/update.php';
                        }
                        
                    } else {
                        // Insert new data
                        $_POST['link'] = $link;
                        include 'include/update.php';
                    }
                    //echo 'inserted ' . $entry['loc'];
                    
                }
    
                // if (!empty(array_column($gindex, 'loc'))) {
                //     $url_list = implode(',', array_column($gindex, 'loc'));
                //     $query = "UPDATE seo SET deleted_at = NOW() WHERE deleted_at IS NULL AND link NOT IN ($url_list)";
                //     mysqli_query($conn2, $query);
                // }
    
                //redirect to the listing page with success message in post data
                //$_SESSION['success'] = true;
                //$_SESSION['msg'] = 'Data updated successfully!';
                //header('Location: view');
            } else {
                //$_SESSION['success'] = false;
                //$_SESSION['msg'] = 'Failed to parse sitemap XML.';
                //header('Location: view');
            }
        } else {
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
                        include 'include/update.php';
                        
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
                        include 'include/update.php';
                    }
                }
                //redirect to the listing page with success message in post data
                //$_SESSION['success'] = true;
                //$_SESSION['msg'] = 'Data updated successfully!';
                //header('Location: view');
            } else {
                //$_SESSION['success'] = false;
                //$_SESSION['msg'] = 'Failed to parse sitemap XML.';
                //header('Location: view');
            }
        } else {
            //$_SESSION['success'] = false;
            //$_SESSION['msg'] = 'Failed to fetch sitemap content.';
            //header('Location: view');
        }
    }

    
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