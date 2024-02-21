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
    $path = dirname(__FILE__);
    include $path.'/conf.php';
    $newdbstype = $db_name == "agile4training" || $db_name == "agile4training ar" || $db_name == "blackbird-training.co.uk" || $db_name == "mercury arabic" || $db_name == "mercury english";
    $lang = str_ends_with($_SESSION['db_name'],' ar') || str_ends_with($_SESSION['db_name'],' arabic') ? ' return in Arabic' : ' return in English' ;

    $row = GetRow($id,'id',$tablename , $theconnection);
    $editornot = $row ? true : false;
    $thepage = 'cities';
    ?><button class="btn btn-secondary m-2 ml-0" onclick="window.location.href='<?php echo $url.$thepage; ?>/view'"><i class="bi bi-arrow-left"></i> Back to View</button><br><?php
    if($id && !$editornot){
        header('Location: '.$url.$thepage.'/edit');
    }
    if ($id && $row) {
        $urlValue = $row[$urlPath];
    if ($dashedname) {
        // Remove special characters and replace spaces with dashes
        $cleanedName = preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $urlValue));
        $cleanedName = preg_replace('/-+/', '-', $cleanedName);
        if (preg_match('/[^A-Za-z0-9]$/', $urlValue)) {
            $cleanedName .= '-';
        }
        $link = $urlslug . $cleanedName;
    } else {
        $link = $urlslug . $urlValue;
    }
        $trashed = $row['deleted_at'] != null;
        if($trashed){
            $customClass = 'bg-light-warning';
        }
if ($_SESSION['userlevel'] > 9 ) { ?>
    <button tabindex="-1" class="btn btn-danger m-2 ml-0" title="Delete" onclick="deleteItem('<?php echo $id ?>')"><i class="bi bi-x-circle-fill m-0"></i> </button>
<?php } ?>
    <button tabindex="-1" class="btn btn-warning btn-trash m-2 ml-0" title="<?php echo $trashed ? 'Untrash' : 'Trash'?>" onclick="trashItem('<?php echo $id ?>')"><?php echo $trashed ? '<i class="bi bi-arrow-counterclockwise m-0"></i>' : '<i class="bi bi-trash-fill m-0"></i>'?> </button>
    <button tabindex="-1" class="btn btn-info m-2 ml-0" title="Show on web" onclick="window.open('<?php echo $link?>','_blank')"><i class="bx bx-world m-0"> </i> </button><?php

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
            $buttonnext = '<div class=" d-flex justify-content-between">
            <button id="prev" class="btn btn-warning" onclick="window.location.href=\'' . $url . $thepage.'/edit/' . $prevrow['id'] . '\'"><i class="bi bi-arrow-left"></i> To Last</button>
            <button id="next" class="btn btn-warning ml-auto" onclick="window.location.href=\'' . $url . $thepage.'/edit/' . $nextrow['id'] . '\'">To First <i class="bi bi-arrow-right"></i></button>
        </div>';
        }
    }
    $Columns = GetTableColumns($tablename,$theconnection);
    
    FormsStart();
    FormsInput('name','Name', 'text', true, 'col-6',true,2,70,'published_at' );
    FormsInput('ar_name','Arabic Name', 'text', true, 'col-6',false,2,70,'published_at' );
    
    FormsInput('countryname','Country Name', 'text', true, 'col-6',false,40,70,'published_at' );
    if ($id) {
        FormsInput('s_alias','Slug (Do Not Change if you dont know what you do!)', 'text', true, 'col-6',false,8,70,'' );  
    }

    FormsInput('title','Title', 'text', false, 'col-6',true,25,60,'published_at' );
    FormsInput('code','Code', 'text', false , 'col-1 ',true,1,3,'');
    FormsInput('codetwo','Two litter Code', 'text', false , 'col-1 ',true,1,2,'');
    FormsInput('hotel_name','Hotel Name', 'text', false, 'col-6',false,40,70,'published_at' );
    FormsInput('hotel_link','Hotel Link', 'text', false, 'col-6',false,40,70,'published_at' );
    FormsInput('hotel_photo','Hotel Photo', 'text', false, 'col-6',false,40,70,'published_at' );

    $options = array(
        "01_Europe & USA" => "Europe and America", 
        "03_Asia" => "Asia",
        "02_Middle East & africa" => "Middle East and Africa",
    );
    FormsSelect('continental','Continental', $options , true, 'col-4');
    $options = array(
        "01_أوربا وأمريكا" => "أوربا وأمريكا", 
        "03_اسيا" => "اسيا",
        "02_الشرق الاوسط و افريقيا" => "الشرق الاوسط و افريقيا",
    );
    FormsSelect('ar_continental','AR Continental', $options , true, 'col-4');

    $options = array(
        "A" => "A", 
        "B" => "B",
        "C" => "C",
    );
    FormsSelect('class','Class', $options , true, 'col-4');
    FormsCheck('monday', 'Monday', 'checkbox',false,'1', 'col-1' );
    ?><div class="row"><hr><?php
if($newdbstype){
    FormsImg('city_photo','City Image', 'col-4 row float-start',$citiesimgurl,'s_alias',$tablename,$remoteDirectory,'cities','1.35',100,20);
    FormsImg('slider_photo','Slider Image', 'col-4 row float-start',$citiessliderimgurl,'s_alias',$tablename,$remoteDirectory,'bg','2.45',1500,70);
    ?><hr></div><?php
}
    if ($id){
        if(isset($row['keyword'])){

            $row['keyword'] = JsonArrayAsText($row['keyword']);
        }
    }
    FormsText('keyword','Keywords', 'keyword','', 'true', 'col-6', 20, false,0,0);
    FormsText('description','description', 'text','published_at', false, 'col-6', 10, true,110,160,true,'create descripotion for { '.($row['name'] ?? '') .' } city for courses that we offer in that city',"return only between 110 - 160 characters maximum $lang");
    FormsEditor('about','About', 'text', 'true', 'col-12  ');

    ?><div class="row"><hr><?php
    FormsInput('x','Ratio for Class A', 'text', false, 'col-2',false,0,5,'' );
    FormsInput('w1_p','Price for 1 week \'Class A\'', 'text', false, 'col-2',false,0,5,'' );
    FormsInput('w2_p','Price for 2 week \'Class A\'', 'text', false, 'col-2',false,0,5,'' );
    FormsInput('w3_p','Price for 3 week \'Class A\'', 'text', false, 'col-2',false,0,5,'' );
    FormsInput('w4_p','Price for 4 week \'Class A\'', 'text', false, 'col-2',false,0,5,'' );
    ?><div class="row"><hr><?php
    FormsInput('x_b','Ratio for Class B', 'text', false, 'col-2',false,0,5,'' );
    FormsInput('w1_p_b','Price for 1 week \'Class B\'', 'text', false, 'col-2',false,0,5,'' );
    FormsInput('w2_p_b','Price for 2 week \'Class B\'', 'text', false, 'col-2',false,0,5,'' );
    FormsInput('w3_p_b','Price for 3 week \'Class B\'', 'text', false, 'col-2',false,0,5,'' );
    FormsInput('w4_p_b','Price for 4 week \'Class B\'', 'text', false, 'col-2',false,0,5,'' );
    ?><div class="row"><hr><?php
    FormsInput('x_c','Ratio for Class c', 'text', false, 'col-2',false,0,5,'' );
    FormsInput('w1_p_c','Price for 1 week \'Class C\'', 'text', false, 'col-2',false,0,5,'' );
    FormsInput('w2_p_c','Price for 2 week \'Class C\'', 'text', false, 'col-2',false,0,5,'' );
    FormsInput('w3_p_c','Price for 3 week \'Class C\'', 'text', false, 'col-2',false,0,5,'' );
    FormsInput('w4_p_c','Price for 4 week \'Class C\'', 'text', false, 'col-2',false,0,5,'' );
    ?><hr></div><?php
    ?><br><br><?php
    FormsDateTimeNew('published_at','Publish Date And Time', false, 'col-6');
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