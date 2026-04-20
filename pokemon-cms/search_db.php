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
$q = $_GET['q'] ?? '';
$category = $_GET['category'] ?? 'all';

$sql = "SELECT * FROM pokemon WHERE name LIKE ?";
$params = ["%$q%"];

if ($category !== "all") {
    $sql .= " AND category = ?";
    $params[] = $category;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
