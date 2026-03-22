<?php
require_once 'app/Legacy/LegacyDatabase.php';
$db = (new LegacyDatabase())->getConnection();

try {
    echo "Starting DB fix...\n";
    
    // 1. Delete ID 0 records as they are corrupted/mixed
    echo "Deleting corrupted ID 0 records...\n";
    $db->exec("DELETE FROM orders WHERE id = 0");
    $db->exec("DELETE FROM order_items WHERE order_id = 0");
    $db->exec("DELETE FROM order_items WHERE id = 0");

    // 2. Add Primary Key and Auto Increment to orders
    echo "Updating orders table...\n";
    $db->exec("ALTER TABLE orders MODIFY COLUMN id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY");

    // 3. Add Primary Key and Auto Increment to order_items
    echo "Updating order_items table...\n";
    $db->exec("ALTER TABLE order_items MODIFY COLUMN id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY");

    echo "Fix applied successfully!\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
