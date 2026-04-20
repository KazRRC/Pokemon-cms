<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';

if(!isset($_SESSION['user'])){
    header("Location: pokemon.php?" . http_build_query($_GET));
    exit;
}

if($_POST){
    $stmt=$pdo->prepare("SELECT * FROM users WHERE username=?");
    $user=$stmt->fetch();

    if($user && password_verify($_POST['password'],$user['password_hash'])){
        $_SESSION['user']=$user;
        header("Location: index.php");
    }
}
?>
