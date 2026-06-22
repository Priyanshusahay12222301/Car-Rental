<?php
// TEMPORARY DEBUG PAGE — DELETE AFTER CHECKING
// Access: https://your-site.onrender.com/dbcheck.php
session_start();
include('includes/config.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html>
<head>
<title>DB Check</title>
<style>
body { font-family: monospace; padding: 20px; background: #1a1a2e; color: #eee; }
h2 { color: #e94560; }
table { border-collapse: collapse; width: 100%; margin-bottom: 30px; }
th { background: #e94560; color: white; padding: 8px 12px; text-align: left; }
td { padding: 6px 12px; border-bottom: 1px solid #333; }
tr:nth-child(even) { background: #16213e; }
.ok { color: #0f9b58; font-weight: bold; }
.err { color: #e94560; font-weight: bold; }
.section { background: #16213e; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
</style>
</head>
<body>
<h2>🔍 Database Check</h2>

<?php
$tables = ['admin', 'tblusers', 'tblbrands', 'tblvehicles', 'tblbooking', 'tblpages', 'tbltestimonial', 'tblcontactusinfo', 'tblcontactusquery', 'tblsubscribers'];

foreach ($tables as $table) {
    try {
        $q = $dbh->query("SELECT * FROM $table LIMIT 5");
        $rows = $q->fetchAll(PDO::FETCH_ASSOC);
        $count = $dbh->query("SELECT COUNT(*) FROM $table")->fetchColumn();
        echo "<div class='section'>";
        echo "<h2>📋 $table <span class='ok'>($count rows)</span></h2>";
        if (count($rows) > 0) {
            echo "<table><tr>";
            foreach (array_keys($rows[0]) as $col) {
                echo "<th>$col</th>";
            }
            echo "</tr>";
            foreach ($rows as $row) {
                echo "<tr>";
                foreach ($row as $val) {
                    echo "<td>" . htmlspecialchars((string)$val) . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='err'>⚠️ Table is empty!</p>";
        }
        echo "</div>";
    } catch (Exception $e) {
        echo "<div class='section'><h2 class='err'>❌ $table — " . htmlspecialchars($e->getMessage()) . "</h2></div>";
    }
}
?>

<div class='section'>
<h2>🔑 Admin Password Check</h2>
<?php
try {
    $r = $dbh->query("SELECT username, password FROM admin WHERE id=1")->fetch(PDO::FETCH_ASSOC);
    echo "<p>Username: <b>{$r['username']}</b></p>";
    echo "<p>Stored hash: <b>{$r['password']}</b></p>";
    echo "<p>md5('admin123') = <b>" . md5('admin123') . "</b></p>";
    if ($r['password'] === md5('admin123')) {
        echo "<p class='ok'>✅ Password 'admin123' MATCHES!</p>";
    } else {
        echo "<p class='err'>❌ Password does NOT match 'admin123'</p>";
    }
} catch (Exception $e) {
    echo "<p class='err'>Error: " . $e->getMessage() . "</p>";
}
?>
</div>

</body>
</html>
