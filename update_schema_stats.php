<?php

use App\Core\Database;

require_once __DIR__ . '/src/Core/bootstrap.php';

echo "Updating schema for Profile Stats...\n";

$db = Database::getInstance();

$sql = "CREATE TABLE IF NOT EXISTS profile_stats_votes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    voter_id INT NOT NULL,
    target_id INT NOT NULL,
    trusty INT DEFAULT 0,
    cool INT DEFAULT 0,
    sexy INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(voter_id, target_id),
    FOREIGN KEY (voter_id) REFERENCES users(id),
    FOREIGN KEY (target_id) REFERENCES users(id)
)";

try {
    $db->exec($sql);
    echo "Table 'profile_stats_votes' created successfully.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
