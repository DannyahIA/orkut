<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        // Load config (naive implementation for now, ideally use env)
        $config = require ROOT_PATH . '/config/database.php';

        try {
            if (isset($config['driver']) && $config['driver'] === 'sqlite') {
                $dsn = "sqlite:" . $config['sqlite_path'];
                $this->connection = new PDO($dsn);
                // SQLite specific: Enable Foreign Keys
                $this->connection->exec("PRAGMA foreign_keys = ON;");
            } else {
                $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
                $this->connection = new PDO($dsn, $config['username'], $config['password']);
            }

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Database Connection Failed: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->connection;
    }
}
