<?php
session_start();
require 'db.php';

$id = $_GET['id'] ?? null;
$name = $_GET['name'] ?? null;

$pokemon = null;
$apiData = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM pokemon WHERE pokemon_id = ?");
    $stmt->execute([$id]);
    $pokemon = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($pokemon) {
        $name = $pokemon['name'];
    }
}

if (!$pokemon && $name) {
    $apiData = json_decode(@file_get_contents(
        "https://pokeapi.co/api/v2/pokemon/" . urlencode($name)
    ), true);

    if (!$apiData) {
        die("Pokémon not found.");
    }
    $name = $apiData['name'];
}

if (!$pokemon && !$apiData) {
    die("Pokémon not found.");
}

$stmt = $pdo->prepare("
    SELECT comments.*, users.username 
    FROM comments
    JOIN users ON comments.user_id = users.user_id
    WHERE comments.pokemon_name = ?
    ORDER BY comments.created_at DESC
");
$stmt->execute([$name]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($pokemon['name'] ?? ucfirst($apiData['name'])) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="pokemon-page">
    <div class="pokemon-card-detail">
        <a href="index.php" class="home-button">⬅ Back</a>
        <h1><?= htmlspecialchars($pokemon['name'] ?? ucfirst($apiData['name'])) ?></h1>
        <?php if (!empty($pokemon['image'])): ?>
            <img src="uploads/<?= htmlspecialchars($pokemon['image']) ?>">
        <?php elseif (!empty($apiData['sprites']['front_default'])): ?>
            <img src="<?= htmlspecialchars($apiData['sprites']['front_default']) ?>">
        <?php endif; ?>

        <div class="types">
            <?php if ($apiData): ?>
                <?php foreach ($apiData['types'] as $t): ?>
                    <span class="type <?= htmlspecialchars($t['type']['name']) ?>">
                        <?= htmlspecialchars($t['type']['name']) ?>
                    </span>
                <?php endforeach; ?>
            <?php else: ?>
                <span class="type <?= htmlspecialchars($pokemon['type'] ?? '') ?>">
                    <?= htmlspecialchars($pokemon['type'] ?? '-') ?>
                </span>
            <?php endif; ?>
        </div>
        <div class="stats">
            <?php if ($apiData): ?>
                <?php foreach ($apiData['stats'] as $stat): ?>
                    <p><?= htmlspecialchars($stat['stat']['name']) ?>: <?= $stat['base_stat'] ?></p>
                <?php endforeach; ?>
            <?php else: ?>
                <p>HP: <?= $pokemon['hitpoints'] ?? '-' ?></p>
                <p>Attack: <?= $pokemon['attack'] ?? '-' ?></p>
                <p>Defense: <?= $pokemon['defense'] ?? '-' ?></p>
            <?php endif; ?>
        </div>

    </div>
    <div class="comments-container">

        <h2>Comments</h2>

        <?php foreach ($comments as $c): ?>
            <div class="comment">
                <strong><?= htmlspecialchars($c['username']) ?></strong>
                <p><?= htmlspecialchars($c['comment_text']) ?></p>
            </div>
        <?php endforeach; ?>
        <?php if (isset($_SESSION['user'])): ?>
            <form method="POST" action="comment.php">

                <textarea name="comment" placeholder="Write a comment..." required></textarea>
                <div class="captcha-box">
                    <img src="captcha.php" alt="captcha">
                    <input type="text" name="captcha" placeholder="Enter captcha" required>
                </div>
                <input type="hidden" name="pokemon_name" value="<?= htmlspecialchars($name) ?>">
                <button class="post-button" type="submit">Post Comment</button>
            </form>
        <?php endif; ?>
    </div>
</div>
</body>
</html>