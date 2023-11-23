<?php

$tagId = $_POST['tag_id'];

$sql = "DELETE FROM keyword_tag WHERE tag_id = {$tagId}";

if ($conn->query($sql) === TRUE) {
  echo json_encode(['success' => true]);
} else {
  echo json_encode(['success' => false, 'error' => $conn->error]);
} 
?>