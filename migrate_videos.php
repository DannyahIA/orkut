<?php
// migrate_videos.php

use App\Core\Database;

require_once __DIR__ . '/src/Core/bootstrap.php';

echo "Migrating Videos Table...\n";

$db = Database::getInstance();

$sql = "CREATE TABLE IF NOT EXISTS videos (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INT NOT NULL,
    youtube_url VARCHAR(255) NOT NULL,
    title VARCHAR(255),
    description TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

try {
    $db->exec($sql);
    echo "Videos table created successfully.\n";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
