<?php
$addsql = "";
$filterbytagid = isset($_GET['filterby']) && $_GET['filterby'] !== 'ALL' ? (int)$_GET['filterby'] : '';
$filterby2 = $_GET['filterby2'] ?? '';


// Check if a sub tag has been selected
$subtagid = $_GET['filterby2'] ?? '';
if (!empty($subtagid) && $subtagid !== 'ALL') {
    $subtagid = (int)$subtagid;

    // Get the keyword IDs associated with the selected sub tag
    $sqlSubTagFilter = "SELECT keyword_id FROM keyword_subtag WHERE subtag_id = {$subtagid}";
    $resultSubTagFilter = $conn->query($sqlSubTagFilter);
    $filteredSubTagKeywordIds = [];

    if ($resultSubTagFilter->num_rows > 0) {
        while($row = $resultSubTagFilter->fetch_assoc()) {
            $filteredSubTagKeywordIds[] = $row['keyword_id'];
        }
    }

    $filteredSubTagKeywordIdsString = implode(',', $filteredSubTagKeywordIds);

    // If both a tag and sub tag have been selected, append to the existing WHERE clause
    // If only a sub tag has been selected, create a new WHERE clause
    if (!empty($addsql)) {
        $addsql .= " AND keywords.id IN ({$filteredSubTagKeywordIdsString})";
    } else {
        $addsql = " WHERE keywords.id IN ({$filteredSubTagKeywordIdsString})";
    }
}else if ($filterbytagid != "ALL") {
     $sqlFilter = "SELECT keyword_id FROM keyword_tag WHERE tag_id = {$filterbytagid}";
    $resultFilter = $conn->query($sqlFilter);
    $filteredKeywordIds = [];

    if ($resultFilter->num_rows > 0) {
        while($row = $resultFilter->fetch_assoc()) {
            $filteredKeywordIds[] = $row['keyword_id'];
        }
    }
    $filteredKeywordIdsString = implode(',', $filteredKeywordIds);
    $addsql = " WHERE keywords.id IN ({$filteredKeywordIdsString})";
}else {
    $filterbytagid = "";
}
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
if ($filterbytagid != "") {
    $addsql2 = " WHERE `subtags`.`tag_id` = '$filterbytagid' ";
} else { 
    $addsql2 = "";
}
$sqlsubtag = "SELECT *, (SELECT COUNT(*) FROM `subtags`) as count FROM `subtags`" . $addsql2 . " ORDER BY `tag_id` ASC ";

$sqlsubtag = "SELECT subtags.id, subtags.subtag as name, COUNT(keyword_subtag.keyword_id) as count 
FROM subtags  
LEFT JOIN keyword_subtag ON subtags.id = keyword_subtag.subtag_id " . $addsql2 . " 
GROUP BY subtags.id, subtags.subtag";
$resultsubtag = mysqli_query($conn, $sqlsubtag);
// fetch all results into an associative array
$subtags = mysqli_fetch_all($resultsubtag, MYSQLI_ASSOC);
?>
<form action="" method="get">
    <select id="filterSelect" name="filterby" class="single-select m-3  " data-select2-id="4" tabindex="-1" aria-hidden="true">
        <option value="">Select a tag</option>
        <option value="ALL">All tags</option>
        <?php foreach($tags as $tag): ?>
            <option value="<?php echo $tag['id']; ?>" <?php if(isset($filterbytagid) && $filterbytagid == $tag['id']) echo '  selected'; ?>>
                <?php echo $tag['name']; ?> (<?php echo $tag['count']; ?>)
            </option>
        <?php endforeach; ?>
    </select>
    <br />
    <select id="filterSelect2" name="filterby2" class="single-select  " data-select2-id="5" tabindex="-1" aria-hidden="true">
    <option value="">Select a Sub Tag</option>
    <option value="ALL">All Sub Tags</option>
    <?php foreach($subtags as $subtag): ?>
        <option value="<?php echo $subtag['id']; ?>" <?php if(isset($subtagid) && $subtagid == $subtag['id']) echo '  selected'; ?>>
            <?php echo $subtag['id'] . $subtag['name']; ?> (<?php echo $subtag['count']; ?>)
        </option>
    <?php endforeach; ?>
</select> 

</form>
<?php
$sql = "SELECT keywords.id as keyword_id, keywords.keyword, tags.id as tag_id, tags.tag
FROM keywords
LEFT JOIN keyword_tag ON keywords.id = keyword_tag.keyword_id 
LEFT JOIN tags ON keyword_tag.tag_id = tags.id" . $addsql;
$result = $conn->query($sql);
$tabletitle = "Keywords";
$keywords = [];
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        // If the keyword is not yet in the array, add it
        if (!array_key_exists($row['keyword_id'], $keywords)) {
            $keywords[$row['keyword_id']] = [
                'id' => $row['keyword_id'],
                'keyword' => $row['keyword'],
                'tags' => []
            ];
        }
        // Add the tag to the keyword's tags if it is not null
        if($row['tag_id'] !== null && $row['tag'] !== null) {
            $keywords[$row['keyword_id']]['tags'][] = [
                'id' => $row['tag_id'],
                'name' => $row['tag']
            ];
        }
    }
}
if (isset($_GET['filterby']) && $_GET['filterby'] != '') {
?>
<h1><?php echo $tabletitle; ?></h1>
<a data-tag-id="<?php echo $filterbytagid; ?>" class="tag-delete-all text-white btn btn-danger">Delete All Tags</a>
<button id="copyAllBtn" class="btn btn-primary">Copy All Keywords</button>
<hr />
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="example2" class="table table-striped table-bordered" style="width:100%">

                <thead>
                  <tr>
                    <th>Keyword</th>
                    <th>Tags</th>
                    <th>Sub Tags</th>
                  </tr>
                </thead>
                <tbody>
    <?php foreach($keywords as $keyword): ?>
        <tr>
            <td  class="keyword"><?php echo $keyword['keyword']; ?></td>
            <td>
                <?php if(!empty($keyword['tags'])): ?>
                    <?php foreach($keyword['tags'] as $tag): ?>
                        <span class="tag-label" data-keyword-id="<?php echo $keyword['id']; ?>" 
                            data-tag-id="<?php echo $tag['id']; ?>">
                            <a href="?filterby=<?php echo $tag['id']; ?>" title = "Filter By"><?php echo $tag['name']; ?></a>
                            <a data-feather="trash-2" class="tag-delete text-white btn btn-primary">  Delete </a>
                        </span>
                    <?php endforeach; ?>
                <?php endif; ?>
            </td>
            <td>
                <?php
                    // Query to retrieve subtags for each keyword
                    $sqlSubtags = "SELECT subtags.subtag FROM subtags 
                                    LEFT JOIN keyword_subtag ON subtags.id = keyword_subtag.subtag_id
                                    WHERE keyword_subtag.keyword_id = {$keyword['id']}";
                    $resultSubtags = $conn->query($sqlSubtags);
                    while($rowSubtag = $resultSubtags->fetch_assoc()) {
                        echo "<span class='subtag-label'>" . $rowSubtag['subtag'] . "</span>, ";
                    }
                ?>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>

            </table>
        </div>
    </div>
</div>
<?php
} ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://unpkg.com/feather-icons"></script>
<script src="https://cdn.jsdelivr.net/clipboard.js/1.5.12/clipboard.min.js"></script>
<script>
$(document).ready(function() {
    $('#filterSelect').on('change', function() {
        this.form.submit();
    });
    $('#filterSelect2').on('change', function() {
        this.form.submit();
    });
    feather.replace();
    $(function () {
        "use strict";
        $(document).ready(function () {
            $('#example').DataTable();
        });
        $(document).ready(function () {
            var table = $('#example2').DataTable({
                pageLength: 100,
                lengthMenu: [[10, 25, 50, 100, 200, 500, -1], [10, 25, 50, 100, 200, 500, "All"]],
                buttons: ['copy', 'excel', 'pdf', 'print']
            });

            table.buttons().container()
                .appendTo('#example2_wrapper .col-md-6:eq(0)');
        });
    });
    $('body').on('click', '.tag-label', function(e) {
        if (!$(e.target).is('svg')) {
            return;
        }
        var keywordId = $(this).data('keyword-id');
        var tagId = $(this).data('tag-id');
        if (keywordId === undefined || tagId === undefined) {
            alert('Keyword ID or Tag ID is undefined');
            return;
        }
        var userConfirmation = confirm("Are you sure you want to delete this tag?");
        if (userConfirmation) {
            $.ajax({
                url: 'delete_relation',
                type: 'POST',
                data: {
                    keyword_id: keywordId,
                    tag_id: tagId
                },
                context: this,
                success: function(data) {
                    $(this).remove();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Could not delete relation: ' + errorThrown);
                }
            });
        }
    });
    $('body').on('click', '.tag-delete-all', function(e) {
        e.preventDefault();
        var tagId = $(this).data('tag-id'); // Get tag ID from the delete button
        var userConfirmation = confirm("Are you sure you want to delete all tags?");
        if (userConfirmation) {
            $.ajax({
                url: 'deleteall_relation',
                type: 'POST',
                data: { tag_id: tagId }, // Send the tag ID to the server
                success: function(data) {
                    // Update your UI here to reflect the deletion of all tags
                    location.reload(); // Or you can simply reload the page
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Could not delete all relations: ' + errorThrown);
                }
            });
        }
    });
});


</script>


<script>
$(document).ready(function() {
    $('#copyAllBtn').on('click', function() {
        var keywords = Array.from(document.querySelectorAll('.keyword')).map(function(td) {
            return td.innerText;
        }).join(", ");

        navigator.clipboard.writeText(keywords).then(function() {
            alert('Keywords copied to clipboard!');
        }, function(err) {
            alert('Failed to copy keywords: ', err);
        });
    });
});
</script>