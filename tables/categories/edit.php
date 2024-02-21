<?php
if ($_SESSION['userlevel'] > 2 ) {
    ?><div class="alert border-0 bg-light-info alert-dismissible fade show py-2">
        <div class="d-flex align-items-center">
            <div class="fs-3 text-info"><i class="bi bi-info-circle-fill"></i>
            </div>
            <div class="ms-3">
            <div class="text-info"><b>Hint:</b> you can Save this form by pressing Ctrl + S, or Trash it by pressing delete button on keyboard !</div>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php
    $newdbstype = $db_name == "agile4training" || $db_name == "agile4training ar" || $db_name == "blackbird-training.co.uk" || $db_name == "mercury arabic" || $db_name == "mercury english";
    $path = dirname(__FILE__);
    include $path.'/conf.php';   
    $row = GetRow($id,'id',$tablename , $theconnection);
    $editornot = $row ? true : false;
    $thepage = 'categories';
    ?><button class="btn btn-secondary m-2 ml-0" onclick="window.location.href='<?php echo $url.$thepage; ?>/view'"><i class="bi bi-arrow-left "></i> Back to Table</button><br><?php
    if($id && !$editornot){
        header('Location: '.$url.$thepage.'/edit');
    }
    if ($id && $row) {
        if($_SESSION['db_name'] == "mercury arabic" ) {
            mysqli_set_charset($theconnection, "utf8");
            $queryc = "SELECT * FROM `page` WHERE `en_name` = '" . mysqli_real_escape_string($theconnection, $row['keyword']) . "' AND `type` = 'programs'";
            $resultss = mysqli_query($theconnection, $queryc);
            $rowss = mysqli_fetch_assoc($resultss);
            
            //print_r($rowss);
            if(isset($rowss['alias'])){
                $urlValue = $rowss['alias'];
                $dashedname = false;
            }else{
                $urlValue = $row['name'];
                $dashedname = true;
            }
        }else{

            $urlValue = $row[$urlPath];
        }
    if ($dashedname) {
        // Remove special characters and replace spaces with dashes
        $urlValue = trim($urlValue);
        $cleanedName = str_replace(' ', '-', $urlValue);
        $cleanedName = preg_replace('/[^A-Za-z0-9\-]/u', '', $cleanedName); // Use 'u' modifier for Unicode
        $cleanedName = preg_replace('/-+/', '-', $cleanedName); // Replace multiple hyphens with a single one
        if (!preg_match('/[A-Za-z0-9]$/', $cleanedName)) {
            $cleanedName .= '-';
        }
        $link = $urlslug . $cleanedName. $pageend;
    } else {
        $link = $urlslug . $urlValue. $pageend;
    }
        $trashed = $row['deleted_at'] != null;
        if($trashed){
            $customClass = 'bg-light-warning';
        }
if ($_SESSION['userlevel'] > 9 ) { ?>
    <button tabindex="-1" class="btn btn-danger m-2 ml-0" title="Delete" onclick="deleteItem('<?php echo $id ?>')"><i class="bi bi-x-circle-fill m-0"></i> </button>
<?php } ?>
    <button tabindex="-1" class="btn btn-warning btn-trash m-2 ml-0" title="<?php echo $trashed ? 'Untrash' : 'Trash'?>" onclick="trashItem('<?php echo $id ?>')"><?php echo $trashed ? '<i class="bi bi-arrow-counterclockwise m-0"> Untrash</i>' : '<i class="bi bi-trash-fill m-0"></i>'?> </button>
    <button tabindex="-1" class="btn btn-info m-2 ml-0" title="Show on web" onclick="window.open('<?php echo $link?>','_blank')"><i class="bx bx-world m-0"></i> </button><?php

        $nextrow = GetNextRow($id,'id',$tablename , $theconnection);
        $prevrow = GetPrevRow($id,'id',$tablename , $theconnection);
        if($nextrow){
            $prevtext = $id > $prevrow['id'] ? 'Prev' : 'To Last';
            $nexttext = $id < $nextrow['id'] ? 'Next' : 'To First';
            $prevcolor = $id > $prevrow['id'] ? 'btn-primary' : 'btn-warning';
            $nextcolor = $id < $nextrow['id'] ? 'btn-primary' : 'btn-warning';
            $buttonnext = '<div class=" d-flex justify-content-between">
                <button id="prev" class="btn '.$prevcolor.'" onclick="window.location.href=\'' . $url . $thepage.'/edit/' . $prevrow['id'] . '\'"><i class="bi bi-arrow-left"></i> '.$prevtext.'</button>
                <button id="next" class="btn '.$nextcolor.' ml-auto" onclick="window.location.href=\'' . $url . $thepage.'/edit/' . $nextrow['id'] . '\'">'.$nexttext.' <i class="bi bi-arrow-right"></i></button>
            </div>';
        }
    }else{
        $nextrow = GetNextRow(1,'id',$tablename , $theconnection);
        $prevrow = GetPrevRow(1,'id',$tablename , $theconnection);
        if($nextrow){
            $prevtext = 'To Last';
            $nexttext = 'To First';
            $prevcolor = 'btn-warning';
            $nextcolor = 'btn-warning';
            $buttonnext = '<div class=" d-flex justify-content-between">
            <button id="prev" class="btn '.$prevcolor.'" onclick="window.location.href=\'' . $url . $thepage.'/edit/' . $prevrow['id'] . '\'"><i class="bi bi-arrow-left"></i> '.$prevtext.'</button>
            <button id="next" class="btn '.$nextcolor.' ml-auto" onclick="window.location.href=\'' . $url . $thepage.'/edit/' . $nextrow['id'] . '\'">'.$nexttext.' <i class="bi bi-arrow-right"></i></button>
        </div>';
        }
    }
    $Columns = GetTableColumns($tablename,$theconnection);

    FormsStart();
    FormsInput('name','Name', 'text', false, 'col-6',true,15,70,'published_at', true, $prompt ='',$system ='') ;
    FormsInput('ar_name','Arabic Name', 'text', false, 'col-6',true,15,70,'published_at', true, $prompt ='',$system ='') ;
    if ($id) {
        FormsInput('s_alias','Slug (Do Not Change if you dont know what you do!)', 'text', true, 'col-6',false,8,70,'') ;  
    }

    FormsInput('title','Title', 'text', false, 'col-6',true,$minTitleChar,$maxTitleChar,'published_at', true, $prompt ='',$system ='',true,false);
    FormsInput('sh','Short Code', 'text', false , 'col-4 ',true,$minShortCode,$maxShortCode,'');
    $options = array(
        "A" => "A",
        "B" => "B",
        "C" => "C",
    );
    FormsSelect('class','Class', $options , true, 'col-4') ;
    $option = array(
        "l" => "Left",
        "r" => "Right",
    );
    FormsSelect('location','Location on page', $option , true, 'col-4') ;
    FormsCheck('home', 'Home', 'checkbox',false,'on', 'col-2' ) ;

    ?><div class="row"><hr><?php

    // Remove any trailing slashes from the URL
$urls = rtrim($categoriesimgurl, '/');

// Split the URL by '/' and get the last part
$parts = explode('/', $urls);
$lastPart = end($parts);
    if($newdbstype){
        FormsImg('glyphicon','Image', 'col-4 row float-start',$categoriesimgurl,$imgcolumn,$tablename,$remoteDirectory,$lastPart,'1.5',400,40);
        ?><hr></div><?php
    }else{?>
       
    <?php }
    $descPrompt ='';
    if ($id){
        if(isset($row['keyword'])){
            
            
            //separate keywords by comma in one line and store it into descPrompt
            
            $keywordArray = json_decode($row['keyword']);
            $row['keyword'] = JsonArrayAsText($row['keyword']); 

            // Assuming $row['keyword'] contains the JSON data

            if (is_array($keywordArray)) {
                $keywords = array_map(function ($item) {
                    return $item->value;
                }, $keywordArray);

                $commaSeparatedKeywords = implode(', ', $keywords);
            } else {
                // Handle the case where $row['keyword'] is not a valid JSON array
                $commaSeparatedKeywords = $keywordArray; // Set a default value or handle it as needed
            }
            $descPrompt = 'For the courses category call '.$row['name'].' Using what the best from this keywords [keyword]'.$commaSeparatedKeywords.'[/keyword] Write 160 character meta description=';
        }
    }
    FormsText('keyword','Keywords', 'keyword','', false, 'col-6', 20, false,0,0, true, $prompt,$system ='') ;
    
    FormsText('description','Description', 'text','published_at', false, 'col-6', 10, false,110,160, true, $descPrompt,$system ='') ;
    FormsEditor('text','Text', 'text', 'true', 'col-12  ') ;
    FormsDateTimeNew('published_at','Publish Date And Time', false, 'col-6') ;
    FormsEnd();

?>
<div class="row">
<?php echo $buttonnext?>
</div>
<script>
    function deleteItem(id){
        if (confirm('Are you sure you want to delete this item?')) {
            $.ajax({
                type: 'GET',
                url: '<?php echo $url.$thepage . '/delete/' ?>' + id,
                contentType: 'application/x-www-form-urlencoded',
                success: function(response) {
                    if (response.success == true ) {
                        window.location.href = '<?php echo $url.$thepage?>/view';
                    } else {
                        error_noti(response.message);
                    }
                },
                error: function() {
                    error_noti("Failed to delete the row.");
                    success = false;
                }
            });
        }
    }

    function trashItem(id){
        if (confirm('Are you sure you want to trash this item?')) {
            $.ajax({
                type: 'GET',
                url: '<?php echo $url.$thepage . '/trash/' ?>' + id,
                contentType: 'application/x-www-form-urlencoded',
                success: function(response) {
                    if (response.success == true ) {
                        success_noti(response.message);
                        // if the response.message starts with un trashed
                        if(response.message.startsWith('Un trashed')){
                            $('.btn-trash').find('i').removeClass('bi-arrow-counterclockwise');
                            $('.btn-trash').find('i').addClass('bi-trash-fill');
                            $('.card').removeClass('bg-light-warning');
                        }else{
                            $('.btn-trash').find('i').addClass('bi-arrow-counterclockwise');
                            $('.btn-trash').find('i').removeClass('bi-trash-fill');
                            $('.card').addClass('bg-light-warning');
                        }
                    } else {
                        error_noti(response.message);
                    }
                },
                error: function() {
                    error_noti("Failed to delete the row.");
                    success = false;
                }
            });
        }
    }
    document.addEventListener('keydown', function(event) {
        // Check if Ctrl key is pressed and the S key is pressed
        if ((event.ctrlKey && event.key === 's') || (event.metaKey && event.key === 's')) {
            // Prevent the default behavior (save page)
            event.preventDefault();

            // Trigger form submission
            document.getElementById('theform').submit();
        }
        if ((event.ctrlKey && event.key === 'ArrowLeft') || (event.metaKey && event.key === 'ArrowRight')) {
            // Prevent the default behavior (save page)
            event.preventDefault();

            // Trigger form submission
            document.getElementById('prev').click();
        }
        // or cmd + arrow right
        if ((event.ctrlKey && event.key === 'ArrowRight') || (event.metaKey && event.key === 'ArrowRight')) {
            // Prevent the default behavior (save page)
            event.preventDefault();

            // Trigger form submission
            document.getElementById('next').click();
        }
        if ((event.key === 'Delete')) {
            // Prevent the default behavior (e.g., navigating back)
            event.preventDefault();
            trashItem('<?php echo $id ?>');
        }
    });
</script>
<?php

        
    
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
            <a href="javascript:history.back()" class="btn btn-primary">Back to Previous Page</a>
    <?php
}
    ?>