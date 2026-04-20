<?php
session_start();
require 'db.php';

$q = $_GET['q'] ?? '';
$category = $_GET['category'] ?? 'all';
$page = max(1, (int)($_GET['page'] ?? 1));

$limit = 6;
$offset = ($page - 1) * $limit;
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
$sql = "SELECT DISTINCT p.* FROM pages p
        LEFT JOIN page_categories pc ON p.id = pc.page_id
        LEFT JOIN categories c ON pc.category_id = c.id
        WHERE p.title LIKE ?";

$params = ["%$q%"];

if ($category !== "all") {
    $sql .= " AND c.id = ?";
    $params[] = $category;
}

$sql .= " LIMIT $limit OFFSET $offset";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$pages = $stmt->fetchAll();
$countSql = "SELECT COUNT(DISTINCT p.id) FROM pages p
             LEFT JOIN page_categories pc ON p.id = pc.page_id
             LEFT JOIN categories c ON pc.category_id = c.id
             WHERE p.title LIKE ?";

$countParams = ["%$q%"];

if ($category !== "all") {
    $countSql .= " AND c.id = ?";
    $countParams[] = $category;
}

$countStmt = $pdo->prepare($countSql);
$countStmt->execute($countParams);
$total = $countStmt->fetchColumn();

$totalPages = ceil($total / $limit);
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
<main>

<h2>Search Pages</h2>

<form method="GET">
    <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Search...">

    <select name="category">
        <option value="all">All Categories</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= ($category == $cat['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Search</button>
</form>

<hr>
<?php if (empty($pages)): ?>
    <p>No results found.</p>
<?php else: ?>
    <?php foreach ($pages as $p): ?>
        <div class="card">
            <h3><?= htmlspecialchars($p['title']) ?></h3>
            <div><?= $p['content'] ?></div>
            <a href="page.php?id=<?= $p['id'] ?>">View Page</a>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
<hr>
<?php if ($totalPages > 1): ?>

    <?php if ($page > 1): ?>
        <a href="?q=<?= urlencode($q) ?>&category=<?= $category ?>&page=<?= $page-1 ?>">Prev</a>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?q=<?= urlencode($q) ?>&category=<?= $category ?>&page=<?= $i ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>

    <?php if ($page < $totalPages): ?>
        <a href="?q=<?= urlencode($q) ?>&category=<?= $category ?>&page=<?= $page+1 ?>">Next</a>
    <?php endif; ?>

<?php endif; ?>

</main>
</body>
</html>