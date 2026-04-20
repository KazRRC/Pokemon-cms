<?php
require 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);

    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user'] = $user;
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <a href="index.php" class="home-button">⬅ Back to Home</a>
<div class="auth-container">
<h2>Login</h2>
<?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<?php if (!isset($_SESSION['user'])): ?>
<form method="POST">
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Login</button>
</form>
<?php else: ?>
    <p>
        Welcome, <?= htmlspecialchars($_SESSION['user']['username']) ?>!
    </p>
    <a href="logout.php">
        <button>Logout</button>
    </a>
    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
        <a href="admin.php">
            <button>Admin Panel</button>
        </a>
    <?php endif; ?>
<?php endif; ?>
<a href="register.php">Create account</a>
</div>
</body>
</html>