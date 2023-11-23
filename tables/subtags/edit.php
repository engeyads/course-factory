<?php
if ($_SESSION['userlevel'] > 2 ) {

    $id = $_GET['id'] ?? null; 
    $tagid = $_GET['tagid'] ?? null;
    $tablename = 'subtags';
    $httpurl = $url;
    $tabletitle = 'Sub Tags';  
    $folderName =  basename(__DIR__);
    $prompt = '';
    $system = '';
    $validby='';
    // if $conn2 is not set the connection will be empty
    if (isset($conn)) {
        $theconnection = $conn;
    }else {
        $theconnection = '';
    }
    $updateurl = 'update';
    $remoteDirectory = '';
  
    $row = GetRow($id,'id',$tablename , $theconnection);
    $Columns = GetTableColumns($tablename,$theconnection);

    FormsStart();
    FormsInput('subtag','Name', 'text', true, 'col-12') ;
    echo '<input type="hidden" name="tag_id" value="'.$tagid.'">';
  
 
    FormsEnd(); 
                
    
}
    ?>