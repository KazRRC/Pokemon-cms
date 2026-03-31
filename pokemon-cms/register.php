<?php
require 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "All fields required.";
    } else {
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->fetch()) {
            $error = "Username already taken.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("
                INSERT INTO users (username, password_hash, role, created_at)
                VALUES (?, ?, 'user', NOW())
            ");

            $stmt->execute([$username, $hash]);

            header("Location: login.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h2>Register</h2>
<?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<form method="POST">
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Register</button>
</form>
<a href="login.php">Already have an account? Login</a>
</body>
</html>