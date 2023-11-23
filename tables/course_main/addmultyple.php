<?php
  $id = $_GET['id'] ?? null; 
  $prompt = '';
  $system = '';
  $httpurl = $url;
  $folderName =  basename(__DIR__);
  $validby='';
  $theconnection = $conn;
  $updateurl = 'updatemultiple';
  
  
    if (isset($id) && is_numeric($id)) {

    }else {
        exit;
    }

 
  ?>
  
  <div class="row">
    <div id="messageDiv"></div>  
        <div class="col-xl-10 mx-auto">
            <h1>  Create Multiple Courses with keywords from json array</h1>
            <?php 
            // get row from id 
            $subtagrow = GetRow($id,'id','subtags' , $theconnection);
            // get the keywords from the keywords table with the relation from keywords_subtags table
              $keywordsquery = "SELECT keywords.id AS keyword_id, keywords.keyword AS keyword_name 
            FROM keywords 
            INNER JOIN keyword_subtag ON keywords.id = keyword_subtag.keyword_id 
            WHERE keyword_subtag.subtag_id = $id ; ";
            $keywordsresult = mysqli_query($conn, $keywordsquery);
            $keywordlist = '';
            if ($keywordsresult && mysqli_num_rows($keywordsresult) > 0) {
                while ($keywordsrow = mysqli_fetch_assoc($keywordsresult)) {
                    $keywordlist .= $keywordsrow['keyword_name'].' , ';
                }
            } else {
                echo '<h1 >No keywords </h1>';
                exit();
            }
            
            echo '<h2> The courses will add to the sub tag ('.  $subtagrow['subtag'] .') </h2>';
            $tochatgpt = 'i need you to suggest courses for the Category {'.$subtagrow['subtag'].'} for my corporate training website 
            base on this keywords { '.$keywordlist.' }
            return the result in json array as courses name and the keyword that fit on each course The courses should be for corporate not individual do not add online courses or courses that are not for corporate do not add location on the courses name do not mention corporate on the name 
            return the json on this format 
            {
                "courseName": "The course name",
                "keywords": ["Keyword1", "Keyword2", "etc"]
            },
            ';
            ?>
            <hr/>
            <div class="card">
                <div class="card-body mt-3"> 
    <form id="theform" method="post" action="<?php echo $url.$folderName.'/'.$updateurl ; ?>" enctype="multipart/form-data" class="row g-3">
    <div class="row">
    <div class="col-12 mb-3">
    <label class="form-label">The Keywords <?php echo mysqli_num_rows($keywordsresult) ; ?></label>
    <textarea class="form-control mb-3 col-10 " rows="5" placeholder="" aria-label="keywords" name="keywords" id="keywords"   ><?php echo  $keywordlist; ?></textarea>

    </div>        
    <div class="col-12 mb-3">
    <label class="form-label">Excute this on chat GPT</label>
    <textarea class="form-control mb-3 col-10 " rows="5" placeholder="" aria-label="chatgpt" name="chatgpt" id="chatgpt"   ><?php echo $tochatgpt; ?></textarea>

    </div>
    </div>

     <input type="hidden" name="subtag_id" value="<?php echo $id ; ?>">
 
 
    <div class="row"> 
    <div class="col-12  mb-3">
    <label class="form-label">Json</label>
    <textarea class="form-control mb-3 col-10 " rows="10" placeholder="add the json from chat GPT here " aria-label="results" name="results" id="results"   > </textarea> 
    </div>
    </div>
  
    <div class="row"> 
    <div class="col-12  mb-3">
    <label class="form-label">ask gpt for more 3 or more time the resend them all arrays with this</label>
    <textarea class="form-control mb-3 col-10 " rows="2" placeholder="the second" aria-label="second step" name="secondstep" id="secondstep">merge all this in one array remove any duplication courses if the keywords fit in more than one course add them </textarea> 
    </div>
    </div>
    


    <div class='row'>
      <div class='col-2 mb-3'><button type='submit' class='btn btn-secondary px-5'>Add</button></div>
    </div>
  </form>
    </div>
        </div>
    </div>
</div>


  