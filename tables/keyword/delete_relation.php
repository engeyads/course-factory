<?php
// Include your database connection code here...

$keywordId = $_POST['keyword_id'];
$tagId = $_POST['tag_id'];

$sql = "DELETE FROM keyword_tag WHERE keyword_id = ? AND tag_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $keywordId, $tagId);

if ($stmt->execute()) {
  echo json_encode(['success' => true]);
} else {
  echo json_encode(['success' => false, 'error' => $conn->error]);
}

?>