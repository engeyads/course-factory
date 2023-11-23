<?php
$folderName = basename(__DIR__);
$tablename = 'keywords_tag_id';
$tabletitle = 'tag_id Keywords';  
$editslug = 'keyword_tag_id_edit';
$trashslug = 'keyword_tag_id_trash';
$deleteslug = 'keyword_tag_id_delete';
$maxlenginfield = 20;
$theconnection = $conn;
$thedbname = $conn1dbname;
$ignoredColumns = [  'deleted_at'];
//$urlslug = $websiteurl .$categoriesslug;
$tooltips = [ ];
 $popups = [ ];
 $jsonarrays = [ ];
 $imagePaths = [ ];
 $urlPaths = [ ];
$fieldTitles = [ 'name' => 'Name', 'subtag_id' => 'Sub Tag',  'created_at' => 'Created', 'updated_at' => 'Updated' , 'published_at' => 'Publish'];
$dateColumns = ['created_at', 'updated_at','published_at']; // replace with your actual date columns


// replace subtag id with subtag name
$subtagQuery = "SELECT id, subtag FROM subtags";
$subtagResult = mysqli_query($theconnection, $subtagQuery);
if (!$subtagResult) {echo "Error: " . mysqli_error($theconnection);}
$subtags = array();
while ($subtag = mysqli_fetch_assoc($subtagResult)) {$subtags[$subtag['id']] = $subtag['subtag'];}
mysqli_free_result($subtagResult);
$dataArrays = [
    'subtag_id' => $subtags,
    // Add more column mappings here
];
?>
<a href="<?php echo $url . $folderName . '/keyword_tag_id_add_multiple' ; ?>" class="btn btn-primary">Assign tag_id to Keywords</a>
<?php
include 'include/view.php';
?>
