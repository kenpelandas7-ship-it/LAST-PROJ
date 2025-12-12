<?php
header('Content-Type: application/json');
require 'db.php';

$survey_id = intval($_GET['id']);

// Get survey
$stmt = $conn->prepare("SELECT * FROM surveys WHERE id = ?");
$stmt->bind_param("i", $survey_id);
$stmt->execute();
$result = $stmt->get_result();
$survey = $result->fetch_assoc();

if (!$survey) {
    echo json_encode(['message' => 'Survey not found']);
    exit;
}

$survey['questions'] = json_decode($survey['questions'], true);


$resStmt = $conn->prepare("SELECT * FROM responses WHERE survey_id = ?");
$resStmt->bind_param("i", $survey_id);
$resStmt->execute();
$resResult = $resStmt->get_result();

$responses = [];
while($row = $resResult->fetch_assoc()){
    $row['answers'] = json_decode($row['answers'], true);
    $responses[] = $row;
}

$survey['responses'] = $responses;

echo json_encode($survey);
?>
