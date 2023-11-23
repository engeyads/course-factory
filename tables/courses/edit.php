<?php
if ($_SESSION['userlevel'] > 2 ) {
    
    $path = dirname(__FILE__);
    include $path.'/conf.php';   
 
    $row = GetRow($id,'id',$tablename , $theconnection);
    if ($id){ $c_id = $row['c_id']; }else { 
        $c_id ='';
        // sql get next id
        $query = "SELECT MAX(c_id) FROM course_main";
        $result = mysqli_query($theconnection, $query);
        $row = mysqli_fetch_row($result);   
        $new_c_id = $row[0] + 1;
    }
    $Columns = GetTableColumns($tablename,$theconnection);

    FormsStart();

    $categories = GetForSelect('course_c' , $conn2, 'id', 'name');
    FormsSelect('course_c','Category', $categories, true , 'col-6');
    echo '<div class="row"></div>';
    if ($id){
    $prompt="rephrase this course name ( ".(isset($row['name']) ? $row['name'] : '')." ) minimum 50 letter and maximum 90 letter ";
    }
    FormsInput('name','Name', 'text', false, 'col-6',true,50,90,'published_at',true,$prompt,'return in english');

    
    if ($id){
        $prompt="rephrase this course name ( ".(isset($row['name']) ? $row['name'] : '')." ) return only the name minimum 50 letter and maximum 90 letter";
    }

    FormsInput('ar_name','Arabic Name', 'text', false, 'col-6',true,50,90,'published_at',true,$prompt,"return in arabic");
    if ($id){
        $prompt="suggest meta title for this course (".(isset($row['name']) ? $row['name'] : '').") between 50 to 60 letter and Write for humans and return one title return the title only";
    }
    FormsInput('title','Title', 'text', false, 'col-6',true,40,80,'published_at',true,$prompt,$system);
    if ($id){
        $prompt="suggest meta title for this course (".(isset($row['name']) ? $row['name'] : '').") between 50 to 60 letter and Write for humans and return one title return the title only";
    }
    FormsInput('ar_title','Arabic Title', 'text', false, 'col-6',true,40,80,'published_at',true,$prompt,"return in arabic");
    
    if(isset($courseimgurl) && $newdbstype){
        FormsImg('image','Image', 'col-4 row float-start',$courseimgurl,'s_alias',$tablename,$remoteDirectory,'courses','1.5',400,10);
    }
    if ($id){
        $prompt="suggest a subtitle for this title ( ".(isset($row['title']) ? $row['title'] : '')." ) between 60 to 300 letter";
    }
    FormsText('sub_title','Sub Title', 'text','published_at', false, 'col-6', 3, true,60,300,true, $prompt,$system);
    if ($id){
        $prompt="suggest a subtitle for this title ( ".(isset($row['ar_title']) ? $row['ar_title'] : '')." ) between 60 to 300 letter";
    }
    FormsText('ar_sub_title','Arabic Sub Title', 'text','published_at', false, 'col-6', 3, true,60,300,true, $prompt,"return in arabic");
    
    echo '<div class="row"></div>';
    if ($id) {
        FormsInput('s_alias','Slug (Do Not Change if you dont know what you do!)', 'text', true, 'col-6',false,8,70,'',false,$prompt,$system,$editable) ;  
        FormsInput('alias','Slug (Do Not Change if you dont know what you do!)', 'text', true, 'col-6',false,8,70,'',false,$prompt,$system,$editable) ;  
    }
    
    if ($id){
        // if (isset($row['keyword'])) {
        //     $row['keyword'] = JsonArrayAsText($row['keyword']);
        // }
    }
    if(!$id){
        $row['c_id'] = $new_c_id;
    }
    FormsInput('c_id','Course ID', 'text', true, 'col-2',false ,$countmin,$countmax,'',false,$prompt,$system,false);
    //if(!$id){
        $row['c_id'] = '';
    //}
    $options = array(
        "1" => "1 Week",
        "2" => "2 Weeks",
        "3" => "3 Weeks",
        "4" => "4 Weeks",
    );

    FormsSelect($DBweekname,'Duration', $options , true, 'col-2') ;
    
    echo '<div class="row"></div>';

/////////////////////////////////////// start sitekeywords
    if ($_SESSION['userlevel'] > 2  && $id) {
        if(tableExists($theconnection, 'sitekeywords')){

            $query1 = "SELECT *
                FROM sitekeywords ";
    
    // Check if the table sitekeywords_coursemain exists in the database
    $tableName = "sitekeywords_coursemain";
    if (tableExists($theconnection, $tableName)) {
        $query1 .= "LEFT JOIN $tableName ON sitekeywords.id = $tableName.sitekeywords_id ";
        $query1 .= "WHERE $tableName.course_main_id=$id";
    }else{
        $query0 = "CREATE TABLE IF NOT EXISTS sitekeywords_coursemain (
                 id SERIAL PRIMARY KEY,
                 course_main_id INT ,
                 sitekeywords_id INT ,
                 FOREIGN KEY (course_main_id) REFERENCES course_main(id) ON DELETE CASCADE ,
                 FOREIGN KEY (sitekeywords_id) REFERENCES sitekeywords(id) ON DELETE CASCADE,
                 CONSTRAINT unique_coursekeywords UNIQUE (course_main_id, sitekeywords_id)
             )";
        mysqli_query($theconnection, $query0);
        $query1 .= "LEFT JOIN $tableName ON sitekeywords.id = $tableName.sitekeywords_id ";
        $query1 .= "WHERE $tableName.course_main_id=$id";
    }
    
    $result1 = mysqli_query($theconnection, $query1);
    
    if ($result1 && mysqli_num_rows($result1) > 0) {
        while ($row1 = mysqli_fetch_assoc($result1)) {
            $keys[] = $row1['name'];
        }
        $lines = array_map(function ($key) {
            return $key;
        }, $keys);
    }
    ?>
    <br>
    <div class="col-6 mb-3">
        <label class="form-label">Site Keywords</label>
        <textarea class="form-control mb-3" rows="20" placeholder="sitekeywords" aria-label="sitekeywords"
            name="sitekeywords" id="sitekeywords" ><?php echo isset($lines) ? implode("\n", $lines) : ''; ?></textarea>
    </div>
                <?php
        }
        }
/////////////////////////////////////// end sitekeywords
    // FormsText('ar_keywords','Arabic Keywords', 'ar_keywords','', false, 'col-6', 20, false,0,0) ;
    // FormsText('keyword','Keywords', 'keyword','', false, 'col-6', 20, false,0,0) ;
    // FormsText('keywords','English Keywords', 'keyword','', false, 'col-6', 20, false,0,0) ;
    //FormsText('sitekeywords','Website Keywords', 'sitekeywords','', false, 'col-6', 20, false,0,0) ;
    $prompt = "suggest meta description for this course (".(isset($row['name']) ? $row['name'] : '').")  between 110 to 165 letter and Write for humans and return one description";
    FormsText('description','description', 'text','published_at', false, 'col-6', 10, true,110,170,true, $prompt,'');
    $prompt = "suggest meta description for this course (".(isset($row['ar_name']) ? $row['ar_name'] : '').")  between 110 to 165 letter and Write for humans and return one description";
    FormsText('ar_description','Arabic description', 'text','published_at', false, 'col-6', 10, true,110,160,true, $prompt,"return in arabic");
    $promtoverview ='';
    if($db_name == 'mercury english' || $db_name == 'mercury arabic' && $id){
        $promtoverview = "For the following course : 
            {".(isset($row['name']) ? $row['name'] : '')."}
            add this keyword if its fit 
            and enhance the hole outlines

            [keyword]
            ".(isset($lines) ? implode("\n", $lines) : "")."    
            [/keyword]

            [outline]
            ".(isset($row['overview']) ? $row['overview'] : '')."
            [/outline]";

    }
    FormsEditor('overview','Text', 'text', 'true', 'col-12',true, $promtoverview,'Rewrite in markdown format and return only the text Do not change the course except by adding long sentences and keywords, and do not shorten any sentences from the course');
    FormsEditor('ar_overview','Arabic Text', 'text', 'true', 'col-12',true, $promtoverview,'Rewrite in markdown format and return only the text Return in Arabic Do not change the course except by adding long sentences and keywords, and do not shorten any sentences from the course');
    // FormsEditor('overview','Text', 'text', 'true', 'col-12  ') ;
    // FormsEditor('ar_overview','Arabic Text', 'text', 'true', 'col-12  ') ;
    
    FormsEditor('broshoure','Broshoure', 'text', 'true', 'col-12  ') ;    
    FormsEditor('ar_broshoure','Arabic Broshoure', 'text', 'true', 'col-12  ') ;    
    
    FormsDateTime('published_at','Publish Date And Time', false, 'col-6') ;
    
    FormsEnd(); 
                
///////////////////////////////////////  view  events
if ($_SESSION['userlevel'] > 2  && $c_id) {
    $folderName = 'event';
    $tablename = 'course';
    $tabletitle = 'Events';
    $urlslug = $websiteurl .$eventslug;
    $maxlenginfield = 20;
    $theconnection = $conn2;
    $thedbname = $db2_name;
    $editslug = 'edit/'.$c_id;
    $ajaxview= true;
    $trashslug = 'trash';
    $deleteslug = 'delete';
    $viewslug = 'view';
    $ignoredColumns = ['c_id','d1', 'm1', 'y1', 'd2', 'm2', 'y2','deleted_at'];  
    $tooltips = ['start_date','end_date'  ];
    $popups = [];
    $jsonarrays = [];
    $imagePaths = [];
    $urlPaths = [];
    $fieldTitles = ['certain' => 'Upcoming', 'start_date' => 'Start Date', 'end_date' => 'End Date', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
    $dateColumns = ['created_at', 'updated_at','published_at']; // replace with your actual date columns
    $cities = GetForSelect('cities' , $conn2, 'id', 'name');
    //$course = GetForSelect('course_main' , $conn2, 'c_id', 'name');
    $dataArrays = [
       'city' => $cities
        // Add more column mappings here
    ];
    $ajaxDataArrays = array(
        array(
            'column' => 'city',
            'table' => 'cities',
            'param1' => 'id',
            'param2' => 'name',
        ),
        // array(
        //     'column' => 'c_id',
        //     'table' => 'course_main',
        //     'param1' => 'c_id',
        //     'param2' => 'name',
        // ),
    );
    $custom_from = "course";
    $custom_select = "* ,TIMESTAMP(CONCAT(y1, '-', LPAD(m1, 2, '0'), '-', LPAD(d1, 2, '0'))) AS start_date, TIMESTAMP(CONCAT(y2, '-', LPAD(m2, 2, '0'), '-', LPAD(d2, 2, '0'))) AS end_date";
    $custom_where = "c_id = $c_id";

    $costumeQuery = "SELECT * ,
        TIMESTAMP(CONCAT(y1, '-', LPAD(m1, 2, '0'), '-', LPAD(d1, 2, '0'))) AS start_date,
        TIMESTAMP(CONCAT(y2, '-', LPAD(m2, 2, '0'), '-', LPAD(d2, 2, '0'))) AS end_date
        FROM course  WHERE c_id = $c_id AND 1";
    $ignoredColumnsDB = ['hotel_photo','hotel_link','address','visible' ];  // Replace these with your actual column names to ignore
    $additionalColumns = ['start_date', 'end_date'];  // Replace these with your actual additional column names

?><a class="btn btn-primary float-right justify-content-end" href="<?php echo $url; ?>event/addmultiple/<?php echo $c_id; ?>" target="_blank" rel="noopener noreferrer">Add Multi Events</a><?php
    include 'include/view.php';
    //include 'include/logview.php';
}else{
    ?>
    <div class="alert border-0 bg-light-warning alert-dismissible fade show py-2">
        <div class="d-flex align-items-center">
            <div class="fs-3 text-warning"><i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div class="ms-3">
            <div class="text-warning">Add The Course To Add Events</div>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php
}
///////////////////////////////////////   end events

if ($id){
    $query = "SELECT * FROM `course_main` WHERE  `course_c` = $id ";
    $courses = mysqli_query($theconnection, $query);
    $coursecount = mysqli_num_rows($courses);
    ?>

    <div class="row">
        <div class="">
            <h1>AI PROMPT FOR CATEGORY</h1>
            <hr/>
            <!-- timeline item 2 -->
            <div class="row"> 
                <div class="col py-2">
                    <div class="card border-primary shadow radius-15"> 
                        <div class="col-6 mb-3"> </div> 
                        <div class="card-body"> 								 
                            <?php if ($coursecount > 1) { ?>
                                            
                            <label class="form-label">Apply this suggestion when you have courses in this category and you need to enhance the category page.</label>

                            <textarea class="form-control mb-3" rows="10">
    We have a dedicated website for corporate training that offers various category pages. One of these categories is  "<?php echo (isset($row['name']) ? $row['name'] : ''); ?>"  which includes  (<?php echo $coursecount; ?>)  informative courses:
    <?php foreach ($courses as $course) { echo "\n".$course['name']; } ?>


    Your task is to enhance the category page by 

    crafting an engaging description between 140-165 characters give me 4 options , 
    and developing compelling text content to added after the courses  speak about the category not the courses the text should be arrount 400 words.
                            </textarea>
                            <label class="form-label">Image Prompt Creator</label>
                            <textarea class="form-control mb-3" rows="10">craft ai prompt for image about  courses category page  the category title is "<?php echo (isset($row['name']) ? $row['name'] : ''); ?>" the style off the image ultra realistic for background on the end off prompt add latterly solid blue background add also --ar 3:2 </textarea>
                            <?php }else { ?>
                                <div class="alert border-0 bg-light-warning alert-dismissible fade show py-2">
                                    <div class="d-flex align-items-center">
                                        <div class="fs-3 text-warning"><i class="bi bi-exclamation-triangle-fill"></i>
                                        </div>
                                        <div class="ms-3">
                                        <div class="text-warning">Add More than 3 Courses in this category To Check the ai future</div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php } ?>                         
                                    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php }else { 
        ?>
        <div class="alert border-0 bg-light-warning alert-dismissible fade show py-2">
            <div class="d-flex align-items-center">
                <div class="fs-3 text-warning"><i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div class="ms-3">
                <div class="text-warning">Add The Course To Check the ai future</div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php
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