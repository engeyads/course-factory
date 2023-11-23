<?php
if ($_SESSION['userlevel'] > 2 ) { 
    $row = GetRow($id,'id',$tablename , $theconnection);
    if ($id){ $c_id = $row['c_id']; }else { $c_id = ''; }
    $Columns = GetTableColumns($tablename,$theconnection);  
    $subtag = GetForSelect('subtags' , $conn, 'id', 'subtag'); 
    FormsSelect('subtag_id','Sub Tag', $subtag, true , 'col-6') ;
    echo '<div class="row"></div>';
    FormsInput('name','Name', 'text', false, 'col-6') ;
    FormsInput('title','Title', 'text', false, 'col-6',false,20,80,'published_at');
    FormsText('sub_title','Sub Title', 'text','published_at', false, 'col-6', 3) ;

    echo '<div class="row"></div>';
    if ($id) {
        FormsInput('s_alias','Slug (Do Not Change if you dont know what you do!)', 'text', false, 'col-6') ;  
    }
    
    if ($id){
        if (JsonArrayAsText($row['keyword']) != NULL){
            $row['keyword'] = JsonArrayAsText($row['keyword']);
        }
        
        FormsInput('c_id','Course ID', 'text', false, 'col-2');

    }
    $options = array(
        "1" => "1 Week",
        "2" => "2 Weeks",
        "3" => "3 Weeks",
        "4" => "4 Weeks",
    );
    FormsSelect('week','Duration', $options , false, 'col-2') ;
    echo '<div class="row"></div>';
 

 

 
    FormsText('keyword','Keywords', 'keyword','', 'true', 'col-6', 20, false,0,0) ;
    FormsText('description','description', 'text','published_at', false, 'col-6') ;
    FormsEditor('overview','Text', 'text', 'true', 'col-12  ') ;
    FormsEditor('broshoure','Broshoure', 'text', 'true', 'col-12  ') ;    
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
    
    $trashslug = 'trash';
    $deleteslug = 'delete';
    $viewslug = 'view';
    $ignoredColumns = ['d1', 'm1', 'y1', 'd2', 'm2', 'y2','deleted_at'];  
    $tooltips = ['start_date','end_date'  ];
    $popups = [ ];
    $jsonarrays = [ ];
    $imagePaths = [ ];
    $urlPaths = ['c_id' => 'id'];
    $fieldTitles = ['c_id' => 'Course Nmae' ,'certain' => 'Upcoming', 'start_date' => 'Start Date', 'end_date' => 'End Date', 'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
    $dateColumns = ['created_at', 'updated_at','published_at','start_date','end_date']; // replace with your actual date columns
     $cities = GetForSelect('cities' , $conn2, 'id', 'name');
     $course = GetForSelect('course_main' , $conn2, 'c_id', 'name');
    $dataArrays = [
       'city' => $cities,
       'c_id' => $course
        // Add more column mappings here
    ];

    $custom_from = "course";
    $custom_select = "* ,TIMESTAMP(CONCAT(y1, '-', LPAD(m1, 2, '0'), '-', LPAD(d1, 2, '0'))) AS start_date, TIMESTAMP(CONCAT(y2, '-', LPAD(m2, 2, '0'), '-', LPAD(d2, 2, '0'))) AS end_date";
    $custom_where = "c_id = $c_id";

      $costumeQuery = "SELECT * ,
    TIMESTAMP(CONCAT(y1, '-', LPAD(m1, 2, '0'), '-', LPAD(d1, 2, '0'))) AS start_date,
    TIMESTAMP(CONCAT(y2, '-', LPAD(m2, 2, '0'), '-', LPAD(d2, 2, '0'))) AS end_date
FROM course  WHERE c_id = $c_id";
    $ignoredColumns = ['d1', 'm1', 'y1', 'd2', 'm2', 'y2','deleted_at'];   
$ignoredColumnsDB = ['hotel_photo','hotel_link','address','visible' ];  // Replace these with your actual column names to ignore
$additionalColumns = ['start_date', 'end_date'];  // Replace these with your actual additional column names
    include 'include/view.php';
    include 'include/logview.php';
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
    We have a dedicated website for corporate training that offers various category pages. One of these categories is  "<?php echo $row['name']; ?>"  which includes  (<?php echo $coursecount; ?>)  informative courses:
    <?php foreach ($courses as $course) { echo "\n".$course['name']; } ?>


    Your task is to enhance the category page by 

    crafting an engaging description between 140-165 characters give me 4 options , 
    and developing compelling text content to added after the courses  speak about the category not the courses the text should be arrount 400 words.
                            </textarea>        
                            <label class="form-label">Image Prompt Creator</label>
                            <textarea class="form-control mb-3" rows="10">craft ai prompt for image about  courses category page  the category title is "<?php echo $row['name']; ?>" the style off the image ultra realistic for background on the end off prompt add latterly solid blue background add also --ar 3:2 </textarea>
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