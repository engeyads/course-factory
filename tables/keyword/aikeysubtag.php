<?php 
$now = date('Y-m-d H:i:s'); 
$tagid = 10;
if (isset($_GET['time'])) { $now = $_GET['time']; }
?>
<a href="?time=<?php echo $now; ?>">start</a>
<?php
function tokenToUSD($tokenAmount) {
    $ratePerThousand = 0.002; 
    $rate = $ratePerThousand / 1000;
    return $tokenAmount * $rate;
}

$totalcountquery = "SELECT COUNT(*) FROM keywords LEFT JOIN keyword_tag ON keywords.id = keyword_tag.keyword_id LEFT JOIN tags ON keyword_tag.tag_id = tags.id WHERE  tags.id = '$tagid' ";
$totalcount = mysqli_query($conn, $totalcountquery);
$totalcount = mysqli_fetch_assoc($totalcount);
$totalcount = $totalcount['COUNT(*)'];
echo '<h1 >  '.$totalcount.' Total Keywords on tag '.$tagid .'  </h1>';

$nutfinishcountquery = "SELECT COUNT(*) FROM keywords LEFT JOIN keyword_tag ON keywords.id = keyword_tag.keyword_id LEFT JOIN tags ON keyword_tag.tag_id = tags.id WHERE keywords.updated_at  < '$now' AND tags.id = '$tagid' ";
$nutfinishcount = mysqli_query($conn, $nutfinishcountquery);
$nutfinishcount = mysqli_fetch_assoc($nutfinishcount);
$nutfinishcount = $nutfinishcount['COUNT(*)'];
echo '<h3 >  '.$nutfinishcount.'  Keywords on tag  '.$tagid .' To Check </h3>';
if ($nutfinishcount==0) {
    echo '<h1 > All Done </h1>';   
    exit();
}
$finishcountquery = "SELECT COUNT(*) FROM keywords LEFT JOIN keyword_tag ON keywords.id = keyword_tag.keyword_id LEFT JOIN tags ON keyword_tag.tag_id = tags.id WHERE keywords.updated_at  >= '$now' AND tags.id = '$tagid' ";
$finishcount = mysqli_query($conn, $finishcountquery);
$finishcount = mysqli_fetch_assoc($finishcount);
$finishcount = $finishcount['COUNT(*)'];
echo '<h3 >  '.$finishcount.'  Keywords on tag  '.$tagid .' has Done </h3>';




$keywordnotfinishcountquery = "SELECT COUNT(*) FROM `keywords` ;";
$keywordnotfinishcount = mysqli_query($conn, $keywordnotfinishcountquery);
$keywordnotfinishcount = mysqli_fetch_assoc($keywordnotfinishcount);
$keywordnotfinishcount = $keywordnotfinishcount['COUNT(*)'];


echo '<h3 >  '.$keywordnotfinishcount.' Total keywords </h3>';




$subtagcountquery = "SELECT COUNT(*) FROM `subtags`  WHERE  `tag_id` = '$tagid' ";
$subtagcount = mysqli_query($conn, $subtagcountquery);
$subtagcount = mysqli_fetch_assoc($subtagcount);
$subtagcount = $subtagcount['COUNT(*)'];
echo '<h1 > '.$subtagcount.' Total  Subtags </h1>';


 



//the query  where update_at smaller than now
$query = "SELECT keywords.id as keyword_id, keywords.keyword, tags.id as tag_id, tags.tag
          FROM keywords
          LEFT JOIN keyword_tag ON keywords.id = keyword_tag.keyword_id
          LEFT JOIN tags ON keyword_tag.tag_id = tags.id
          WHERE `keywords`.`updated_at` < '$now' AND `tags`.`id` = '$tagid'
          ORDER BY `keywords`.`updated_at` ASC
          LIMIT 1";
$keywords = mysqli_query($conn, $query);
if (!$keywords) {
    echo mysqli_error($conn);
    exit();
}
$keyword = mysqli_fetch_assoc($keywords);
// if no keyword then echo no keyword
if (!$keyword) {
    echo '<h1 >No keyword </h1>';
    exit();
}
$keywordid = $keyword['keyword_id'];
$keywordname = $keyword['keyword'];
echo '<h5 >Now we check the keyword { '.$keywordid .' '. $keywordname.' } </h5>';


//get 1 sub tag
$query = "SELECT * FROM `subtags` WHERE `updated_at` < '$now' AND `subtags`.`tag_id` = '$tagid'  ORDER BY `id` ASC  ";

$subtags = mysqli_query($conn, $query);
$subtag = mysqli_fetch_assoc($subtags);
$subtagsnames = '';

if (mysqli_num_rows($subtags) > 0) {
    foreach ($subtags as $subtag) {
        $subtagid = $subtag['id'];
        $subtagname = $subtag['subtag'];
        //echo '<span>  '.$subtagid.' '.$subtagname.' </span><br>'; 
        $subtagsnames .= $subtagname.' , ';
    
    }

} else {
    echo '<h1 >No subtag </h1>';
    exit();
}
// for each echo  all subtag id and name







echo $system = '
Your task is to analyze the provided list of course titles and identify the top courses that are most closely related to a specified keyword. 
You will receive a list of course names and one keyword. 
Your task is to select the top courses that have the strongest relation to the keyword. 
The outcome should be returned in the format of a JSON array without key. 
If no course names correspond to the keyword no return is necessary. 
Only the JSON array should be returned. 
do not suggest courses name or keywords that are not in the list.
Below are the course titles and the keyword:
';
$prompt = '
[courses]
';
$prompt .=   $subtagsnames;
$prompt .= '
[/courses]
[keywords]'.$keywordname.'[/keywords]';
echo $prompt;

if (!isset($_GET['time'])){
    echo '<h1 >Please click start to start </h1>';
    exit();
}

$result =  call_openai($prompt, $openai_api_key,$system);

$resulttxt = $result['choices'][0]['message']['content'];
$token = $result['usage']['total_tokens'];



/// 

$prefix = "Result:";
if (substr($resulttxt, 0, strlen($prefix)) == $prefix) {
    $resulttxt = substr($resulttxt, strlen($prefix));
}

$prefix = "Answer: ";
if (substr($resulttxt, 0, strlen($prefix)) == $prefix) {
    $resulttxt = substr($resulttxt, strlen($prefix));
}

$prefix = "Answer:";
if (substr($resulttxt, 0, strlen($prefix)) == $prefix) {
    $resulttxt = substr($resulttxt, strlen($prefix));
}
if (isset($token) && $token!= null) {
} else {
    $token = 0;
    echo '<meta http-equiv="refresh" content="10">';
    exit();
}
echo '===>' . $resulttxt.'<===';
echo '<br>';
echo $result['choices'][0]['finish_reason'];
echo '<br>';
echo $token . ' tokens';
echo '<br>';
echo tokenToUSD($token) . ' USD <br>'; 




$resulttxt = trim($resulttxt);

$data = json_decode($resulttxt, true);



if (json_last_error() == JSON_ERROR_NONE) {
    // Your code here
} else {

    
switch (json_last_error()) {
    case JSON_ERROR_NONE:
        echo ' - No errors';
    break;
    case JSON_ERROR_DEPTH:
        echo ' - Maximum stack depth exceeded';
    break;
    case JSON_ERROR_STATE_MISMATCH:
        echo ' - Underflow or the modes mismatch';
    break;
    case JSON_ERROR_CTRL_CHAR:
        echo ' - Unexpected control character found';
    break;
    case JSON_ERROR_SYNTAX:
        echo ' - Syntax error, malformed JSON';
    break;
    case JSON_ERROR_UTF8:
        echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
        echo ' <meta http-equiv="refresh" content="0">';
    break;
    default:
        echo ' - Unknown error';
    break;
}
 
}


if(is_null($data)) {
    echo "Failed to decode JSON.";
    echo '<meta http-equiv="refresh" content="0">';
    exit();
} else {
    foreach($data as $item) {
           $item;
         // select the subtag id from subtag name
            $query = "SELECT id FROM subtags WHERE subtag = '$item'";
            $subtags = mysqli_query($conn, $query);
            // if mysql error
            if (!$subtags) {
                echo mysqli_error($conn);
                echo '<meta http-equiv="refresh" content="0">';
                exit();
            }
            $subtag = mysqli_fetch_assoc($subtags);
            // if no subtag then echo no subtag
            if (!$subtag) {
                echo '<h1 >No subtag </h1>';
                echo '<meta http-equiv="refresh" content="0">';
                exit();
            }
            $subtagid = $subtag['id'];
            $query = "INSERT INTO `keyword_subtag` (`id`, `tag_id`, `keyword_id`, `subtag_id`, `created_at`, `updated_at`, `published_at`, `deleted_at`) 
            VALUES (NULL, $tagid, '$keywordid', '$subtagid', current_timestamp(), NULL, NULL, NULL)
            ON DUPLICATE KEY UPDATE `updated_at` = current_timestamp();";
            // if mysqli_query($conn, $query) is true then echo the subtag id and name
            if (mysqli_query($conn, $query)) {
                echo '<span>  '.$subtagid.' '.$item.' </span><br>'; 
            }else{
               //echo mysql error
                echo mysqli_error($conn);
                echo '<meta http-equiv="refresh" content="0">';
               exit();
            }
     
    }
// updaye the keyword updated_at to now
$query = "UPDATE `keywords` SET `updated_at` = '$now' WHERE `keywords`.`id` = '$keywordid' ;";
mysqli_query($conn, $query);
}
  
?>
  <meta http-equiv="refresh" content="0">
