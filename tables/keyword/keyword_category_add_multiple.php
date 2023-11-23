<?php
  $id = $_GET['id'] ?? null; 
  $tablename = 'keyword_tag_id_relation';
  $tabletitle = 'tag_id Keywords';  
  $updateurl = 'keyword_tag_id_add_multiple_update';
  $viewslug = 'keyword_tag_id_view';
  $editslug = 'keyword_tag_id_add_multiple';
  $prompt = '';
  $system = '';
  $httpurl = $url;
  $folderName =  basename(__DIR__);
  $validby='';
  $theconnection = $conn;
  
  
  $sqlTags = "SELECT tags.id, tags.tag as name, COUNT(keyword_tag.keyword_id) as count 
  FROM tags 
  LEFT JOIN keyword_tag ON tags.id = keyword_tag.tag_id 
  GROUP BY tags.id, tags.tag";
 
  $resultTags = $conn->query($sqlTags);


  $tags = [];
  
  if ($resultTags->num_rows > 0) {
      while($rowTag = $resultTags->fetch_assoc()) {
          $tags[] = $rowTag;
      }
  }
 
  ?>
  
  <div class="row">
    <div id="messageDiv"></div>  
        <div class="col-xl-10 mx-auto">
            <h1> Assign Keywords to tag_id</h1>
            <hr/>
            <div class="card">
                <div class="card-body"> 
    <form id="theform" method="post" action="<?php echo $url.$folderName.'/'.$updateurl ; ?>" enctype="multipart/form-data" class="row g-3">
     
    <select id="filterSelect" name="tag_id_id" class="single-select m-3  " data-select-id="4" tabindex="-1" aria-hidden="true">
        <option value="">Select a tag</option>
        <option value="ALL">All tags</option>
        <?php foreach($tags as $tag): ?>
            <option value="<?php echo $tag['id']; ?>">
                <?php echo $tag['name']; ?> (<?php echo $tag['count']; ?>)
            </option>
        <?php endforeach; ?>
    </select>
 
    <div class="row"> 
    <div class="col-12  mb-3">
    <label class="form-label">Keywords</label>
    <textarea class="form-control mb-3 col-10 " rows="50" placeholder="add keywords here 1 keyword per line" aria-label="key" name="keyword_id" id="keyword_id"   > </textarea> 
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


  