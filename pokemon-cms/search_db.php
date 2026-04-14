<?php
require 'db.php';

if (!isset($_GET['q'])) {
    echo json_encode([]);
    exit;
}

$search = $_GET['q'];

$stmt = $pdo->prepare("SELECT * FROM pokemon WHERE name LIKE ?");
$stmt->execute([$search . '%']);

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($results);
