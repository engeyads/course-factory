
<?php
$sql = "SELECT * FROM keywords";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $clean_keyword = preg_replace("/[.,\\\\\"'[\]{}()]/", "", $row['keyword']);
        $update_sql = "UPDATE keywords SET keyword = '{$clean_keyword}' WHERE id = {$row['id']}";
        
        if ($conn->query($update_sql) !== TRUE) {
            echo "Error updating record: " . $conn->error;
        }
    }
    echo "Records updated successfully<br>";
} else {
    echo "0 keyword need to clean<br>";
}


// Run a query to delete duplicate rows from the keyword_tag table
$sql = "DELETE kt1 FROM keyword_tag kt1
        INNER JOIN keyword_tag kt2 
        WHERE 
            kt1.id < kt2.id AND 
            kt1.keyword_id = kt2.keyword_id AND 
            kt1.tag_id = kt2.tag_id";

if ($conn->query($sql) === TRUE) {
    // Check if any rows were affected
    if ($conn->affected_rows > 0) {
        echo "Duplicates removed successfully";
    } else {
        echo "No duplicates found";
    }
} else {
    echo "Error removing duplicates: " . $conn->error;
}



function echoKeywordsWithoutTags($conn) {
    // SQL query to select keywords that do not have any associated tags
    $sql = "SELECT k.*
            FROM keywords k
            LEFT JOIN keyword_tag kt ON k.id = kt.keyword_id
            WHERE kt.tag_id IS NULL";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "Keywords without tags:<br>";
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "Keyword: " . $row['keyword'] . "<br>";
        }
    } else {
        echo "No keywords without tags found<br>";
    }
}

// Call the function
echoKeywordsWithoutTags($conn);



function updateKeywordsWithoutTags($conn) {
    // SQL query to update keywords that do not have any associated tags
    $sql = "UPDATE keywords k
            LEFT JOIN keyword_tag kt ON k.id = kt.keyword_id
            SET k.updated_at = NULL
            WHERE kt.tag_id IS NULL";

    // Execute the query and check for errors
    if ($conn->query($sql) !== TRUE) {
        echo "Error updating records: " . $conn->error . "<br>";
    } else {
        // Output success message
        echo "Records updated successfully<br>";
    }
}

// Call the function
updateKeywordsWithoutTags($conn);

function deleteTagsWithoutKeywords($conn) {
    // SQL query to delete tags that do not have any associated keywords
    $sql = "DELETE t FROM tags t
            LEFT JOIN keyword_tag kt ON t.id = kt.tag_id
            WHERE kt.keyword_id IS NULL";

    // Execute the query and check for errors
    if ($conn->query($sql) !== TRUE) {
        echo "Error deleting records: " . $conn->error . "<br>";
    } else {
        // Output success message
        if ($conn->affected_rows > 0) {
            echo "Tags without keywords deleted successfully<br>";
        } else {
            echo "No tags without keywords found<br>";
        }
    }
}

// Call the function
deleteTagsWithoutKeywords($conn);



?>