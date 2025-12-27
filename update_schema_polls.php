<?php

use App\Core\Database;

require_once __DIR__ . '/src/Core/bootstrap.php';

echo "Updating schema for Polls...\n";

$db = Database::getInstance();

$queries = [
    "CREATE TABLE IF NOT EXISTS community_polls (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        community_id INT NOT NULL,
        user_id INT NOT NULL,
        question VARCHAR(255) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (community_id) REFERENCES communities(id),
        FOREIGN KEY (user_id) REFERENCES users(id)
    )",
    "CREATE TABLE IF NOT EXISTS community_poll_options (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        poll_id INT NOT NULL,
        option_text VARCHAR(255) NOT NULL,
        FOREIGN KEY (poll_id) REFERENCES community_polls(id)
    )",
    "CREATE TABLE IF NOT EXISTS community_poll_votes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        poll_id INT NOT NULL,
        option_id INT NOT NULL,
        user_id INT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        UNIQUE(poll_id, user_id),
        FOREIGN KEY (poll_id) REFERENCES community_polls(id),
        FOREIGN KEY (option_id) REFERENCES community_poll_options(id),
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
