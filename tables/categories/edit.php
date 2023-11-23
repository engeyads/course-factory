<?php
if ($_SESSION['userlevel'] > 2 ) {
    $newdbstype = $db_name == "agile4training" || $db_name == "blackbird-training.co.uk";
    $path = dirname(__FILE__);
    include $path.'/conf.php';   
    $row = GetRow($id,'id',$tablename , $theconnection);
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
        FormsImg('glyphicon','Image', 'col-4 row float-start',$categoriesimgurl,'s_alias',$tablename,$remoteDirectory,$lastPart,'1.5',400,40);
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
    FormsDateTime('published_at','Publish Date And Time', false, 'col-6') ;
    FormsEnd(); 
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