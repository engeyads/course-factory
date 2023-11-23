<?php
  $id = $_GET['id'] ?? null; 
  $tablename = 'keywords_tag_id';
  $tabletitle = 'tag_id Keywords';  
  $updateurl = 'keyword_tag_id_update';
  $viewslug = 'keyword_tag_id_view';
  $editslug = 'keyword_tag_id_edit';
  $prompt = '';
  $system = '';
  $httpurl = $url;
  $folderName =  basename(__DIR__);
  $validby='';
  $theconnection = $conn;
    FormsStart();
    $row = GetRow($id,'id',$tablename , $theconnection);
    $Columns = GetTableColumns($tablename,$theconnection);
    FormsInput('name','Name', 'text', false, 'col-6',false,0,0,'',false,$prompt,$system) ;
$subtagQuery = "SELECT id, subtag FROM subtags";
$subtagResult = mysqli_query($theconnection, $subtagQuery);
if (!$subtagResult) {echo "Error: " . mysqli_error($theconnection);}
$subtags = array();
while ($subtag = mysqli_fetch_assoc($subtagResult)) {$subtags[$subtag['id']] = $subtag['subtag'];}
$options = $subtags;
mysqli_free_result($subtagResult);
    FormsSelect('subtag_id','Sub Tag', $options , true, 'col-4') ;
     FormsDateTime('published_at','Publish Date And Time', false, 'col-6') ;
    FormsEnd(); 
  