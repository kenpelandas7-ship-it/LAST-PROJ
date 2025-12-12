<?php
header('Content-Type: application/json');
require 'db.php';

$surveyId = intval($_GET['survey_id']);
$questionIndex = intval($_GET['question_index']);

$stmt = $conn->prepare("SELECT questions FROM surveys WHERE id = ?");
$stmt->bind_param("i", $surveyId);
$stmt->execute();
$result = $stmt->get_result();
$survey = $result->fetch_assoc();

if(!$survey){
    echo json_encode(['message'=>'Survey not found']);
    exit;
}

$questions = json_decode($survey['questions'], true);
if(!isset($questions[$questionIndex])){
    echo json_encode(['message'=>'Question not found']);
    exit;
}

array_splice($questions, $questionIndex, 1); // remove question
$questions_json = json_encode($questions);

$stmt = $conn->prepare("UPDATE surveys SET questions=? WHERE id=?");
$stmt->bind_param("si", $questions_json, $surveyId);
$success = $stmt->execute();

echo json_encode(['message' => $success ? 'Question deleted successfully' : 'Failed to delete question']);
?>
