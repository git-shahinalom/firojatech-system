<?php
session_start();
require_once 'db.php';

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

// Login check
if (!isset($_SESSION['admin'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['pass'] ?? '') === 'admin123') {
        $_SESSION['admin'] = true;
        header("Location: admin.php");
        exit;
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Admin Login</title>
        <style>
            * { margin:0; padding:0; box-sizing:border-box; }
            body { background:#1a1a2e; display:flex; justify-content:center; align-items:center; height:100vh; font-family:Arial; }
            .box { background:#16213e; padding:40px; border-radius:12px; border:1px solid #0f3460; width:350px; }
            h2 { color:#00d4ff; text-align:center; margin-bottom:25px; }
            input { width:100%; padding:12px; margin-bottom:15px; border-radius:8px; border:1px solid #0f3460; background:#0d1117; color:#eee; font-size:1rem; }
            button { width:100%; padding:12px; background:#00d4ff; color:#000; border:none; border-radius:8px; font-size:1rem; font-weight:bold; cursor:pointer; }
            button:hover { background:#00b8d9; }
            .error { color:#e74c3c; text-align:center; margin-bottom:10px; font-size:0.9rem; }
        </style>
    </head>
    <body>
        <div class="box">
            <h2>🔐 Admin Login</h2>
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                <p class="error">❌ Wrong password!</p>
            <?php endif; ?>
            <form method="POST">
                <input type="password" name="pass" placeholder="Enter Password" required autofocus>
                <button type="submit">Login</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}









// ডিলিট হ্যান্ডলার
if (isset($_GET['delete_contact'])) {
    $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = ?");
    $stmt->execute([$_GET['delete_contact']]);
    header("Location: admin.php");
}
if (isset($_GET['delete_app'])) {
    $stmt = $pdo->prepare("DELETE FROM applications WHERE id = ?");
    $stmt->execute([$_GET['delete_app']]);
    header("Location: admin.php");
}

// ডাটা ফেচ
$contacts = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$apps = $pdo->query("SELECT * FROM applications ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Firojatech - Admin Panel</title>
   
   <style>
    body { font-family: Arial, sans-serif; margin: 20px; background: #f4f4f4; }
    h1, h2 { color: #2c3e50; }
    table { width: 100%; border-collapse: collapse; margin: 20px 0; background: white; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    th, td { border: 1px solid #ddd; padding: 12px; text-align: left; max-width: 300px; word-wrap: break-word; white-space: pre-wrap; }
    th { background-color: #3498db; color: white; }
    tr:nth-child(even) { background-color: #f9f9f9; }
    .delete { color: #e74c3c; text-decoration: none; font-weight: bold; }
    .delete:hover { text-decoration: underline; }
    .logout { float: right; margin: 10px; padding: 8px 16px; background: #e74c3c; color: white; text-decoration: none; border-radius: 4px; }
</style>    




</head>
<body>
    <h1>Firojatech Admin Panel</h1>
    <a href="?logout" class="logout">Logout</a>
    <?php if (isset($_GET['logout'])) { session_destroy(); header("Location: admin.php"); } ?>

    <h2>Contact Form Submissions (<?php echo count($contacts); ?>)</h2>
    <?php if ($contacts): ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        <?php foreach ($contacts as $c): ?>
        <tr>
            <td><?php echo $c['id']; ?></td>
            <td><?php echo htmlspecialchars($c['name']); ?></td>
            <td><?php echo htmlspecialchars($c['email']); ?></td>
            <td><?php echo htmlspecialchars($c['message']); ?></td>
            <td><?php echo $c['created_at']; ?></td>
            <td><a href="?delete_contact=<?php echo $c['id']; ?>" class="delete" onclick="return confirm('Delete this contact?')">Delete</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: echo "<p>No contacts yet.</p>"; endif; ?>

    <h2>Job Applications (<?php echo count($apps); ?>)</h2>
    <?php if ($apps): ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Position</th>
            <th>Cover Letter</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        <?php foreach ($apps as $a): ?>
        <tr>
            <td><?php echo $a['id']; ?></td>
            <td><?php echo htmlspecialchars($a['name']); ?></td>
            <td><?php echo htmlspecialchars($a['email']); ?></td>
            <td><?php echo htmlspecialchars($a['position']); ?></td>
            <td><?php echo htmlspecialchars($a['cover_letter']); ?></td>
            <td><?php echo $a['created_at']; ?></td>
            <td><a href="?delete_app=<?php echo $a['id']; ?>" class="delete" onclick="return confirm('Delete this application?')">Delete</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: echo "<p>No applications yet.</p>"; endif; ?>
</body>
</html>
