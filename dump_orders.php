<?php
require_once 'app/Legacy/LegacyDatabase.php';
$db = (new LegacyDatabase())->getConnection();
$stmt = $db->query("SELECT * FROM orders LIMIT 10");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($orders, JSON_PRETTY_PRINT);
