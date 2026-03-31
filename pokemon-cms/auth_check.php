<?php
include 'db.php';

if(!isset($_SESSION['user'])){
    header("Location: pokemon.php?" . http_build_query($_GET));
    exit;
}

if($_POST){
    $stmt=$pdo->prepare("SELECT * FROM users WHERE username=?");
    $stmt->execute([$_POST['username']]);
    $user=$stmt->fetch();

    if($user && password_verify($_POST['password'],$user['password_hash'])){
        $_SESSION['user']=$user;
        header("Location: index.php");
    }
}
?>
<form method="POST">
<input name="username">
<input name="password" type="password">
<button>Login</button>
</form>