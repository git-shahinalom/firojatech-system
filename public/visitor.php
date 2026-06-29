<?php
require_once 'db.php';
$today = date('Y-m-d');
$pdo->exec("INSERT INTO visitors (visit_date, count) VALUES ('$today', 1) ON DUPLICATE KEY UPDATE count = count + 1");
$total = $pdo->query("SELECT SUM(count) FROM visitors")->fetchColumn();
$today_count = $pdo->query("SELECT count FROM visitors WHERE visit_date = '$today'")->fetchColumn();
echo json_encode(['total' => $total, 'today' => $today_count]);
?>
