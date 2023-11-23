<?php
$addsql = "";

if (isset($_GET['filterby']) && $_GET['filterby'] != "ALL") {
    $filterbytagid = (int)$_GET['filterby'];
    $sqlFilter = "SELECT keyword_id FROM keyword_subtag WHERE subtag_id = {$filterbytagid}";
    $resultFilter = $conn->query($sqlFilter);
    $filteredKeywordIds = [];

    if ($resultFilter->num_rows > 0) {
        while($row = $resultFilter->fetch_assoc()) {
            $filteredKeywordIds[] = $row['keyword_id'];
        }
    }

    $filteredKeywordIdsString = implode(',', $filteredKeywordIds);
    $addsql = " WHERE keywords.id IN ({$filteredKeywordIdsString})";
}

$sqlSubtags = "SELECT subtags.id, subtags.subtag as name, COUNT(keyword_subtag.keyword_id) as count 
FROM subtags 
LEFT JOIN keyword_subtag ON subtags.id = keyword_subtag.subtag_id 
GROUP BY subtags.id, subtags.subtag";

$resultSubtags = $conn->query($sqlSubtags);
$subtags = [];

if ($resultSubtags->num_rows > 0) {
    while($rowSubtag = $resultSubtags->fetch_assoc()) {
        $subtags[] = $rowSubtag;
    }
}

?>

<form action="" method="get">
    <select id="filterSelect" name="filterby" class="single-select  " data-select2-id="4" tabindex="-1" aria-hidden="true">
        <option value="">Select a subtag</option>
        <option value="ALL">All subtags</option>
        <?php foreach($subtags as $subtag): ?>
            <option value="<?php echo $subtag['id']; ?>" <?php if(isset($filterbytagid) && $filterbytagid == $subtag['id']) echo '  selected'; ?>>
                <?php echo $subtag['name']; ?> (<?php echo $subtag['count']; ?>)
            </option>
        <?php endforeach; ?>
    </select>
</form>

<?php
if (isset($_GET['filterby']) && $_GET['filterby'] != '') {
    $sql = "SELECT keywords.id as keyword_id, keywords.keyword, subtags.id as subtag_id, subtags.subtag
    FROM keywords
    LEFT JOIN keyword_subtag ON keywords.id = keyword_subtag.keyword_id 
    LEFT JOIN subtags ON keyword_subtag.subtag_id = subtags.id" . $addsql;
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
                    'subtags' => []
                ];
            }
            
            // Add the tag to the keyword's tags if it is not null
            if($row['subtag_id'] !== null && $row['subtag'] !== null) {
                $keywords[$row['keyword_id']]['subtags'][] = [
                    'id' => $row['subtag_id'],
                    'name' => $row['subtag']
                ];
            }
        }
    }

?>

<h1><?php echo $tabletitle; ?></h1>

<a data-subtag-id="<?php echo $filterbytagid; ?>" class="subtag-delete-all text-white btn btn-danger">Delete All Subtags</a>

<hr />
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="example2" class="table table-striped table-bordered" style="width:100%">
                <thead>
                  <tr>
                    <th>Keyword</th>
                    <th>Subtags</th>
                  </tr>
                </thead>
                <tbody>
                    <?php foreach($keywords as $keyword): ?>
                      <tr>
                        <td><?php echo $keyword['keyword']; ?></td>
                        <td>
                            <?php if(!empty($keyword['subtags'])): ?>
                                <?php foreach($keyword['subtags'] as $subtag): ?>
                                    <span class="subtag-label" data-keyword-id="<?php echo $keyword['id']; ?>" 
                                        data-subtag-id="<?php echo $subtag['id']; ?>">
                                        <a href="?filterby=<?php echo $subtag['id']; ?>" title = "Filter By"><?php echo $subtag['name']; ?></a>
                                        <a data-feather="trash-2" class="subtag-delete text-white btn btn-primary">  Delete </a>
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
<?php } ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://unpkg.com/feather-icons"></script>
<script>
$(document).ready(function() {
    $('#filterSelect').on('change', function() {
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
                lengthChange: false,
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
