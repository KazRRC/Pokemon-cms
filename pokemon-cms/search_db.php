<?php
require 'db.php';

header('Content-Type: application/json');

try {
    $q = $_GET['q'] ?? '';
    $category = $_GET['category'] ?? 'all';

    $sql = "SELECT * FROM pokemon WHERE name LIKE ?";
    $params = ["$q%"];

    if ($category !== "all") {
        $sql .= " AND category = ?";
        $params[] = $category;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));

} catch (Exception $e) {
    echo json_encode([]);
}