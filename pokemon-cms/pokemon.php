<?php
session_start();
require 'db.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$pokemon = null;

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("
        SELECT p.*, t.type_name 
        FROM pokemon p
        LEFT JOIN types t ON p.type_id = t.type_id
        WHERE LOWER(p.name) = LOWER(?)
    ");
    $stmt->execute([$_GET['id']]);
    $pokemon = $stmt->fetch();
    if (!$pokemon) {
        $pokemon = null;
}

} elseif (isset($_GET['name'])) {
    $stmt = $pdo->prepare("
        SELECT p.*, t.type_name 
        FROM pokemon p
        JOIN types t ON p.type_id = t.type_id
        WHERE LOWER(p.name) = LOWER(?)
    ");
    $stmt->execute([$_GET['name']]);
    $pokemon = $stmt->fetch();
    if (!$pokemon) {
        $pokemon = null;
    }
    }

if (!$pokemon && isset($_GET['name'])) {

    $name = strtolower($_GET['name']);
    $json = @file_get_contents("https://pokeapi.co/api/v2/pokemon/$name");

    if ($json) {
        $data = json_decode($json, true);
        $pokemon = [
            'name' => ucfirst($data['name']),
            'type_name' => $data['types'][0]['type']['name'],
            'hitpoints' => $data['stats'][0]['base_stat'],
            'attack' => $data['stats'][1]['base_stat'],
            'defense' => $data['stats'][2]['base_stat'],
            'image' => $data['sprites']['front_default'],
            'pokemon_id' => null 
        ];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($pokemon['name'] ?? 'Pokemon') ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<header>
    <a href="index.php" class="home-button">⬅ Back to Home</a>

    <form action="pokemon.php" method="GET" style="display:inline;">
        <input type="text" name="name" placeholder="Search Pokémon..." required>
        <button type="submit">Search</button>
    </form>
</header>
<body>
    <main>
<div class="pokemon-card">
    <h1><?= htmlspecialchars($pokemon['name'] ?? '') ?></h1>
<?php if (!empty($pokemon['image'])): ?>
    <img src="<?= 
        (strpos($pokemon['image'], 'http') === 0) 
        ? $pokemon['image'] 
        : 'uploads/' . $pokemon['image'] 
    ?>" style="max-width:300px;">
<?php endif; ?>
    <div class="type type-<?= strtolower($pokemon['type_name']) ?>">
        <?= htmlspecialchars($pokemon['type_name'] ?? 'Unknown') ?>
    </div>
    <div class="stat">
        <div class="stat-label">HP</div>
        <div class="stat-bar">
            <div class="stat-fill" style="width: <?= $pokemon['hitpoints'] ?>%"></div>
        </div>
    </div>
    <div class="stat">
        <div class="stat-label">Attack</div>
        <div class="stat-bar">
            <div class="stat-fill" style="width: <?= $pokemon['attack'] ?>%"></div>
        </div>
    </div>
    <div class="stat">
        <div class="stat-label">Defense</div>
        <div class="stat-bar">
            <div class="stat-fill" style="width: <?= $pokemon['defense'] ?>%"></div>
        </div>
    </div>
    <p><?= htmlspecialchars($pokemon['description'] ?? '') ?></p>
</div>
    <div class="comments-container">
        <h2>Comments</h2>

        <?php
        if (!empty($pokemon) && !empty($pokemon['pokemon_id'])) {
            $stmt = $pdo->prepare("
                SELECT c.*, u.username 
                FROM comments c
                JOIN users u ON c.user_id = u.user_id
                WHERE c.pokemon_id = ?
                ORDER BY c.created_at DESC
            ");
            $stmt->execute([$pokemon['pokemon_id']]);
            $comments = $stmt->fetchAll();
        } else {
            $comments = [];
        }
        ?>

        <?php if ($comments): ?>
        <?php foreach ($comments as $c): ?>
                <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
                    <strong><?= htmlspecialchars($c['username']) ?></strong><br>

                    <?php
                    $isEditing = isset($_GET['edit']) && $_GET['edit'] == $c['comment_id'];
                    ?>

                    <?php if ($isEditing): ?>
                        <form action="comment.php" method="POST">
                            <input type="hidden" name="action" value="edit">
                            <input type="hidden" name="comment_id" value="<?= $c['comment_id'] ?>">
                            <input type="hidden" name="pokemon_id" value="<?= $pokemon['pokemon_id'] ?>">
                            <input type="text" name="comment" value="<?= htmlspecialchars($c['comment_text']) ?>">
                            <div class="comment-actions">
                                <button type="submit">Save</button>
                            </div>
                            <a href="pokemon.php?id=<?= $pokemon['pokemon_id'] ?>">Cancel</a>
                        </form>
                    <?php else: ?>
                        <p><?= htmlspecialchars($c['comment_text']) ?></p>
                        <?php if (isset($_SESSION['user']) && $_SESSION['user']['user_id'] == $c['user_id']): ?>
                            <a href="pokemon.php?id=<?= $pokemon['pokemon_id'] ?>&edit=<?= $c['comment_id'] ?>">
                                Edit
                            </a>
                            <form action="comment.php" method="POST" style="display:inline;">
                                <input type="hidden" name="pokemon_id" value="<?= $pokemon['pokemon_id'] ?>">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="comment_id" value="<?= $c['comment_id'] ?>">
                            <div class="comment-actions">
                                    <button type="submit">Delete</button>
                                </div>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
        <?php else: ?>
            <p>No comments yet.</p>
        <?php endif; ?>

        <hr>
        <h3>Add Comment</h3>

        <?php if (isset($_SESSION['user'])): ?>
            <?php if (!empty($_SESSION['captcha_error'])): ?>
                <p style="color:red;">
                    <?= $_SESSION['captcha_error'] ?>
                </p>
                <?php unset($_SESSION['captcha_error']); ?>
            <?php endif; ?>
            <form action="comment.php" method="POST">
                <img src="captcha.php?rand=<?= time() ?>" alt="CAPTCHA">
                <br>

                <input type="text" name="captcha" placeholder="Enter CAPTCHA" required>

                <br>
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="pokemon_id" value="<?= $pokemon['pokemon_id'] ?? '' ?>">
                <input type="hidden" name="pokemon_name" value="<?= $pokemon['name'] ?>">
                <textarea name="comment" required><?= $_SESSION['old_comment'] ?? '' ?></textarea>
                <button type="submit">Post Comment</button>
            </form>
        <?php else: ?>
            <p>Login to comment.</p>
        <?php endif; ?>
        </div>
        </main>
</body>
</html>