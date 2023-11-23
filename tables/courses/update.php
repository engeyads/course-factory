<?php
if ($_SESSION['userlevel'] > 2 ) {
    $path = dirname(__FILE__);
    include $path.'/conf.php';   
    if (isset($_POST['keyword']) && $_POST['keyword']!=NULL){$_POST['keyword'] = TextasJsonArray($_POST['keyword']);}
    if (isset($_POST['sitekeywords']) && $_POST['sitekeywords']!=NULL){// Split the input by line breaks
        $lines = explode("\n", $_POST['sitekeywords']);
        
        // Trim each line and remove any empty lines
        $trimmedLines = array_map('trim', $lines);
        $trimmedLines = array_filter($trimmedLines, function($line) {
            return !empty($line);
        });
        
        // Now, $trimmedLines contains an array of trimmed lines from the textarea
        $_POST['sitekeywords1'] = $trimmedLines;
    }
    
    include 'include/update.php';
    // will get $urlback from include/update.php after update or insert query
    header("Refresh: 1; url=" . $urlback);
    exit;
}
?>

