<?php
session_start();
require 'db.php';

$q = $_GET['q'] ?? '';
$type = $_GET['type'] ?? 'all';

$sql = "SELECT * FROM pokemon WHERE name LIKE ?";
$params = ["%$q%"];

if ($type !== 'all') {
    $sql .= " AND type = ?";
    $params[] = $type;
}

$sql .= " ORDER BY name ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$customPokemon = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Poké Fans CMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Poké Fans CMS</h1>

    <nav>
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
            <a href="admin.php">Admin Panel</a>
        <?php endif; ?>

        <?php if (isset($_SESSION['user'])): ?>
            <span>Welcome <?= htmlspecialchars($_SESSION['user']['username']) ?></span>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
</header>
<main class="pokedex">
    <div class="pokedex-header">
        <h1>Pokédex</h1>
<form method="GET">
    <input 
        type="text" 
        name="q" 
        id="search"
        placeholder="Search Pokémon..." 
        value="<?= htmlspecialchars($q) ?>"
    >
        </div>
    <select name="type">
        <option value="all">All Types</option>
        <option value="fire" <?= $type=="fire"?'selected':'' ?>>Fire</option>
        <option value="water" <?= $type=="water"?'selected':'' ?>>Water</option>
        <option value="grass" <?= $type=="grass"?'selected':'' ?>>Grass</option>
        <option value="electric" <?= $type=="electric"?'selected':'' ?>>Electric</option>
        <option value="normal" <?= $type=="normal"?'selected':'' ?>>Normal</option>
        <option value="psychic" <?= $type=="psychic"?'selected':'' ?>>Psychic</option>
        <option value="dragon" <?= $type=="dragon"?'selected':'' ?>>Dragon</option>
    </select>

    <button type="submit">Search</button>
</form>
<div id="pokemon-container"></div>
<h2>Custom Pokémon</h2>

<div class="pokemon-grid">
<?php if (!empty($customPokemon)): ?>
    <?php foreach ($customPokemon as $p): ?>
        <div class="card">
            <a href="pokemon.php?id=<?= $p['pokemon_id'] ?>" style="text-decoration:none; color:black;">
                <strong><?= htmlspecialchars($p['name']) ?></strong>
                <?php if (!empty($p['image']) && file_exists("uploads/" . $p['image'])): ?>
                    <img src="uploads/<?= htmlspecialchars($p['image']) ?>">
                <?php endif; ?>

            </a>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No custom Pokémon found.</p>
<?php endif; ?>
</div>
</main>
<script src="api.js"></script>
<script>
const searchInput = document.getElementById("search");

searchInput.addEventListener("input", function() {
    const query = this.value;
    const params = new URLSearchParams(window.location.search);
    params.set("q", query);
    window.history.replaceState({}, "", "?" + params.toString());

});
</script>
<script>
const selectedType = "<?= $type ?>";
</script>
</body>
</html>