<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";      // change if needed
$password = "";          // change if needed
$dbname = "survey_db"; // change to your DB

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed: ".$conn->connect_error]);
    exit;
}

// Read raw JSON input
$input = json_decode(file_get_contents("php://input"), true);

$survey_id = isset($input['survey_id']) ? intval($input['survey_id']) : 0;
$respondent_id = isset($input['respondent_id']) ? intval($input['respondent_id']) : 0;
$answers = isset($input['answers']) ? $input['answers'] : null;

if (!$survey_id || !$respondent_id || !is_array($answers)) {
    echo json_encode(["success" => false, "message" => "Missing or invalid data"]);
    exit;
}

// Convert answers array to JSON for storing
$answers_json = json_encode($answers);

$stmt = $conn->prepare("INSERT INTO responses (survey_id, respondent_id, answers) VALUES (?, ?, ?)");
if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Prepare failed: ".$conn->error]);
    exit;
}

$stmt->bind_param("iis", $survey_id, $respondent_id, $answers_json);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Execute failed: ".$stmt->error]);
}

$stmt->close();
$conn->close();
?>
