<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    die("Access denied");
}

if (isset($_POST['save_page'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $categories = $_POST['categories'] ?? [];

    if (!empty($_POST['page_id'])) {

        $pageId = $_POST['page_id'];
        $stmt = $pdo->prepare("UPDATE pages SET title=?, content=? WHERE id=?");
        $stmt->execute([$title, $content, $pageId]);
        $pdo->prepare("DELETE FROM page_categories WHERE page_id=?")
            ->execute([$pageId]);

    } else {
        $stmt = $pdo->prepare("INSERT INTO pages (title, content) VALUES (?, ?)");
        $stmt->execute([$title, $content]);

        $pageId = $pdo->lastInsertId();
    }

    foreach ($categories as $catId) {
        $pdo->prepare("INSERT INTO page_categories (page_id, category_id) VALUES (?, ?)")
            ->execute([$pageId, $catId]);
    }
}

if (isset($_POST['add_category'])) {
    $name = trim($_POST['category_name']);

    if ($name !== "") {
        $pdo->prepare("INSERT INTO categories (name) VALUES (?)")
            ->execute([$name]);
    }
}

if (isset($_GET['approve'])) {
    $pdo->prepare("UPDATE comments SET approved=1 WHERE id=?")
        ->execute([$_GET['approve']]);
}

if (isset($_GET['delete_comment'])) {
    $pdo->prepare("DELETE FROM comments WHERE id=?")
        ->execute([$_GET['delete_comment']]);
}

$pages = $pdo->query("SELECT * FROM pages ORDER BY created_at DESC")->fetchAll();
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
$comments = $pdo->query("SELECT * FROM comments WHERE approved=0")->fetchAll();
$editPage = null;
$editCategories = [];

if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM pages WHERE id=?");
    $stmt->execute([$_GET['edit']]);
    $editPage = $stmt->fetch();

    $stmt = $pdo->prepare("SELECT category_id FROM page_categories WHERE page_id=?");
    $stmt->execute([$_GET['edit']]);
    $editCategories = array_column($stmt->fetchAll(), 'category_id');
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <style>
        body { font-family: Arial; }
        .section { margin-bottom: 40px; }
        .card { border:1px solid #ccc; padding:10px; margin:10px 0; }
    </style>
</head>
<body>
<h1>Admin Panel</h1>
<div class="section">
<h2><?= $editPage ? "Edit Page" : "Create Page" ?></h2>

<form method="POST">
    <input type="hidden" name="page_id" value="<?= $editPage['id'] ?? '' ?>">

    <input name="title" placeholder="Title"
        value="<?= htmlspecialchars($editPage['title'] ?? '') ?>" required>

    <textarea id="content" name="content">
        <?= $editPage['content'] ?? '' ?>
    </textarea>

    <h4>Categories</h4>
    <select name="categories[]" multiple>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>"
                <?= in_array($cat['id'], $editCategories) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <br><br>
    <button name="save_page">Save Page</button>
</form>
</div>
<div class="section">
<h2>All Pages</h2>

<?php foreach ($pages as $p): ?>
    <div class="card">
        <strong><?= htmlspecialchars($p['title']) ?></strong><br>
        <a href="?edit=<?= $p['id'] ?>">Edit</a>
    </div>
<?php endforeach; ?>
</div>
<div class="section">
<h2>Manage Categories</h2>

<form method="POST">
    <input name="category_name" placeholder="New Category">
    <button name="add_category">Add</button>
</form>

<ul>
<?php foreach ($categories as $c): ?>
    <li><?= htmlspecialchars($c['name']) ?></li>
<?php endforeach; ?>
</ul>
</div>
<div class="section">
<h2>Pending Comments</h2>

<?php if (empty($comments)): ?>
    <p>No pending comments.</p>
<?php endif; ?>

<?php foreach ($comments as $c): ?>
    <div class="card">
        <?= htmlspecialchars($c['content']) ?><br><br>

        <a href="?approve=<?= $c['id'] ?>">Approve</a> |
        <a href="?delete_comment=<?= $c['id'] ?>">Delete</a>
    </div>
<?php endforeach; ?>
</div>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js"></script>
<script>
tinymce.init({
    selector: '#content',
    height: 300,
    menubar: false
});
</script>

</body>
</html>