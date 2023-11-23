
 
<a href="<?php echo $url ;?>keyword/fix">FIX</a>
<?php
if (isset($_GET['filterby'])){
    $filterbytagid = (int)$_GET['filterby'];
    $addsql = " AND tags.id = {$filterbytagid}";
}else{
    $addsql = "";
}
$sql2 = "SELECT tags.id as tag_id, tags.tag, keywords.id as keyword_id, keywords.keyword
FROM tags
INNER JOIN keyword_tag ON tags.id = keyword_tag.tag_id
INNER JOIN keywords ON keyword_tag.keyword_id = keywords.id" . $addsql;

$result2 = $conn->query($sql2);
$tabletitle2 = "Tags";
$tags = [];

if ($result2->num_rows > 0) {
    // output data of each row
    while($row2 = $result2->fetch_assoc()) {
        // If the tag is not yet in the array, add it
        if (!array_key_exists($row2['tag_id'], $tags)) {
            $tags[$row2['tag_id']] = [
                'id' => $row2['tag_id'],
                'tag' => $row2['tag'],
                'keywords' => []
            ];
        }
        
        // Add the keyword to the tag's keywords if it is not null
        if($row2['keyword_id'] !== null && $row2['keyword'] !== null) {
            $tags[$row2['tag_id']]['keywords'][] = [
                'id' => $row2['keyword_id'],
                'name' => $row2['keyword']
            ];
        }
    }
}

?>
<h1><?php echo $tabletitle2; ?></h1>
<hr />
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="example3" class="table table-striped table-bordered" style="width:100%">

                <thead>
                  <tr>
                    <th>Tag</th>
                    <th>Keywords</th>
                  </tr>
                </thead>

                <tbody>
                    <?php foreach($tags as $tag): ?>
                      <tr>
                        <td><?php echo $tag['tag']; ?></td>
                        <td>
                            <?php if(!empty($tag['keywords'])): ?>
                                <?php foreach($tag['keywords'] as $keyword): ?>
                                    <span class="keyword-label" data-tag-id="<?php echo $tag['id']; ?>" 
                                        data-keyword-id="<?php echo $keyword['id']; ?>">
                                        <a href="?filterby=<?php echo $keyword['id']; ?>" title = "Filter By"><?php echo $keyword['name']; ?></a>
                                        <i data-feather="trash-2" class="keyword-delete"></i>
                                    </span>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>
        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://unpkg.com/feather-icons"></script>

<script>
$(document).ready(function() {

    feather.replace();

    $(function () {
        "use strict";

        $(document).ready(function () {
            $('#example').DataTable();
        });

        $(document).ready(function () {
            var table = $('#example2').DataTable({
                lengthChange: false,
                buttons: ['copy', 'excel', 'pdf', 'print']
            });

            table.buttons().container()
                .appendTo('#_wrapper .col-md-6:eq(0)');
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
});
</script>



