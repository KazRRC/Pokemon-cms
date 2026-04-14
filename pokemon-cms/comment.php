<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$action = $_POST['action'] ?? '';
$user_id = $_SESSION['user']['user_id'];

try {
if ($action === 'add') {

    $pokemon_id = $_POST['pokemon_id'];
    $pokemon_name = $_POST['pokemon_name'];

    if (empty($pokemon_id)) {

        $name = strtolower($pokemon_name);
        $json = @file_get_contents("https://pokeapi.co/api/v2/pokemon/$name");

        if ($json) {
            $data = json_decode($json, true);

            $type = $data['types'][0]['type']['name'];
            $stmt = $pdo->prepare("SELECT type_id FROM types WHERE type_name = ?");
            $stmt->execute([$type]);
            $typeRow = $stmt->fetch();

            if (!$typeRow) {
                $pdo->prepare("INSERT INTO types (type_name) VALUES (?)")->execute([$type]);
                $type_id = $pdo->lastInsertId();
            } else {
                $type_id = $typeRow['type_id'];
            }

            $stmt = $pdo->prepare("
                INSERT INTO pokemon (name, type_id, hitpoints, attack, defense, image, created_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");

            $stmt->execute([
                ucfirst($data['name']),
                $type_id,
                $data['stats'][0]['base_stat'],
                $data['stats'][1]['base_stat'],
                $data['stats'][2]['base_stat'],
                $data['sprites']['front_default']
            ]);

            $pokemon_id = $pdo->lastInsertId();
        }
    }

    $stmt = $pdo->prepare("
        INSERT INTO comments (pokemon_id, user_id, comment_text)
        VALUES (?, ?, ?)
    ");

    $stmt->execute([
        $pokemon_id,
        $user_id,
        trim($_POST['comment'])
    ]);

    header("Location: pokemon.php?id=" . $pokemon_id);
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