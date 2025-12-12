    <?php
    header("Content-Type: application/json");
    require "db.php";

    $data = json_decode(file_get_contents("php://input"), true);

    $questions = json_encode($data["questions"], JSON_UNESCAPED_UNICODE);
    $title = "Survey " . date("Y-m-d H:i:s");

    $stmt = $conn->prepare("INSERT INTO surveys (title, questions) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $questions);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Survey saved successfully"]);
    } else {
        echo json_encode(["message" => "Error saving survey"]);
    }
    ?>