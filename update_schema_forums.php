<?php

use App\Core\Database;

require_once __DIR__ . '/src/Core/bootstrap.php';

echo "Updating schema for Forums...\n";

$db = Database::getInstance();

$queries = [
    "CREATE TABLE IF NOT EXISTS community_topics (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        community_id INT NOT NULL,
        user_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (community_id) REFERENCES communities(id),
        FOREIGN KEY (user_id) REFERENCES users(id)
    )",
    "CREATE TABLE IF NOT EXISTS community_posts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        topic_id INT NOT NULL,
        user_id INT NOT NULL,
        content TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (topic_id) REFERENCES community_topics(id),
        FOREIGN KEY (user_id) REFERENCES users(id)
    )"
];

foreach ($queries as $sql) {
    try {
        $db->exec($sql);
        echo "Executed SQL successfully.\n";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
