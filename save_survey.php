<?php
header('Content-Type: application/json');
require 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

$title = $data['title'] ?? '';
$questions = json_encode($data['questions'] ?? []);
$id = $data['id'] ?? null;

if (!$title || !$questions) {
    echo json_encode(['message' => 'Title and questions are required']);
    exit;
}

if ($id) {
   
    $stmt = $conn->prepare("UPDATE surveys SET title=?, questions=? WHERE id=?");
    $stmt->bind_param("ssi", $title, $questions, $id);
    $success = $stmt->execute();
    echo json_encode(['message' => $success ? 'Survey updated successfully' : 'Failed to update survey']);
} else {
    
    $stmt = $conn->prepare("INSERT INTO surveys (title, questions) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $questions);
    $success = $stmt->execute();
    echo json_encode(['message' => $success ? 'Survey saved successfully' : 'Failed to save survey']);
}
?>
