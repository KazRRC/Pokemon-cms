<?php
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$action = $_POST['action'] ?? '';
$user_id = $_SESSION['user']['user_id'];

try {
    if ($action === 'add') {

        $stmt = $pdo->prepare("
            INSERT INTO comments (pokemon_id, user_id, comment_text)
            VALUES (?, ?, ?)
        ");

        $stmt->execute([
            $_POST['pokemon_id'],
            $user_id,
            trim($_POST['comment'])
        ]);

        header("Location: pokemon.php?name=" . urlencode($_POST['pokemon_name']));
        exit;
    }

    if ($action === 'edit') {

        $stmt = $pdo->prepare("
            UPDATE comments 
            SET comment_text = ?
            WHERE comment_id = ? AND user_id = ?
        ");

        $stmt->execute([
            trim($_POST['comment']),
            $_POST['comment_id'],
            $user_id
        ]);

        header("Location: pokemon.php?id=" . $_POST['pokemon_id']);
        exit;
    }

    if ($action === 'delete') {

        $stmt = $pdo->prepare("
            DELETE FROM comments 
            WHERE comment_id = ? AND user_id = ?
        ");

        $stmt->execute([
            $_POST['comment_id'],
            $user_id
        ]);

    header("Location: pokemon.php?id=" . $_POST['pokemon_id']);
    exit;
    }

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}