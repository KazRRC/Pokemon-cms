<?php
require 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = trim($_POST['email']);

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    }

    if (empty($error)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, role) VALUES (?, ?, ?, 'user')");

        try {
            $stmt->execute([$username, $email, $hash]);
            header("Location: login.php");
            exit;
        } catch (PDOException $e) {
            $error = "Username or email already exists.";
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
<a href="index.php" class="home-button">⬅ Back to Home</a>
<body>
<div class="auth-container">
<h2>Register</h2>
<?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<form method="POST">
    <input type="text" name="username" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required><br>
    <input name="email" type="email" placeholder="Email" required><br>
    <button type="submit">Register</button>
</form>
<a href="login.php">Already have an account? Login</a>
</div>
</body>
</html>