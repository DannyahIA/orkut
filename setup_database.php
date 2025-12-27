<?php

use App\Core\Database;

require_once __DIR__ . '/src/Core/bootstrap.php';

echo "Initializing Database Schema (SQLite compatible)...\n";

$db = Database::getInstance();

// SQLite uses slightly different syntax/types
// ENUM is not supported -> TEXT
// AUTO_INCREMENT -> AUTOINCREMENT (only on INTEGER PRIMARY KEY)

$queries = [
    "CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        name VARCHAR(255) NOT NULL,
        birthdate DATE,
        gender TEXT,
        country VARCHAR(100),
        trusty INT DEFAULT 0,
        cool INT DEFAULT 0,
        sexy INT DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )",
    "CREATE TABLE IF NOT EXISTS communities (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        owner_id INT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (owner_id) REFERENCES users(id)
    )",
    "CREATE TABLE IF NOT EXISTS community_members (
        community_id INT,
        user_id INT,
        joined_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (community_id, user_id),
        FOREIGN KEY (community_id) REFERENCES communities(id),
        FOREIGN KEY (user_id) REFERENCES users(id)
    )",
    "CREATE TABLE IF NOT EXISTS friends (
        requester_id INT,
        addressee_id INT,
        status TEXT DEFAULT 'pending', 
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (requester_id, addressee_id),
        FOREIGN KEY (requester_id) REFERENCES users(id),
        FOREIGN KEY (addressee_id) REFERENCES users(id)
    )",
    "CREATE TABLE IF NOT EXISTS scraps (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        sender_id INT NOT NULL,
        receiver_id INT NOT NULL,
        content TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (sender_id) REFERENCES users(id),
        FOREIGN KEY (receiver_id) REFERENCES users(id)
    )",
    "CREATE TABLE IF NOT EXISTS testimonials (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        sender_id INT NOT NULL,
        receiver_id INT NOT NULL,
        content TEXT,
        approved BOOLEAN DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (sender_id) REFERENCES users(id),
        FOREIGN KEY (receiver_id) REFERENCES users(id)
    )"
];

foreach ($queries as $sql) {
    try {
        $db->exec($sql);
        echo "Executed: " . substr($sql, 0, 50) . "...\n";
    } catch (PDOException $e) {
        echo "Error executing SQL: " . $sql . "\n";
        echo "PDO Msg: " . $e->getMessage() . "\n";
    }
}

echo "Database successfully initialized.\n";
