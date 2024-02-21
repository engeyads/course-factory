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
    $systemar = '';
    $promptar = '';
    $path = dirname(__FILE__);
    include $path.'/conf.php';   
    $lang = str_ends_with($_SESSION['db_name'],' ar') || str_ends_with($_SESSION['db_name'],' arabic') ? ' return in Arabic' : ' return in English' ;
    $row = GetRow($id,'id',$tablename , $theconnection);
    $editornot = $row ? true : false;
    $thepage = 'courses';
    
    
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
    <button tabindex="-1" class="btn btn-warning btn-trash m-2 ml-0" title="<?php echo $trashed ? 'Untrash' : 'Trash'?>" onclick="trashItem('<?php echo $id ?>')"><?php echo $trashed ? '<i class="bi bi-arrow-counterclockwise m-0"></i>' : '<i class="bi bi-trash-fill m-0"></i>'?> </button>
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
            $buttonnext = '<div class=" d-flex justify-content-between">
            <button id="prev" class="btn btn-warning" onclick="window.location.href=\'' . $url . $thepage.'/edit/' . $prevrow['id'] . '\'"><i class="bi bi-arrow-left"></i> To Last</button>
            <button id="next" class="btn btn-warning ml-auto" onclick="window.location.href=\'' . $url . $thepage.'/edit/' . $nextrow['id'] . '\'">To First <i class="bi bi-arrow-right"></i></button>
        </div>';
        }
    }
    
    if ($id){ $c_id = $row['c_id']; }else { 
        $c_id ='';
        // sql get next id
        $query = "SELECT MAX(c_id) FROM course_main";
        $result = mysqli_query($theconnection, $query);
        $row = mysqli_fetch_row($result);   
        $new_c_id = $row[0] + 1;
    }
    $Columns = GetTableColumns($tablename,$theconnection);

    if(tableExists($theconnection, 'sitekeywords')){

        $query1 = "SELECT *
            FROM sitekeywords ";

// Check if the table sitekeywords_coursemain exists in the database
$tableName = "sitekeywords_coursemain";
if (tableExists($theconnection, $tableName)) {
    $query1 .= "LEFT JOIN $tableName ON sitekeywords.id = $tableName.sitekeywords_id ";
    if($editornot){
        $query1 .= "WHERE $tableName.course_main_id=$id";
    }else{
        $query1 = "";
    }
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
    if($editornot){
        $query1 .= "WHERE $tableName.course_main_id=$id";
    }else{
        $query1 = "";
    }
}
if($query1 != ""){
    $result1 = mysqli_query($theconnection, $query1);
}else{
    $result1 = '';
}

if ($result1 && mysqli_num_rows($result1) > 0) {
    while ($row1 = mysqli_fetch_assoc($result1)) {
        $keys[] = $row1['name'];
    }
    $lines = array_map(function ($key) {
        return $key;
    }, $keys);
}
}
    FormsStart();

    $categories = GetForSelect('course_c' , $conn2, 'id', 'name');
    FormsSelect('course_c','Category', $categories, true , 'col-6');
    echo '<div class="row"></div>';
    if ($id){
        $prompt="rephrase this course name ( ".(isset($row['name']) ? $row['name'] : '')." ) minimum 50 letter and maximum 90 letter ";
        
        //for bb
        if($db_name == 'blackbird-training.co.uk' || $db_name == 'blackbird-training' && $id){
            $prompt = $prompt = "Use the course outline to understand the main themes and content of the course
            [outline]
            ".(isset($row['overview']) ? $row['overview'] : '')."
            [/outline]
            Create a course name that integrates the following keywords in a meaningful and contextually relevant way
            [keyword]
            ".(isset($lines) ? implode(", \n", $lines) : "")."
            [/keyword]";
            $system = "Formulate a course name that:Captures the essence and key themes of the course as reflected in the outline Is engaging, descriptive, and easily identifiable Seamlessly incorporates at least one of the provided keywords";
        }
    }
    FormsInput('name','Name', 'text', false, 'col-6',true,50,90,'published_at',true,$prompt,$system.$lang);

    
    if ($id){
        $prompt="rephrase this course name ( ".(isset($row['name']) ? $row['name'] : '')." ) return only the name minimum 50 letter and maximum 90 letter";
    }

    FormsInput('ar_name','Arabic Name', 'text', false, 'col-6',true,50,90,'published_at',true,$prompt,"return in arabic");
    if ($id){
        $prompt="suggest meta title for this course
        {
            ".(isset($row['name']) ? $row['name'] : '')."
        }
        Use what fit from this keywords with course name
        {
        [keyword]
        ".(isset($lines) ? implode(", \n", $lines) : "")."
        [/keyword]
        }
        ";
        $system = "return Length: Between 45 to 70 characters";
    }
    if($db_name == 'blackbird-training.co.uk' || $db_name == 'blackbird-training' && $id){
        $promptar = $prompt = "Suggest Meta Title for this Course
        {".(isset($row['name']) ? $row['name'] : '')."}
        Incorporate relevant keywords into the title
        [keyword]
        ".(isset($lines) ? implode(", \n", $lines) : "")."
        [/keyword]";
        $system = "Write a meta title that is: Relevant, search-friendly, engaging, diverse, and concise Designed to capture the essence of the course Tailored for a human audience Length: Between 45 to 70 characters";
        $systemar = '"Write a meta title that is: Relevant, search-friendly, engaging, diverse, and concise Designed to capture the essence of the course Tailored for a human audience Length: Between 45 to 70 characters return in arabic"';
    }
    FormsInput('title','Title', 'text', false, 'col-6',true,40,80,'published_at',true,$prompt,$system.$lang);
    if ($id){
        $prompt="suggest meta title for this course (".(isset($row['name']) ? $row['name'] : '').") between 50 to 60 letter and Write for humans and return one title return the title only";
    }
    FormsInput('ar_title','Arabic Title', 'text', false, 'col-6',true,40,80,'published_at',true,$promptar,$systemar);
    
    if(isset($courseimgurl) && $newdbstype){
        FormsImg('image','Image', 'col-4 row float-start',$courseimgurl,$imgcolumn,$tablename,$remoteDirectory,'courses','1.5',400,10);
    }
    if ($id){
        $prompt="suggest a subtitle for this title ( ".(isset($row['title']) ? $row['title'] : '')." ) between 60 to 300 letter";
        if($db_name == 'blackbird-training.co.uk' || $db_name == 'blackbird-training' && $id){
            $prompt = "Create Subtitles for Course Sections Using Keywords
            Course Name: {".(isset($row['name']) ? $row['name'] : '')."}
            Incorporate Keywords into Subtitles:
            Use the following keywords to create engaging and relevant subtitles for different sections of the course
            [keyword]
            ".(isset($lines) ? implode(", \n", $lines) : "")."
            [/keyword]
            Course Outline for Reference
            [outline]
            ".(isset($row['overview']) ? $row['overview'] : '')."
            [/outline]
            Identify sections of the course that can be enhanced with subtitles";
            $system = "Generate subtitles for specific sections or chapters of the course, using the provided keywords Ensure that each subtitle is relevant to the content of the section it represents and incorporates at least one of the keywords Focus on making subtitles engaging and informative Length: Between 60 to 300 characters";
            $systemar = "Generate subtitles for specific sections or chapters of the course, using the provided keywords Ensure that each subtitle is relevant to the content of the section it represents and incorporates at least one of the keywords Focus on making subtitles engaging and informative Length: Between 60 to 300 characters return in arabic";
        }
    }
    FormsText('sub_title','Sub Title', 'text','published_at', false, 'col-6', 3, true,60,300,true, $prompt,$system.$lang);
    if ($id){
        $prompt="suggest a subtitle for this title ( ".(isset($row['ar_title']) ? $row['ar_title'] : '')." ) between 60 to 300 letter";
    }
    FormsText('ar_sub_title','Arabic Sub Title', 'text','published_at', false, 'col-6', 3, true,60,300,true, $prompt,$systemar);
    
    echo '<div class="row"></div>';
    if ($id) {
        FormsInput('s_alias','Slug (Do Not Change if you dont know what you do!)', 'text', false, 'col-6',false,8,70,'',false,$prompt,$system.$lang,$editable) ;  
        FormsInput('alias','Slug (Do Not Change if you dont know what you do!)', 'text', true, 'col-6',false,8,70,'',false,$prompt,$system.$lang,$editable) ;  
    }
    
    if ($id){
        // if (isset($row['keyword'])) {
        //     $row['keyword'] = JsonArrayAsText($row['keyword']);
        // }
    }
    if(!$id){
        $row['c_id'] = $new_c_id;
    }
    FormsInput('c_id','Course ID', 'text', true, 'col-2',false ,$countmin,$countmax,'',false,$prompt,$system.$lang,false);
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
    if ($_SESSION['userlevel'] > 2  ) {
       if(tableExists($theconnection, 'sitekeywords')){ 
    ?>
    <br>
    <div class="col-6 mb-3">
        <label class="form-label">Site Keywords</label> <a href="#" class="" data-bs-toggle="modal" data-bs-target="#myModalsitekeywords" id="openModalLinksitekeywords">AI</a>
        <textarea class="form-control mb-3" rows="20" placeholder="sitekeywords" aria-label="sitekeywords"
            name="sitekeywords" id="sitekeywords" ><?php echo isset($lines) ? implode("\n", $lines) : ''; ?></textarea>
            <!-- Modal HTML -->
            <div class="modal fade" id="myModalsitekeywords" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Site Keywords AI</h5>
                        </div>
                        <div class="modal-body" id="modalBodyContentsitekeywords">
                            <!-- Content will be filled via AJAX -->
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"  id="changeInputsitekeywords" data-bs-dismiss="modal">Change Input</button>  <!-- New button -->
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                        <small class="text-right d-flex justify-content-end p-2 pt-0"><?php echo $gptmode ?></small>
                    </div>
                </div>
            </div>
            <?PHP // ai script
            global $type;
            
        echo '    
        <script>
      
      $(document).ready(function() {
          if(1) {
              $("#openModalLinksitekeywords").click(function(e){
                  e.preventDefault();
                  var txtval = $("#sitekeywords").val();
                  $.ajax({
                      url: "'. $httpurl .'tables/aimodal.php",
                      type: "POST",
                      data: {
                        httpurl: "'. $httpurl .'",
                        prompt: `'.str_replace('<br />', '', nl2br('Suggest keywords for this course name: ' . (isset($row['name']) ? $row['name']:''))).'`,
                        system: "return only the text and without list or numbering or commas, just return in lines'. $lang .'",
                        type: "'. $type .'",
                        txtval: "",
                      },
                      success: function(result){
                          if (result.includes("Error:")) {
                              $("#modalBodyContentsitekeywords").html(result);
                          } else {
                              
                          }
                      },
                      error: function(error) {
                        console.log(error);
                          $("#modalBodyContentsitekeywords").html("Error: Unable to load content.");
                      }
                  });
              });
              
              $(document).on("click", "#changeInputsitekeywords", function(){
                  var textareaContent = $("#ai").val();
                  $("#sitekeywords").val(textareaContent).trigger("input");
                        var confirmation = confirm("Are you sure you want to change? ");
                        if (confirmation) {

                        // Convert Markdown to HTML using marked.js
                        var htmlContent = marked.parse(textareaContent);

                            updateTinyMCE(htmlContent, "sitekeywords");
                            $("#theform").submit();
                        }
                      
                  
              });
               
          }
      });
      </script>';
      ?>
    </div>
                <?php
        }
        }
/////////////////////////////////////// end sitekeywords
    // FormsText('ar_keywords','Arabic Keywords', 'ar_keywords','', false, 'col-6', 20, false,0,0) ;
    // FormsText('keyword','Keywords', 'keyword','', false, 'col-6', 20, false,0,0) ;
    // FormsText('keywords','English Keywords', 'keyword','', false, 'col-6', 20, false,0,0) ;
    // FormsText('sitekeywords','Website Keywords', 'sitekeywords','', false, 'col-6', 20, false,0,0) ;
    
    $promtdescription = "suggest meta description for this course (".(isset($row['name']) ? $row['name'] : '').")  between 110 to 165 letter and Write for humans and return one description";
    $promtdescriptionar = "suggest meta description for this course (".(isset($row['ar_name']) ? $row['ar_name'] : '').")  between 110 to 165 letter and Write for humans and return one description";
    $systemdescription = "Write between 110 to 165 character and return one description Don't duplicate same words";
    $systemdescriptionar = "Write between 110 to 165 character and return one description Don't duplicate same words return in arabic";
    
    if($db_name == 'mercury english' || $db_name == 'mercury arabic' && $id){
        $promtdescriptionar = $promtdescription = "Suggest meta description for this course
        {".(isset($row['name']) ? $row['name'] : '')."}
        Get what fit from course outline
        [outline]
        ".(isset($row['overview']) ? $row['overview'] : '')."
        [/outline]
        Get what fit from these keywords
        [keyword]
        ".(isset($lines) ? implode(", \n", $lines) : "")."
        [/keyword]";
        $systemdescription = "Write between 135 to 165 character and return one description Don't duplicate same words";
    }
    if($db_name == 'blackbird-training.co.uk' || $db_name == 'blackbird-training' && $id){
        $promtdescriptionar = $promtdescription = "Suggest meta description for this course
        {".(isset($row['name']) ? $row['name'] : '')."}
        Extract relevant points from the course outline
        [outline]
        ".(isset($row['overview']) ? $row['overview'] : '')."
        [/outline]
        Use appropriate keywords in the description
        [keyword]
        ".(isset($lines) ? implode(", \n", $lines) : "")."
        [/keyword]";
        $systemdescription = "Write a meta description that is: search-friendly and concise Tailored for a human audience Avoid duplication of words Length: Between 135 to 165 characters.";
    }
    FormsText('description','description', 'text','published_at', false, 'col-6', 10, true,110,170,true, $promtdescription,$systemdescription.$lang);
    FormsText('ar_description','Arabic description', 'text','published_at', false, 'col-6', 10, true,110,160,true, $promtdescriptionar,$systemdescriptionar);
    
    
    
    $promtoverview ='';
    if($db_name == 'blackbird-training.co.uk' || $db_name == 'blackbird-training' || $db_name == 'mercury english' || $db_name == 'mercury arabic' && $id){
        $promtoverview = "
        For the following course :
        {".(isset($row['name']) ? $row['name'] : '')."}
        Add these keywords if it fits
        And enhance the outlines of the entire course with these keywords if it fits
        Create a new subheading using the keywords with a short paragraph if it fits
        [keyword]
        ".(isset($lines) ? implode(", \n", $lines) : "")." 
        [/keyword]
        [Outline]
        ".(isset($row['overview']) ? $row['overview'] : '')."
        [/Outline]";
    }
    FormsEditor('overview','Text', 'text', 'true', 'col-12',true, $promtoverview,"Rewrite in markdown format and return only the text$lang Change the course by adding long sentences and keywords, and do not shorten any sentences from the course");
    FormsEditor('ar_overview','Arabic Text', 'text', 'true', 'col-12',true, $promtoverview,'Rewrite in markdown format and return only the text Return in Arabic Do not change the course except by adding long sentences and keywords, and do not shorten any sentences from the course');
    
    FormsEditor('broshoure','Broshoure', 'text', 'true', 'col-12  ') ;    
    FormsEditor('ar_broshoure','Arabic Broshoure', 'text', 'true', 'col-12  ') ;    
    
    FormsDateTimeNew('published_at','Publish Date And Time', false, 'col-6') ;
    
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
    $ignoredColumns = ['price','currency','certain','city','published_at','created_at','updated_at','smart','c_id','d1', 'm1', 'y1', 'd2', 'm2', 'y2','deleted_at'];  
    $tooltips = ['start_date','end_date'  ];
    $popups = [];
    $jsonarrays = [];
    $imagePaths = [];
    $urlPaths = [];
    $editPath = 'city';
    $urlPath = 'id';
    // $no_link = true;
    // $no_edits = true;
    $fieldTitles = [ 'start_date' => 'Start Date', 'end_date' => 'End Date', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
    $dateColumns = ['created_at', 'updated_at','published_at']; // replace with your actual date columns
    $cities = GetForSelect('cities' , $conn2, 'id', 'name');
    //$course = GetForSelect('course_main' , $conn2, 'c_id', 'name');
    $dataArrays = [
       'city' => $cities
        // Add more column mappings here
    ];
    $ajaxDataArrays = array(
        // array(
        //     'column' => 'city',
        //     'table' => 'cities',
        //     'param1' => 'id',
        //     'param2' => 'name',
        // ),
        // array(
        //     'column' => 'c_id',
        //     'table' => 'course_main',
        //     'param1' => 'c_id',
        //     'param2' => 'name',
        // ),
    );
    $custom_from = " course LEFT JOIN cities ON course.city=cities.id ";
    $custom_select = " course.id,cities.name as City, CASE WHEN course.certain = 'on' THEN '✅' ELSE '❌' END AS Upcomming ,CASE WHEN cities.monday = 1 THEN '✅' ELSE '❌' END AS Monday,course.price as Price ,course.currency as Currency ,date(CONCAT(y1, '-', LPAD(m1, 2, '0'), '-', LPAD(d1, 2, '0'))) AS start_date, date(CONCAT(y2, '-', LPAD(m2, 2, '0'), '-', LPAD(d2, 2, '0'))) AS end_date ,course.published_at as Published_at,course.deleted_at";
    $custom_where = " course.c_id = $c_id ";

    $searchsColumns = [ 'id' => 'course`.`id', 'City' => 'cities`.`name' , 'Price' => 'course`.`price', 'Currency' => 'course`.`currency', 'currency' => 'course`.`currency','Published_at' => 'course`.`published_at','Upcomming'=>'course`.`certain','Monday' => 'cities`.`monday'];

    $costumeQuery = "SELECT * ,
        date(CONCAT(y1, '-', LPAD(m1, 2, '0'), '-', LPAD(d1, 2, '0'))) AS start_date,
        date(CONCAT(y2, '-', LPAD(m2, 2, '0'), '-', LPAD(d2, 2, '0'))) AS end_date,
        course.published_at as Published_at
        FROM course  WHERE c_id = $c_id AND 1";
    $ignoredColumnsDB = ['hotel_photo','hotel_link','address','visible' ];  // Replace these with your actual column names to ignore
    $additionalColumns = ['City','Upcomming','Monday','Price','Currency','start_date', 'end_date','Published_at'];  // Replace these with your actual additional column names

?><a class="btn btn-primary float-right justify-content-end" href="<?php echo $url; ?>event/addmultiplebycourse/<?php echo $c_id; ?>" target="_blank" rel="noopener noreferrer">Add Multi Events</a><?php
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