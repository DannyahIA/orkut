<?php

use App\Core\Database;

require_once __DIR__ . '/src/Core/bootstrap.php';

echo "Updating schema for Fans...\n";

$db = Database::getInstance();

$sql = "CREATE TABLE IF NOT EXISTS fans (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    idol_id INT NOT NULL,
    fan_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(idol_id, fan_id),
    FOREIGN KEY (idol_id) REFERENCES users(id),
    FOREIGN KEY (fan_id) REFERENCES users(id)
)";

try {
    $db->exec($sql);
    echo "Table 'fans' created successfully.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
