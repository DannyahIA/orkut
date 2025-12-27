<?php

use App\Core\Database;

require_once __DIR__ . '/src/Core/bootstrap.php';

echo "Updating Database Schema...\n";

$db = Database::getInstance();

$columns = [
    "relationship_status" => "TEXT",
    "city" => "VARCHAR(100)",
    "bio" => "TEXT",
    "interests" => "TEXT",
    "avatar" => "VARCHAR(255)"
];

foreach ($columns as $col => $type) {
    try {
        // SQLite ADD COLUMN syntax
        $sql = "ALTER TABLE users ADD COLUMN $col $type";
        $db->exec($sql);
        echo "Added column: $col\n";
    } catch (PDOException $e) {
        // Ignore if column likely exists (naive check)
        echo "Column $col might already exist or error: " . $e->getMessage() . "\n";
    }
}

echo "Database schema update finished.\n";
