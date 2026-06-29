<?php
session_start();
require_once 'db.php';

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

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
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
            * { margin:0; padding:0; box-sizing:border-box; }
            body { background:#0a0a1a; display:flex; justify-content:center; align-items:center; height:100vh; font-family:'Inter',sans-serif; }
            .box { background:rgba(22,27,34,0.9); padding:48px 40px; border-radius:20px; border:1px solid rgba(0,212,255,0.15); width:380px; box-shadow:0 25px 60px rgba(0,0,0,0.5); }
            .logo { text-align:center; font-size:2rem; margin-bottom:8px; }
            h2 { color:#fff; text-align:center; margin-bottom:6px; font-size:1.4rem; font-weight:600; }
            .sub { color:#555; text-align:center; margin-bottom:30px; font-size:0.85rem; }
            .input-wrap { position:relative; margin-bottom:16px; }
            .input-wrap span { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#555; }
            input { width:100%; padding:14px 14px 14px 42px; border-radius:10px; border:1px solid rgba(255,255,255,0.08); background:rgba(255,255,255,0.05); color:#eee; font-size:0.95rem; font-family:'Inter',sans-serif; }
            input:focus { outline:none; border-color:rgba(0,212,255,0.4); }
            button { width:100%; padding:14px; background:linear-gradient(135deg,#00d4ff,#0099bb); color:#000; border:none; border-radius:10px; font-size:1rem; font-weight:700; cursor:pointer; margin-top:8px; }
            .error { color:#ff4757; text-align:center; margin-bottom:16px; font-size:0.9rem; background:rgba(255,71,87,0.1); padding:10px; border-radius:8px; }
        </style>
    </head>
    <body>
        <div class="box">
            <div class="logo">⚙️</div>
            <h2>Admin Panel</h2>
            <p class="sub">Firojatech IT.System</p>
            <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                <div class="error">❌ Wrong password!</div>
            <?php endif; ?>
            <form method="POST">
                <div class="input-wrap">
                    <span>🔑</span>
                    <input type="password" name="pass" placeholder="Enter Password" required autofocus>
                </div>
                <button type="submit">Login</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Export CSV handler
if (isset($_GET['export'])) {
    $type = $_GET['export'];
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . $type . '_' . date('Y-m-d') . '.csv');
    $output = fopen('php://output', 'w');
    if ($type === 'contacts') {
        fputcsv($output, ['ID', 'Name', 'Email', 'Message', 'Date']);
        $rows = $pdo->query("SELECT id, name, email, message, created_at FROM contacts ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) fputcsv($output, $row);
    } elseif ($type === 'applications') {
        fputcsv($output, ['ID', 'Name', 'Email', 'Position', 'Cover Letter', 'Date']);
        $rows = $pdo->query("SELECT id, name, email, position, cover_letter, created_at FROM applications ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) fputcsv($output, $row);
    }
    fclose($output);
    exit;
}

if (isset($_GET['delete_contact'])) {
    $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = ?");
    $stmt->execute([$_GET['delete_contact']]);
    header("Location: admin.php"); exit;
}
if (isset($_GET['delete_app'])) {
    $stmt = $pdo->prepare("DELETE FROM applications WHERE id = ?");
    $stmt->execute([$_GET['delete_app']]);
    header("Location: admin.php"); exit;
}

$contacts = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$apps = $pdo->query("SELECT * FROM applications ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Firojatech Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        * { margin:0; padding:0; box-sizing:border-box; }
        body { background:linear-gradient(-45deg, #0a0a1a, #0d1b2a, #1a0a2e, #0a1628); background-size:400% 400%; animation:bgShift 10s ease infinite; color:#eee; font-family:'Inter',sans-serif; min-height:100vh; }
        @keyframes bgShift { 0%{background-position:0% 50%;} 50%{background-position:100% 50%;} 100%{background-position:0% 50%;} }
        .topbar { background:rgba(13,17,23,0.95); border-bottom:1px solid #1e2530; padding:16px 30px; display:flex; justify-content:space-between; align-items:center; position:sticky; top:0; z-index:100; backdrop-filter:blur(10px); }
        .brand { display:flex; align-items:center; gap:12px; }
        .brand-icon { width:36px; height:36px; background:linear-gradient(135deg,#00d4ff,#0099bb); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:1.1rem; }
        .brand-name { color:#fff; font-weight:700; font-size:1.1rem; }
        .brand-sub { color:#555; font-size:0.75rem; }
        .logout { background:rgba(255,71,87,0.15); color:#ff4757; padding:8px 16px; border-radius:8px; text-decoration:none; font-size:0.82rem; border:1px solid rgba(255,71,87,0.2); }
        .main { padding:30px; max-width:1400px; margin:0 auto; }
        .summary { display:grid; grid-template-columns:repeat(2,1fr); gap:16px; margin-bottom:30px; }
        .sum-card { background:rgba(22,27,34,0.6); border:1px solid rgba(0,212,255,0.15); border-radius:14px; padding:24px; text-align:center; backdrop-filter:blur(10px); transition:all 0.3s; }
        .sum-card:hover { border-color:rgba(0,212,255,0.4); box-shadow:0 0 30px rgba(0,212,255,0.1); transform:translateY(-3px); }
        .sum-val { font-size:2.5rem; font-weight:700; margin-bottom:4px; }
        .sum-label { color:#555; font-size:0.78rem; text-transform:uppercase; letter-spacing:1px; }
        .section-header { display:flex; justify-content:space-between; align-items:center; margin:30px 0 16px; }
        .section-title { color:#00d4ff; font-size:1rem; font-weight:600; text-transform:uppercase; letter-spacing:1px; display:flex; align-items:center; gap:10px; }
        .export-btn { background:rgba(0,212,255,0.15); color:#00d4ff; padding:8px 16px; border-radius:8px; text-decoration:none; font-size:0.82rem; border:1px solid rgba(0,212,255,0.3); transition:all 0.3s; }
        .export-btn:hover { background:rgba(0,212,255,0.3); }
        table { width:100%; border-collapse:collapse; background:rgba(22,27,34,0.8); border-radius:14px; overflow:hidden; border:1px solid #1e2530; }
        th { background:linear-gradient(135deg,#0d1117,#161b22); color:#00d4ff; padding:14px 16px; text-align:left; font-size:0.78rem; text-transform:uppercase; letter-spacing:1px; font-weight:600; border-bottom:1px solid #1e2530; }
        td { padding:14px 16px; border-bottom:1px solid #1a2030; font-size:0.88rem; color:#ccc; vertical-align:top; max-width:300px; word-wrap:break-word; white-space:pre-wrap; }
        tr:last-child td { border-bottom:none; }
        tr:hover td { background:rgba(0,212,255,0.03); }
        .delete { color:#ff4757; text-decoration:none; font-weight:600; font-size:0.82rem; background:rgba(255,71,87,0.1); padding:4px 12px; border-radius:6px; border:1px solid rgba(255,71,87,0.2); white-space:nowrap; }
        .delete:hover { background:rgba(255,71,87,0.2); }
        .empty { text-align:center; padding:40px; color:#333; font-size:0.9rem; }
        .badge { display:inline-block; background:rgba(0,212,255,0.1); color:#00d4ff; padding:3px 10px; border-radius:20px; font-size:0.75rem; border:1px solid rgba(0,212,255,0.2); margin-left:8px; }
        .footer { text-align:center; color:#2a3040; margin-top:40px; padding:20px; font-size:0.8rem; }
    </style>
</head>
<body>
<div class="topbar">
    <div class="brand">
        <div class="brand-icon">⚙️</div>
        <div>
            <div class="brand-name">Firojatech Admin</div>
            <div class="brand-sub">Control Panel</div>
        </div>
    </div>
    <a href="?logout" class="logout">Logout</a>
</div>

<div class="main">
    <div class="summary">
        <div class="sum-card">
            <div class="sum-val" style="color:#00d4ff;"><?php echo count($contacts); ?></div>
            <div class="sum-label">Contact Submissions</div>
        </div>
        <div class="sum-card">
            <div class="sum-val" style="color:#a29bfe;"><?php echo count($apps); ?></div>
            <div class="sum-label">Job Applications</div>
        </div>
    </div>

    <div class="section-header">
        <div class="section-title">📧 Contact Submissions <span class="badge"><?php echo count($contacts); ?></span></div>
        <a href="?export=contacts" class="export-btn">⬇️ Export CSV</a>
    </div>
    <?php if ($contacts): ?>
    <table>
        <tr><th>ID</th><th>Name</th><th>Email</th><th>Message</th><th>Date</th><th>Action</th></tr>
        <?php foreach ($contacts as $c): ?>
        <tr>
            <td><?php echo $c['id']; ?></td>
            <td><?php echo htmlspecialchars($c['name']); ?></td>
            <td><?php echo htmlspecialchars($c['email']); ?></td>
            <td><?php echo htmlspecialchars($c['message']); ?></td>
            <td><?php echo $c['created_at']; ?></td>
            <td><a href="?delete_contact=<?php echo $c['id']; ?>" class="delete" onclick="return confirm('Delete?')">Delete</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: echo "<div class='empty'>No contacts yet.</div>"; endif; ?>

    <div class="section-header" style="margin-top:30px;">
        <div class="section-title">💼 Job Applications <span class="badge"><?php echo count($apps); ?></span></div>
        <a href="?export=applications" class="export-btn">⬇️ Export CSV</a>
    </div>
    <?php if ($apps): ?>
    <table>
        <tr><th>ID</th><th>Name</th><th>Email</th><th>Position</th><th>Cover Letter</th><th>Date</th><th>Action</th></tr>
        <?php foreach ($apps as $a): ?>
        <tr>
            <td><?php echo $a['id']; ?></td>
            <td><?php echo htmlspecialchars($a['name']); ?></td>
            <td><?php echo htmlspecialchars($a['email']); ?></td>
            <td><?php echo htmlspecialchars($a['position']); ?></td>
            <td><?php echo htmlspecialchars($a['cover_letter']); ?></td>
            <td><?php echo $a['created_at']; ?></td>
            <td><a href="?delete_app=<?php echo $a['id']; ?>" class="delete" onclick="return confirm('Delete?')">Delete</a></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php else: echo "<div class='empty'>No applications yet.</div>"; endif; ?>
</div>
<div class="footer">Firojatech IT.System — Admin Panel 2026</div>
</body>
</html>
