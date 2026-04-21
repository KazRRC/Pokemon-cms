<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    die("You must be logged in to comment.");
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Invalid request.");
}

$pokemonName = $_POST['pokemon_name'] ?? '';
$commentText = trim($_POST['comment'] ?? '');
$userCaptcha = $_POST['captcha'] ?? '';

if ($pokemonName === '' || $commentText === '') {
    die("Missing required fields.");
}

if (
    !isset($_SESSION['captcha']) ||
    strtolower($userCaptcha) !== strtolower($_SESSION['captcha'])
) {
    die("Invalid CAPTCHA.");
}

$stmt = $pdo->prepare("
    INSERT INTO comments (pokemon_name, comment_text, user_id, created_at)
    VALUES (?, ?, ?, NOW())
");

$stmt->execute([
    $pokemonName,
    $commentText,
    $_SESSION['user']['user_id']
]);

unset($_SESSION['captcha']);
header("Location: pokemon.php?name=" . urlencode($pokemonName));
exit;