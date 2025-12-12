<?php
header('Content-Type: application/json');
require 'db.php';

$res = $conn->query("SELECT * FROM surveys ORDER BY id DESC");

$surveys = [];
while($row = $res->fetch_assoc()){
    $row['questions'] = json_decode($row['questions'], true); // decode JSON
    $surveys[] = $row;
}

echo json_encode($surveys);
?>
