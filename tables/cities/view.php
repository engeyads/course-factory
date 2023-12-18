<?php
if ($_SESSION['userlevel'] > 2 ) {
    $path = dirname(__FILE__);
    include $path.'/conf.php';   
$ajaxview= true;
switch ($db_name) {
        case "agile4training":
            $ignoredColumns = ['country_image','s_alias', 'color', 'another_column', 'deleted_at','publish'];
            $tooltips = ['', 'title' ];
            $popups = ['description', 'keyword', 'text','about'];
            $jsonarrays = ['keyword', 'xx'];
            $urlPaths = [];
            $editPath = 'name';
            $urlPath = 's_alias';
            // $no_link = true;
            // $no_edits = true;
            //$gsc = ['indexed' => 's_alias'];
            $fieldTitles = ['countryname' => 'Country','hotel_name' => 'Hotel','x'=>'Repeat (A)','x_b'=>'Repeat (B)','x_c'=>'Repeat (C)','w1_p'=>'Price (A) W1','w2_p'=>'Price (A) W2','w3_p'=>'Price (A) W3','w1_p_b'=>'Price (B) W1','w2_p_b'=>'Price (B) W2','w3_p_b'=>'Price (B) W3','w1_p_b'=>'Price (C) W1','w2_p_c'=>'Price (C) W2','w3_p_c'=>'Price (C) W3','country_photo' => 'Country Image','slider_photo' => 'Slider Image','city_photo' => 'Image', 'sh' => 'Short', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $dateColumns = ['created_at', 'updated_at','published_at']; // replace with your actual date columns
            $dataArrays = [
                //'subtag_id' => $subtags,
                // Add more column mappings here
            ];
            $imagePaths = ['city_photo' => $citiesimgurl,'slider_photo' => $citiessliderimgurl];
            $urlslug = $websiteurl .$citiesslug;
        break;
        case "agile4training ar":
            $ignoredColumns = ['country_image','s_alias', 'color', 'another_column', 'deleted_at','publish'];
            $tooltips = ['', 'title' ];
            $popups = ['description', 'keyword', 'text','about'];
            $jsonarrays = ['keyword', 'xx'];
            $urlPaths = [];
            $editPath = 'name';
            $urlPath = 's_alias';
            // $no_link = true;
            // $no_edits = true;
            //$gsc = ['indexed' => 's_alias'];
            $fieldTitles = ['countryname' => 'Country','hotel_name' => 'Hotel','x'=>'Repeat (A)','x_b'=>'Repeat (B)','x_c'=>'Repeat (C)','w1_p'=>'Price (A) W1','w2_p'=>'Price (A) W2','w3_p'=>'Price (A) W3','w1_p_b'=>'Price (B) W1','w2_p_b'=>'Price (B) W2','w3_p_b'=>'Price (B) W3','w1_p_b'=>'Price (C) W1','w2_p_c'=>'Price (C) W2','w3_p_c'=>'Price (C) W3','country_photo' => 'Country Image','slider_photo' => 'Slider Image','city_photo' => 'Image', 'sh' => 'Short', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $dateColumns = ['created_at', 'updated_at','published_at']; // replace with your actual date columns
            $dataArrays = [
                //'subtag_id' => $subtags,
                // Add more column mappings here
            ];

            
            $imagePaths = ['city_photo' => $citiesimgurl,'slider_photo' => $citiessliderimgurl];
            $urlslug = $websiteurl .$citiesslug;
        break;
        case "blackbird-training.co.uk":
            $ignoredColumns = ['country_image','s_alias', 'color', 'another_column', 'deleted_at','publish'];
            $tooltips = ['', 'title' ];
            $popups = ['description', 'keyword', 'text','about'];
            $jsonarrays = ['keyword', 'xx'];
            $urlPaths = [];
            $editPath = 'name';
            $urlPath = 's_alias';
            // $no_link = true;
            // $no_edits = true;
            //$gsc = ['indexed' => 's_alias'];
            $fieldTitles = ['countryname' => 'Country','hotel_name' => 'Hotel','x'=>'Repeat (A)','x_b'=>'Repeat (B)','x_c'=>'Repeat (C)','w1_p'=>'Price (A) W1','w2_p'=>'Price (A) W2','w3_p'=>'Price (A) W3','w1_p_b'=>'Price (B) W1','w2_p_b'=>'Price (B) W2','w3_p_b'=>'Price (B) W3','w1_p_b'=>'Price (C) W1','w2_p_c'=>'Price (C) W2','w3_p_c'=>'Price (C) W3','country_photo' => 'Country Image','slider_photo' => 'Slider Image','city_photo' => 'Image', 'sh' => 'Short', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $dateColumns = ['created_at', 'updated_at','published_at']; // replace with your actual date columns
            $dataArrays = [
                //'subtag_id' => $subtags,
                // Add more column mappings here
            ];
            $imagePaths = ['city_photo' => $citiesimgurl,'slider_photo' => $citiessliderimgurl];
            $urlslug = $websiteurl .$citiesslug;
            break;
        case "mercury_dubai":
            $ignoredColumns = ['map','country_image','alias', 'color', 'another_column', 'deleted_at','publish'];
            $tooltips = ['', 'title' , 'hotel_link', 'address'];
            $popups = ['description', 'keyword', 'text','about','Business','useful_information','National_Holidays','Currency','Customs','Health_Care','Currency_Exchange','Safety_Tips','Tax_Tipping','Tourist_Information','Useful_Telephone','Visas','Weather'];
            $jsonarrays = ['keyword', 'xx'];
            $urlPaths = [];
            $editPath = 'name';
            $urlPath = 'id';
            // $no_link = true;
            // $no_edits = true;
            //$gsc = ['indexed' => 's_alias'];
            $fieldTitles = ['countryname' => 'Country','hotel_name' => 'Hotel','x'=>'Repeat (A)','x_b'=>'Repeat (B)','x_c'=>'Repeat (C)','w1_p'=>'Price (A) W1','w2_p'=>'Price (A) W2','w3_p'=>'Price (A) W3','w1_p_b'=>'Price (B) W1','w2_p_b'=>'Price (B) W2','w3_p_b'=>'Price (B) W3','w1_p_b'=>'Price (C) W1','w2_p_c'=>'Price (C) W2','w3_p_c'=>'Price (C) W3','country_photo' => 'Country Image','slider_photo' => 'Slider Image','city_photo' => 'Image', 'sh' => 'Short', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $dateColumns = ['created_at', 'updated_at','published_at']; // replace with your actual date columns
            $dataArrays = [
                //'subtag_id' => $subtags,
                // Add more column mappings here
            ];
            $imagePaths = [];
            $pageend = '';
            $urlslug = $websiteurl .$citiesslug;
            break;
        case "mercury arabic":
            $ignoredColumns = ['map','country_image','alias', 'color', 'another_column', 'deleted_at','publish','description', 'keyword', 'text','about','Business','useful_information','National_Holidays','Currency','Customs','Health_Care','Currency_Exchange','Safety_Tips','Tax_Tipping','Tourist_Information','Useful_Telephone','Visas','Weather','booking.com','hotel_link','address','hotel_logo','hotel_photo','hotel_name','name_anas'];
            $tooltips = ['title'];
            $popups = ['description', 'keyword', 'text','about','Business','useful_information','National_Holidays','Currency','Customs','Health_Care','Currency_Exchange','Safety_Tips','Tax_Tipping','Tourist_Information','Useful_Telephone','Visas','Weather'];
            $jsonarrays = [];
            $urlPaths = [];
            $editPath = 'name';
            $urlPath = 'id';
            // $no_link = true;
            // $no_edits = true;
            //$gsc = ['indexed' => 's_alias'];
            $fieldTitles = ['booking.com'=>'Booking','countryname' => 'Country','hotel_name' => 'Hotel','x'=>'Repeat (A)','x_b'=>'Repeat (B)','x_c'=>'Repeat (C)','w1_p'=>'Price (A) W1','w2_p'=>'Price (A) W2','w3_p'=>'Price (A) W3','w1_p_b'=>'Price (B) W1','w2_p_b'=>'Price (B) W2','w3_p_b'=>'Price (B) W3','w1_p_b'=>'Price (C) W1','w2_p_c'=>'Price (C) W2','w3_p_c'=>'Price (C) W3','country_photo' => 'Country Image','slider_photo' => 'Slider Image','city_photo' => 'Image', 'sh' => 'Short', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $dateColumns = ['created_at', 'updated_at','published_at']; // replace with your actual date columns
            $mondays=[0=>'Sunday',1=>'Monday'];
            $dataArrays = [
                //'subtag_id' => $subtags,
                // Add more column mappings here
                'monday' => $mondays,
            ];
            $imagePaths = [];
            $pageend = '';
            $urlslug = $websiteurl .$citiesslug;
            break;
        case "mercury english":
            $ignoredColumns = ['map','country_image','alias', 'color', 'another_column', 'deleted_at','publish','description', 'keyword', 'text','about','Business','useful_information','National_Holidays','Currency','Customs','Health_Care','Currency_Exchange','Safety_Tips','Tax_Tipping','Tourist_Information','Useful_Telephone','Visas','Weather','booking.com','hotel_link','address','hotel_logo','hotel_photo','hotel_name','name_anas'];
            $tooltips = ['title'];
            $popups = ['description', 'keyword', 'text','about','Business','useful_information','National_Holidays','Currency','Customs','Health_Care','Currency_Exchange','Safety_Tips','Tax_Tipping','Tourist_Information','Useful_Telephone','Visas','Weather'];
            $jsonarrays = [];
            $urlPaths = [];
            $editPath = 'name';
            $urlPath = 'id';
            // $no_link = true;
            // $no_edits = true;
            //$gsc = ['indexed' => 's_alias'];
            $fieldTitles = ['countryname' => 'Country','hotel_name' => 'Hotel','x'=>'Repeat (A)','x_b'=>'Repeat (B)','x_c'=>'Repeat (C)','w1_p'=>'Price (A) W1','w2_p'=>'Price (A) W2','w3_p'=>'Price (A) W3','w1_p_b'=>'Price (B) W1','w2_p_b'=>'Price (B) W2','w3_p_b'=>'Price (B) W3','w1_p_b'=>'Price (C) W1','w2_p_c'=>'Price (C) W2','w3_p_c'=>'Price (C) W3','country_photo' => 'Country Image','slider_photo' => 'Slider Image','city_photo' => 'Image', 'sh' => 'Short', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $dateColumns = ['created_at', 'updated_at','published_at']; // replace with your actual date columns
            $mondays=[0=>'Sunday',1=>'Monday'];
            $dataArrays = [
                //'subtag_id' => $subtags,
                // Add more column mappings here
                'monday' => $mondays,
            ];
            $imagePaths = [];
            $pageend = '';
            $urlslug = $websiteurl .$citiesslug;
            break;
        case "mercury-training":
            $ignoredColumns = ['map','country_image','alias', 'color', 'another_column', 'deleted_at','publish'];
            $tooltips = ['', 'title' , 'hotel_link', 'address'];
            $popups = ['description', 'keyword', 'text','about','Business','useful_information','National_Holidays','Currency','Customs','Health_Care','Currency_Exchange','Safety_Tips','Tax_Tipping','Tourist_Information','Useful_Telephone','Visas','Weather'];
            $jsonarrays = ['keyword', 'xx'];
            $urlPaths = [];
            $editPath = 'name';
            $urlPath = 'id';
            // $no_link = true;
            // $no_edits = true;
            //$gsc = ['indexed' => 's_alias'];
            $fieldTitles = ['countryname' => 'Country','hotel_name' => 'Hotel','x'=>'Repeat (A)','x_b'=>'Repeat (B)','x_c'=>'Repeat (C)','w1_p'=>'Price (A) W1','w2_p'=>'Price (A) W2','w3_p'=>'Price (A) W3','w1_p_b'=>'Price (B) W1','w2_p_b'=>'Price (B) W2','w3_p_b'=>'Price (B) W3','w1_p_b'=>'Price (C) W1','w2_p_c'=>'Price (C) W2','w3_p_c'=>'Price (C) W3','country_photo' => 'Country Image','slider_photo' => 'Slider Image','city_photo' => 'Image', 'sh' => 'Short', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $dateColumns = ['created_at', 'updated_at','published_at']; // replace with your actual date columns
            $dataArrays = [
                //'subtag_id' => $subtags,
                // Add more column mappings here
            ];
            $imagePaths = [];
            $pageend = '';
            $urlslug = $websiteurl .$citiesslug;
            break;
        case "blackbird-training":
            $ignoredColumns = ['country_image','alias', 'color', 'another_column', 'deleted_at','publish','Things_to_do_and_places_to_visit', 'hotel_address', 'embed_map', 'hotel_logo'];
            $tooltips = ['', 'title' ];
            $popups = ['description', 'keyword', 'text','about'];
            $jsonarrays = ['keyword', 'xx'];
            $urlPaths = [];
            $editPath = 'name';
            $urlPath = 's_alias';
            // $no_link = true;
            // $no_edits = true;
            //$gsc = ['indexed' => 's_alias'];
            $fieldTitles = ['countryname' => 'Country','hotel_name' => 'Hotel','x'=>'Repeat (A)','x_b'=>'Repeat (B)','x_c'=>'Repeat (C)','w1_p'=>'Price (A) W1','w2_p'=>'Price (A) W2','w3_p'=>'Price (A) W3','w1_p_b'=>'Price (B) W1','w2_p_b'=>'Price (B) W2','w3_p_b'=>'Price (B) W3','w1_p_b'=>'Price (C) W1','w2_p_c'=>'Price (C) W2','w3_p_c'=>'Price (C) W3','country_photo' => 'Country Image','slider_photo' => 'Slider Image','city_photo' => 'Image', 'sh' => 'Short', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $dateColumns = ['created_at', 'updated_at','published_at']; // replace with your actual date columns
            $dataArrays = [
                //'subtag_id' => $subtags,
                // Add more column mappings here
            ];
            $outsource = ['hotel_link'];
            $imagePaths = [];
            $pageend = '';
            $urlslug = $websiteurl .$citiesslug;
            break;
            case "Euro Wings En":
                $ignoredColumns = ['country_image','alias', 'color', 'another_column', 'deleted_at','publish'];
            $tooltips = ['', 'title' ];
            $popups = ['description', 'keyword', 'text','about'];
            $jsonarrays = ['keyword', 'xx'];
            $urlPaths = [];
            $editPath = 'name';
            $urlPath = 's_alias';
            // $no_link = true;
            // $no_edits = true;
            //$gsc = ['indexed' => 's_alias'];
            $fieldTitles = ['countryname' => 'Country','hotel_name' => 'Hotel','x'=>'Repeat (A)','x_b'=>'Repeat (B)','x_c'=>'Repeat (C)','w1_p'=>'Price (A) W1','w2_p'=>'Price (A) W2','w3_p'=>'Price (A) W3','w1_p_b'=>'Price (B) W1','w2_p_b'=>'Price (B) W2','w3_p_b'=>'Price (B) W3','w1_p_b'=>'Price (C) W1','w2_p_c'=>'Price (C) W2','w3_p_c'=>'Price (C) W3','country_photo' => 'Country Image','slider_photo' => 'Slider Image','city_photo' => 'Image', 'sh' => 'Short', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $dateColumns = ['created_at', 'updated_at','published_at']; // replace with your actual date columns
            $dataArrays = [
                //'subtag_id' => $subtags,
                // Add more column mappings here
            ];
            $urlslug = $websiteurl .$citiesslug;
            $imagePaths = [];
                break;
            case "Euro Wings Ar":
                $ignoredColumns = ['country_image','alias', 'color', 'another_column', 'deleted_at','publish'];
            $tooltips = ['', 'title' ];
            $popups = ['description', 'keyword', 'text','about'];
            $jsonarrays = ['keyword', 'xx'];
            $urlPaths = [];
            $editPath = 'name';
            $urlPath = 's_alias';
            // $no_link = true;
            // $no_edits = true;
            //$gsc = ['indexed' => 's_alias'];
            $fieldTitles = ['countryname' => 'Country','hotel_name' => 'Hotel','x'=>'Repeat (A)','x_b'=>'Repeat (B)','x_c'=>'Repeat (C)','w1_p'=>'Price (A) W1','w2_p'=>'Price (A) W2','w3_p'=>'Price (A) W3','w1_p_b'=>'Price (B) W1','w2_p_b'=>'Price (B) W2','w3_p_b'=>'Price (B) W3','w1_p_b'=>'Price (C) W1','w2_p_c'=>'Price (C) W2','w3_p_c'=>'Price (C) W3','country_photo' => 'Country Image','slider_photo' => 'Slider Image','city_photo' => 'Image', 'sh' => 'Short', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
            $dateColumns = ['created_at', 'updated_at','published_at']; // replace with your actual date columns
            $dataArrays = [
                //'subtag_id' => $subtags,
                // Add more column mappings here
            ];
            $urlslug = $websiteurl .$citiesslug;
            $imagePaths = [];
                break;
        default:
            echo " ";
            break;
    }
    include 'include/view.php';
    include 'include/logview.php';
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
