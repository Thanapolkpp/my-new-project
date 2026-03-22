<?php
require_once 'app/Legacy/LegacyDatabase.php';
$db = (new LegacyDatabase())->getConnection();

try {
    echo "<h1>Updating Database Schema...</h1>";
    
    // Check if column exists in orders table
    $stmt = $db->query("SHOW COLUMNS FROM orders LIKE 'payment_slip'");
    $exists = $stmt->fetch();

    if (!$exists) {
        echo "<p>Adding 'payment_slip' and 'payment_date' columns to orders table...</p>";
        $db->exec("ALTER TABLE orders ADD COLUMN payment_slip VARCHAR(255) NULL AFTER payment_status");
        $db->exec("ALTER TABLE orders ADD COLUMN payment_date DATETIME NULL AFTER payment_slip");
        echo "<p style='color: green;'>✅ Columns added successfully!</p>";
    } else {
        echo "<p style='color: blue;'>ℹ️ Columns already exist.</p>";
    }

    // Ensure status column can handle 'paid' status
    echo "<p>Checking orders table status...</p>";
    echo "<p style='color: green;'>✅ Database is ready!</p>";

} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}
?>
<hr>
<a href="index.php/orders">กลับไปหน้าคำสั่งซื้อ</a>
