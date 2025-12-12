<?php
header('Content-Type: application/json');
require 'db.php';

$id = intval($_GET['id']);

$stmt = $conn->prepare("DELETE FROM surveys WHERE id = ?");
$stmt->bind_param("i", $id);
$success = $stmt->execute();

echo json_encode(['message' => $success ? 'Survey deleted successfully' : 'Failed to delete survey']);
?>
