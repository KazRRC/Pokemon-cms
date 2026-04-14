<?php
session_start();
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
         <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
        <a href="admin.php" class="admin-link">Admin Panel</a>
        </a>
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
<main>
<section>
    <h2>Search Pokémon</h2>
        <input type="text" id="search" name="name" placeholder="Enter Pokémon name...">
        <button type="submit">Search</button>
        <div id="search-results"></div>
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
                    <li class="type steel" onclick="filterByType('steel')">Steel</li>
                    <li class="type normal" onclick="filterByType('normal')">Normal</li>
                    <li class="type bug" onclick="filterByType('bug')">Bug</li>
                </ul>
        </aside>
    </div>
</section>
</main>
    <div id="pokemon-container"></div>
    <script src="api.js"></script>
</body>
</html>