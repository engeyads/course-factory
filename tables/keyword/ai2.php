



<?php
$nutfinishcountquery = "SELECT COUNT(*) FROM `keywords` WHERE `updated_at` IS NULL;";
$nutfinishcount = mysqli_query($conn, $nutfinishcountquery);
$nutfinishcount = mysqli_fetch_assoc($nutfinishcount);
$nutfinishcount = $nutfinishcount['COUNT(*)'];

?>

<?php
$nutfinishcountquery2 = "SELECT COUNT(*) FROM `keywords` ";
$nutfinishcount2 = mysqli_query($conn, $nutfinishcountquery2);
$nutfinishcount2 = mysqli_fetch_assoc($nutfinishcount2);
$nutfinishcount2 = $nutfinishcount2['COUNT(*)'];

echo '<h1 >'.$nutfinishcount.' To Finish </h1>';
echo '<h3 > From '.$nutfinishcount2.' Total Keywords </h3>';
if ($nutfinishcount==0) {
    echo '<h1 > All Done </h1>';
    
    exit();
}

    
 
?>
<h1 id="counter">0</h1>

<script>
// Set counter to 0
let counter = 0;

// Create audio context for beep
let audioCtx = new (window.AudioContext || window.webkitAudioContext || false)();
if (!audioCtx) {
    alert('Web Audio API not supported by this browser');
}

// Function to play beep
function beep() {
    let oscillator = audioCtx.createOscillator();
    oscillator.frequency.value = 440;
    oscillator.type = 'square';
    oscillator.connect(audioCtx.destination);
    oscillator.start();
    setTimeout(function() { oscillator.stop(); }, 500);
}

// Function to increment counter and update HTML element
function incrementCounter() {
    counter++;
    document.getElementById("counter").innerHTML = counter;
    if (counter > 100) {
        beep();
    }
}

// Call incrementCounter every 1000 ms (1 second)
setInterval(incrementCounter, 1000);
</script>
<?php 
//$prompt = $_POST['prompt']; 


$system = 'Act As an SEO expert for a corporate training website your expert on corporate training courses , 
base on your experience your task is to tag the given keywords with the given tags. 
You will be given a list of keywords and a list of tags.
You will need to tag each keyword with all suitable tags.
send the result in json array format.
return the result in json array format.
return only the json
if you dint find tag that fit the keyword add the keyword to Miscellaneous tag.
use the Miscellaneous tags only if the keyword is not fit to any of the given tags.
return the json this way  [ { "keyword": "home slogans", "tags": ["Miscellaneous"] }, { "keyword": "adaptability", "tags": ["Human Resources and Personnel Development"] } ]
return only the array ';
$prompt = '
the tags are:
{{{{tags}}}}
';
?>

<?php
    $tagslist= '';
    $query = "SELECT * FROM `tags`   ";
    $tags = mysqli_query($conn, $query);
    $tagcount = mysqli_num_rows($tags);
    foreach ($tags as $tag) {
        $tagslist.= "\n".$tag['tag'];
    }

$prompt .= $tagslist;
$prompt .= '
{{{{/tags}}}}
{{{{keywords}}}}
';

$query = "SELECT * FROM `keywords` WHERE `updated_at` IS NULL ORDER BY `updated_at` ASC LIMIT 30 ";
$keywords = mysqli_query($conn, $query);
$keywordcount = mysqli_num_rows($keywords);

 
if ($keywordcount < 1) {
    exit();
}

$keywordslist = '';
while ($keyword = mysqli_fetch_assoc($keywords)) {
   $keywordslist.= "\n" . $keyword['keyword'];
}
$prompt .= $keywordslist;
$prompt .= '
{{{{/keywords}}}}
';
echo $prompt;
 
echo '<br><br>';
 
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

echo '===>' . $resulttxt.'<===';
echo '<br>';
echo $result['choices'][0]['finish_reason'];
echo '<br>';
echo $token . ' tokens';
echo '<br>';
echo tokenToUSD($token) . ' USD'; 
?>

<?php
function tokenToUSD($tokenAmount) {
    $ratePerThousand = 0.002; 
    $rate = $ratePerThousand / 1000;
    return $tokenAmount * $rate;
}


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
        $keyword = mysqli_real_escape_string($conn, $item['keyword']);
        $query = "SELECT id FROM keywords WHERE keyword = '$keyword'";
        $result = mysqli_query($conn, $query);
        

        $row = mysqli_fetch_assoc($result);
        // check if data returned is empty
        if(empty($row)) {
            echo '<meta http-equiv="refresh" content="0">';
            exit("Keyword not found: $keyword");
        } else {
            $keyword_id = $row['id'];
        }
        $keyword_id = $row['id'];

        $query = "UPDATE keywords SET updated_at = CURRENT_TIMESTAMP WHERE id = $keyword_id";
        mysqli_query($conn, $query);
        


        foreach($item['tags'] as $tag) {  // Changed line
            $tag = mysqli_real_escape_string($conn, $tag);
            $query = "SELECT id FROM tags WHERE tag = '$tag'";
            $result = mysqli_query($conn, $query);

            if(mysqli_num_rows($result) === 0) {
                $query = "INSERT INTO tags (tag) VALUES ('$tag')";
                mysqli_query($conn, $query);
                $tag_id = mysqli_insert_id($conn);
            } else {
                $row = mysqli_fetch_assoc($result);
                $tag_id = $row['id'];
            }

            $query = "INSERT INTO keyword_tag (keyword_id, tag_id) VALUES ($keyword_id, $tag_id)";
            mysqli_query($conn, $query);
        }
    }
}

?>

  <meta http-equiv="refresh" content="0">
