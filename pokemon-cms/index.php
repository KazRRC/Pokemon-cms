<?php
require 'db.php';
$json = file_get_contents("https://pokeapi.co/api/v2/pokemon?limit=151");
$data = json_decode($json, true);
$pokemonList = $data['results'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Poké Fans CMS</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Poké Fans Pokémon Encyclopedia</h1>
        <nav>
        <?php if (isset($_SESSION['user'])): ?>
            <span>Welcome <?= htmlspecialchars($_SESSION['user']['username']) ?></span>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
        </nav>
</header>
<main>
<section>
    <h2>Search Pokémon</h2>
    <form action="pokemon.php" method="GET">
        <input type="text" name="name" placeholder="Enter Pokémon name..." required>
        <button type="submit">Search</button>
    </form>
</section>
<section>
    <div class="layout">
        <aside class="sidebar">
            <h3>Search by type</h3>
                <ul id="type-list">
                    <li class="type all" onclick="loadAllPokemon()">All</li>
                    <li class="type fire" onclick="filterByType('fire')">Fire</li>
                    <li class="type water" onclick="filterByType('water')">Water</li>
                    <li class="type grass" onclick="filterByType('grass')">Grass</li>
                    <li class="type electric" onclick="filterByType('electric')">Electric</li>
                    <li class="type psychic" onclick="filterByType('psychic')">Psychic</li>
                    <li class="type ice" onclick="filterByType('ice')">Ice</li>
                    <li class="type dragon" onclick="filterByType('dragon')">Dragon</li>
                    <li class="type dark" onclick="filterByType('dark')">Dark</li>
                    <li class="type fairy" onclick="filterByType('fairy')">Fairy</li>
                </ul>
        </aside>
    </div>
</section>
</main>
    <div id="pokemon-container"></div>
    <script src="api.js"></script>
</body>
</html>