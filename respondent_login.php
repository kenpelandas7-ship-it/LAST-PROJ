<?php
header("Content-Type: application/json");
require "db.php";

$data = json_decode(file_get_contents("php://input"), true);
$email = $data["email"];
$password = $data["password"];

$stmt = $conn->prepare("SELECT id FROM respondents WHERE email = ? AND password = ?");
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$res = $stmt->get_result();

if ($row = $res->fetch_assoc()) {
    echo json_encode(["success" => true, "user_id" => $row["id"]]);
} else {
    echo json_encode(["success" => false]);
}
?>
