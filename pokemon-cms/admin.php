<?php include 'db.php';
include 'auth_check.php';

if($_SESSION['user']['role'] != 'admin'){
    die("No access");
}

if(isset($_POST['create'])){
    $stmt=$pdo->prepare("INSERT INTO pokemon (name,hitpoints,attack,defense) VALUES (?,?,?,?)");
    $stmt->execute([$_POST['name'],$_POST['hp'],$_POST['atk'],$_POST['def']]);
}

if(isset($_GET['delete'])){
    $pdo->prepare("DELETE FROM pokemon WHERE pokemon_id=?")->execute([$_GET['delete']]);
}

$editPokemon = null;
if(isset($_GET['edit'])){
    $stmt=$pdo->prepare("SELECT * FROM pokemon WHERE pokemon_id=?");
    $stmt->execute([$_GET['edit']]);
    $editPokemon = $stmt->fetch();
}

if(isset($_POST['update'])){
    $stmt=$pdo->prepare("UPDATE pokemon SET name=?, hitpoints=?, attack=?, defense=? WHERE pokemon_id=?");
    $stmt->execute([
        $_POST['name'],
        $_POST['hp'],
        $_POST['atk'],
        $_POST['def'],
        $_POST['pokemon_id']
    ]);
}
?>

<h2>Admin Dashboard</h2>
<form method="POST">
    <input name="name" placeholder="Name" value="<?= $editPokemon['name'] ?? '' ?>">
    <input name="hp" placeholder="HP" value="<?= $editPokemon['hitpoints'] ?? '' ?>">
    <input name="atk" placeholder="Attack" value="<?= $editPokemon['attack'] ?? '' ?>">
    <input name="def" placeholder="Defense" value="<?= $editPokemon['defense'] ?? '' ?>">

    <?php if($editPokemon): ?>
        <input type="hidden" name="pokemon_id" value="<?= $editPokemon['pokemon_id'] ?>">
        <button name="update">Update Pokémon</button>
        <a href="admin.php">Cancel</a>
    <?php else: ?>
        <button name="create">Add Pokémon</button>
    <?php endif; ?>
</form>

<?php
foreach($pdo->query("SELECT * FROM pokemon") as $p){
    echo "{$p['name']} 
        <a href='?edit={$p['pokemon_id']}'>Edit</a> 
        <a href='?delete={$p['pokemon_id']}'>Delete</a><br>";
}
?>

<h2>Admin Dashboard</h2>

<form method="POST">
<input name="name" placeholder="Name">
<input name="hp" placeholder="HP">
<input name="atk" placeholder="Attack">
<input name="def" placeholder="Defense">
<button name="create">Add Pokémon</button>
</form>

<?php
foreach($pdo->query("SELECT * FROM pokemon") as $p){
    echo "{$p['name']} <a href='?delete={$p['pokemon_id']}'>Delete</a><br>";
}
?>
