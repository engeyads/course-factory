<div class="row">
    <div class="col-xl-12 mx-auto">
        <h1 class="mb-0 text-uppercase">  AI </h1>
        <hr/>
        <div class="card">
            <div class="card-body">
                
    <form method="post" action="ai2" enctype="multipart/form-data" class="row g-3">
        <div class="col-8  mb-3">
        <label class="form-label"></label>
         <textarea class="form-control mb-3" rows="20" name="prompt" id="prompt" readonly>
<?php 
$prompt = '
Act As an SEO expert for a corporate training website your expert on corporate training courses , 
base on your experience your task is to tag the given keywords with the given tags. 
You will be given a list of keywords and a list of tags.
You will need to tag each keyword with all suitable tags.
if you dint find tag that fit the keyword you can add new tag. 
send the result in json array format.
return the result in json array format.
return only the json
if you dint find tag that fit the keyword you can add new tag.
use the Miscellaneous tags only if the keyword is not fit to any of the given tags.
return the json this way  [ { "keyword": "home slogans", "tags": ["Miscellaneous"] }, { "keyword": "adaptability", "tags": ["Human Resources and Personnel Development"] } ]
<tags>
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
</tags>
<keywords>
';

$query = "SELECT * FROM `keywords` WHERE `updated_at` IS NULL ORDER BY `updated_at` DESC LIMIT 20";
$keywords = mysqli_query($conn, $query);
$keywordcount = mysqli_num_rows($keywords);
$keywordslist = '';
while ($keyword = mysqli_fetch_assoc($keywords)) {
   $keywordslist.= "\n" . $keyword['keyword'];
}
$prompt .= $keywordslist;
$prompt .= '
</keywords>
';
echo $prompt;
?>
         </textarea>

        </div>

        <div class='row'>
            <div class='col-2 mb-3'>
                <button type='submit' class='btn btn-secondary px-5'>Next</button>
            </div>
        </div>
    </form>
   

                
            </div>
            </div>
        </div>
    </div>
</div>
	 
 