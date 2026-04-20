<?php
session_start();
require 'db.php';

if ($_SESSION['user']['role'] !== 'admin') die("Unauthorized");

if ($_POST) {
    $pdo->prepare("INSERT INTO categories (name) VALUES (?)")
        ->execute([$_POST['name']]);
}

$cats = $pdo->query("SELECT * FROM categories")->fetchAll();
?>

<h2>Categories</h2>

<form method="POST">
    <input name="name" placeholder="New Category">
    <button>Add</button>
</form>

<ul>
<?php foreach ($cats as $c): ?>
    <li><?= htmlspecialchars($c['name']) ?></li>
<?php endforeach; ?>
</ul>