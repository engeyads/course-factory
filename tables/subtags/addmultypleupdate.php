<?php
$tablename = 'subtags';
$tabletitle = 'Sub tags';
$folderName = 'keyword';
$theconnection = $conn;
$viewslug = 'viewall';
$restype = '';
$change = false;
$keyword_id = trim($_POST['keyword_id']);
$_POST['keyword_id'] = TextasJsonArray($_POST['keyword_id']);
$tag_idId = $_POST['tag_id_id'];

// Decode the JSON data
$keywords = json_decode($_POST['keyword_id'], true);

foreach ($keywords as $keyword) {
    // If $keyword is an array, you might need to use $keyword['value'] or other key
    $keywordText = is_array($keyword) ? $keyword['value'] : $keyword;

    // Escape special characters in a string for use in an SQL statement
    $safeKeyword = $conn->real_escape_string($keywordText);

    $sql = "INSERT IGNORE INTO subtags (tag_id, subtag, updated_at) VALUES ($tag_idId, '$safeKeyword', NOW())";
    // Execute statement
    if ($conn->query($sql) === false) {
        echo "Error: " . $conn->error;
    } else {
        echo "Success <br>";
    }
} 

?>
