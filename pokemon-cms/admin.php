<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("Access denied");
}

if (isset($_POST['add_pokemon'])) {

    $name = trim($_POST['name']);
    $hitpoints = $_POST['hitpoints'] ?? null;
    $attack = $_POST['attack'] ?? null;
    $defense = $_POST['defense'] ?? null;
    $type = $_POST['type'] ?? null;
    $description = $_POST['description'] ?? null;
    $ability = $_POST['ability'] ?? null;

    $imageName = null;

    if (!empty($_FILES['image']['name'])) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = time() . "_" . uniqid() . "." . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $imageName);
    }

    if ($name !== "") {
        $stmt = $pdo->prepare("
            INSERT INTO pokemon 
            (name, hitpoints, attack, defense, type, image, description, ability, is_custom)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)
        ");

        $stmt->execute([
            $name, $hitpoints, $attack, $defense,
            $type, $imageName, $description, $ability
        ]);
    }
}

if (isset($_POST['update_pokemon'])) {

    $id = $_POST['pokemon_id'];
    $name = trim($_POST['name']);
    $hitpoints = $_POST['hitpoints'] ?? null;
    $attack = $_POST['attack'] ?? null;
    $defense = $_POST['defense'] ?? null;
    $type = $_POST['type'] ?? null;
    $description = $_POST['description'] ?? null;
    $ability = $_POST['ability'] ?? null;

    $pdo->prepare("
        UPDATE pokemon 
        SET name=?, hitpoints=?, attack=?, defense=?, type=?, description=?, ability=?
        WHERE pokemon_id=?
    ")->execute([
        $name, $hitpoints, $attack, $defense,
        $type, $description, $ability, $id
    ]);
}

if (isset($_POST['update_image'])) {

    $id = $_POST['pokemon_id'];

    if (!empty($_FILES['image']['name'])) {

        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $imageName = time() . "_" . uniqid() . "." . $ext;
        $targetPath = "uploads/" . $imageName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $old = $pdo->prepare("SELECT image FROM pokemon WHERE pokemon_id=?");
            $old->execute([$id]);
            $oldImg = $old->fetchColumn();

            if ($oldImg && file_exists("uploads/" . $oldImg)) {
                unlink("uploads/" . $oldImg);
            }

            $pdo->prepare("UPDATE pokemon SET image=? WHERE pokemon_id=?")
                ->execute([$imageName, $id]);
        }
    }
}

if (isset($_POST['delete_pokemon'])) {
    $id = $_POST['pokemon_id'];
    $stmt = $pdo->prepare("SELECT image FROM pokemon WHERE pokemon_id=?");
    $stmt->execute([$id]);
    $img = $stmt->fetchColumn();

    if ($img && file_exists("uploads/" . $img)) {
        unlink("uploads/" . $img);
    }

    $pdo->prepare("DELETE FROM pokemon WHERE pokemon_id=?")->execute([$id]);
}

$customPokemon = $pdo->query("
    SELECT * FROM pokemon 
    WHERE is_custom = 1 OR is_custom IS NULL
    ORDER BY name ASC
")->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['delete_user'])) {
    $id = $_POST['user_id'];

    $stmt = $pdo->prepare("SELECT role FROM users WHERE user_id=?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();

    if ($user && $user['role'] !== 'admin') {
        $pdo->prepare("DELETE FROM users WHERE user_id=?")->execute([$id]);
    }
}

if (isset($_POST['edit_user'])) {
    $id = $_POST['user_id'];
    $username = trim($_POST['username']);
    // sanitizes whitespace
    $pdo->prepare("UPDATE users SET username=? WHERE user_id=?")
        ->execute([$username, $id]);
}

if (isset($_POST['delete_comment'])) {
    $id = $_POST['comment_id'];

    $pdo->prepare("DELETE FROM comments WHERE comment_id=?")
        ->execute([$id]);
}

$pokemon = $pdo->query("SELECT * FROM pokemon ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$users = $pdo->query("SELECT * FROM users ORDER BY user_id DESC")->fetchAll(PDO::FETCH_ASSOC);

$comments = $pdo->query("
    SELECT comments.*, users.username 
    FROM comments
    LEFT JOIN users ON comments.user_id = users.user_id
    ORDER BY comments.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style>
        body { font-family: Arial; }
        .section { margin-bottom: 40px; }
        .card { border:1px solid #ccc; padding:10px; margin:10px 0; }
        img { max-width:100px; display:block; }
    </style>
</head>
<body>

<h1>Admin Panel</h1>
<a href="index.php" class="home-button">⬅ Back</a>
<div class="section">
<h2>Add Pokémon</h2>
<form method="POST" enctype="multipart/form-data">
    <input name="name" placeholder="Name" required><br>
    <input type="number" name="hitpoints" placeholder="HP"><br>
    <input type="number" name="attack" placeholder="Attack"><br>
    <input type="number" name="defense" placeholder="Defense"><br> <!-- constrainted to enforce correct datatypes-->
    <textarea name="description" placeholder="Description"></textarea><br>
    <select name="type">
        <option value="">Select Type</option>
        <option value="fire">Fire</option>
        <option value="water">Water</option>
        <option value="grass">Grass</option>
        <option value="electric">Electric</option>
        <option value="normal">Normal</option>
        <option value="psychic">Psychic</option>
        <option value="dragon">Dragon</option>
    </select><br><br>
    <input type="file" name="image" accept="image/*"><br><br>
    <button name="add_pokemon">Add Pokémon</button>
</form>
<h2>Custom Pokémon</h2>

<?php foreach ($customPokemon as $p): ?>
<div class="card">

<form method="POST" enctype="multipart/form-data">

    <input type="hidden" name="pokemon_id" value="<?= $p['pokemon_id'] ?>">

    <input name="name" value="<?= htmlspecialchars($p['name']) ?>" required><br>

    <input type="number" name="hitpoints" value="<?= $p['hitpoints'] ?>" placeholder="HP"><br>
    <input type="number" name="attack" value="<?= $p['attack'] ?>" placeholder="Attack"><br>
    <input type="number" name="defense" value="<?= $p['defense'] ?>" placeholder="Defense"><br>

    <input type="text" name="ability" value="<?= htmlspecialchars($p['ability'] ?? '') ?>" placeholder="Ability"><br>

    <textarea name="description"><?= htmlspecialchars($p['description'] ?? '') ?></textarea><br>

    <select name="type">
        <option value="">Select Type</option>
        <option value="fire" <?= $p['type']=="fire"?'selected':'' ?>>Fire</option>
        <option value="water" <?= $p['type']=="water"?'selected':'' ?>>Water</option>
        <option value="grass" <?= $p['type']=="grass"?'selected':'' ?>>Grass</option>
        <option value="electric" <?= $p['type']=="electric"?'selected':'' ?>>Electric</option>
        <option value="normal" <?= $p['type']=="normal"?'selected':'' ?>>Normal</option>
        <option value="psychic" <?= $p['type']=="psychic"?'selected':'' ?>>Psychic</option>
        <option value="dragon" <?= $p['type']=="dragon"?'selected':'' ?>>Dragon</option>
    </select><br><br>

    <?php if (!empty($p['image']) && file_exists("uploads/" . $p['image'])): ?>
        <img src="uploads/<?= htmlspecialchars($p['image']) ?>">
    <?php else: ?>
        <p>No image</p>
    <?php endif; ?>

    <input type="file" name="image"><br><br>

    <button name="update_pokemon">Save Changes</button>

</form>

<form method="POST">
    <input type="hidden" name="pokemon_id" value="<?= $p['pokemon_id'] ?>">
    <button name="delete_pokemon" onclick="return confirm('Delete this Pokémon?')">
        Delete
    </button>
</form>

</div>
<?php endforeach; ?>
</div>
</div>
<div class="section">
<h2>Manage Users</h2>

<?php foreach ($users as $u): ?>
    <div class="card">
        <strong><?= htmlspecialchars($u['username']) ?></strong> (<?= $u['role'] ?>)<br>
        <!--sanitized with htmlspecialchars to prevent script injection-->
        <form method="POST" style="display:inline;">
            <input type="hidden" name="user_id" value="<?= $u['user_id'] ?>">
            <input name="username" value="<?= htmlspecialchars($u['username']) ?>">
            <button name="edit_user">Save</button>
        </form>
        <?php if ($u['role'] !== 'admin'): ?>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="user_id" value="<?= $u['user_id'] ?>">
                <button name="delete_user" onclick="return confirm('Delete this user?')">Delete</button>
            </form>
        <?php endif; ?>
    </div>
<?php endforeach; ?>
</div>
<div class="section">
<h2>All Comments</h2>

<?php foreach ($comments as $c): ?>
    <div class="card">
        <strong><?= htmlspecialchars($c['username'] ?? 'User') ?></strong><br>
        <?= htmlspecialchars($c['comment_text']) ?><br><br>

        <form method="POST">
            <input type="hidden" name="comment_id" value="<?= $c['comment_id'] ?>">
            <button name="delete_comment" onclick="return confirm('Delete comment?')">Delete</button>
        </form>
    </div>
<?php endforeach; ?>
</div>

<script src="https://cdn.tiny.cloud/1/jnn1k337zusgomp29w8oqrtuvjip37fc1aqy8pccd5qv4ydh/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>

<script>
  tinymce.init({
    selector: 'textarea',
    plugins: [
      'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
      'checklist', 'mediaembed', 'casechange', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'advtemplate', 'tinymceai', 'uploadcare', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown','importword', 'exportword', 'exportpdf'
    ],
    toolbar: 'undo redo | tinymceai-chat tinymceai-quickactions tinymceai-review | blocks fontfamily fontsize | bold italic underline strikethrough | link media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography uploadcare | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
    tinycomments_mode: 'embedded',
    tinycomments_author: 'Author name',
    mergetags_list: [
      { value: 'First.Name', title: 'First Name' },
      { value: 'Email', title: 'Email' },
    ],
    tinymceai_token_provider: async () => {
      await fetch(`https://demo.api.tiny.cloud/1/jnn1k337zusgomp29w8oqrtuvjip37fc1aqy8pccd5qv4ydh/auth/random`, { method: "POST", credentials: "include" });
      return { token: await fetch(`https://demo.api.tiny.cloud/1/jnn1k337zusgomp29w8oqrtuvjip37fc1aqy8pccd5qv4ydh/jwt/tinymceai`, { credentials: "include" }).then(r => r.text()) };
    },
    uploadcare_public_key: '4ef66d22160488668390',
  });
</script>
<textarea>
  Welcome to TinyMCE!
</textarea>
</body>
</html>