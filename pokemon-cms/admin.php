<?php
require 'db.php';
require 'auth_check.php';
if ($_SESSION['user']['role'] !== 'admin') {
    die("Access denied. Admins only.");
}

if (isset($_POST['create_pokemon'])) {
    $stmt = $pdo->prepare("INSERT INTO pokemon (name, hitpoints, attack, defense) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $_POST['name'],
        $_POST['hp'],
        $_POST['atk'],
        $_POST['def']
    ]);
}

if (isset($_POST['delete_pokemon'])) {
    $stmt = $pdo->prepare("DELETE FROM pokemon WHERE pokemon_id = ?");
    $stmt->execute([$_POST['pokemon_id']]);
}

$editPokemon = null;
if (isset($_GET['edit_pokemon'])) {
    $stmt = $pdo->prepare("SELECT * FROM pokemon WHERE pokemon_id = ?");
    $stmt->execute([$_GET['edit_pokemon']]);
    $editPokemon = $stmt->fetch();
}

if (isset($_POST['update_pokemon'])) {
    $stmt = $pdo->prepare("UPDATE pokemon SET name=?, hitpoints=?, attack=?, defense=? WHERE pokemon_id=?");
    $stmt->execute([
        $_POST['name'],
        $_POST['hp'],
        $_POST['atk'],
        $_POST['def'],
        $_POST['pokemon_id']
    ]);
}

if (isset($_POST['create_user'])) {
    $hashed = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, role) VALUES (?, ?, ?)");
    $stmt->execute([
        $_POST['username'],
        $hashed,
        $_POST['role']
    ]);
}

if (isset($_POST['delete_user'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->execute([$_POST['user_id']]);
}

$editUser = null;
if (isset($_GET['edit_user'])) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$_GET['edit_user']]);
    $editUser = $stmt->fetch();
}

if (isset($_POST['update_user'])) {
    $sql = "UPDATE users SET username=?, role=?";
    $params = [$_POST['username'], $_POST['role']];
    if (!empty($_POST['password'])) {
        $sql .= ", password_hash=?";
        $params[] = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }

    $sql .= " WHERE user_id=?";
    $params[] = $_POST['user_id'];

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
}
?>

<h1>Admin Dashboard</h1>
<h2>Manage Pokémon</h2>

<form method="POST">
    <input name="name" placeholder="Name" value="<?= $editPokemon['name'] ?? '' ?>" required>
    <input name="hp" placeholder="HP" value="<?= $editPokemon['hitpoints'] ?? '' ?>" required>
    <input name="atk" placeholder="Attack" value="<?= $editPokemon['attack'] ?? '' ?>" required>
    <input name="def" placeholder="Defense" value="<?= $editPokemon['defense'] ?? '' ?>" required>

    <?php if ($editPokemon): ?>
        <input type="hidden" name="pokemon_id" value="<?= $editPokemon['pokemon_id'] ?>">
        <button name="update_pokemon">Update</button>
        <a href="admin.php">Cancel</a>
    <?php else: ?>
        <button name="create_pokemon">Create</button>
    <?php endif; ?>
</form>
<?php foreach ($pdo->query("SELECT * FROM pokemon") as $p): ?>
    <div>
        <?= htmlspecialchars($p['name']) ?>
        <a href="?edit_pokemon=<?= $p['pokemon_id'] ?>">Edit</a>
        <form method="POST" style="display:inline;">
            <input type="hidden" name="pokemon_id" value="<?= $p['pokemon_id'] ?>">
            <button name="delete_pokemon">Delete</button>
        </form>
    </div>
<?php endforeach; ?>
<h2>Manage Users</h2>
<form method="POST">
    <input name="username" placeholder="Username" value="<?= $editUser['username'] ?? '' ?>" required>
    <input name="password" placeholder="Password (leave blank to keep same)">
    
    <select name="role">
        <option value="user" <?= (isset($editUser) && $editUser['role'] === 'user') ? 'selected' : '' ?>>User</option>
        <option value="admin" <?= (isset($editUser) && $editUser['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
    </select>

    <?php if ($editUser): ?>
        <input type="hidden" name="user_id" value="<?= $editUser['user_id'] ?>">
        <button name="update_user">Update User</button>
        <a href="admin.php">Cancel</a>
    <?php else: ?>
        <button name="create_user">Create User</button>
    <?php endif; ?>
</form>

<?php foreach ($pdo->query("SELECT * FROM users") as $u): ?>
    <div>
        <?= htmlspecialchars($u['username']) ?> (<?= $u['role'] ?>)

        <a href="?edit_user=<?= $u['user_id'] ?>">Edit</a>

        <form method="POST" style="display:inline;">
            <input type="hidden" name="user_id" value="<?= $u['user_id'] ?>">
            <button name="delete_user">Delete</button>
        </form>
    </div>
<?php endforeach; ?>