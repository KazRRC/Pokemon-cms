<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    die("Not authorized");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request");
}

$commentId = $_POST['comment_id'] ?? null;
$pokemonName = $_POST['pokemon_name'] ?? '';

if (!$commentId) {
    die("Missing comment ID");
}

$stmt = $pdo->prepare("SELECT user_id FROM comments WHERE comment_id=?");
$stmt->execute([$commentId]);
$comment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$comment || $comment['user_id'] != $_SESSION['user']['user_id']) {
    die("You cannot delete this comment.");
}

$stmt = $pdo->prepare("DELETE FROM comments WHERE comment_id=?");
$stmt->execute([$commentId]);

header("Location: pokemon.php?name=" . urlencode($pokemonName));
exit;