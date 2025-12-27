<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class SearchController extends Controller
{
    public function search()
    {
        $this->checkAuth();
        $query = $_GET['q'] ?? '';

        $users = [];
        $communities = [];

        if (!empty($query)) {
            $db = Database::getInstance();
            $term = "%$query%";

            // Search Users
            $stmtUsers = $db->prepare("SELECT id, name, avatar, city, country FROM users WHERE name LIKE ? OR email LIKE ? LIMIT 20");
            $stmtUsers->execute([$term, $term]);
            $users = $stmtUsers->fetchAll();

            // Search Communities
            $stmtComm = $db->prepare("SELECT id, name, description FROM communities WHERE name LIKE ? OR description LIKE ? LIMIT 20");
            $stmtComm->execute([$term, $term]);
            $communities = $stmtComm->fetchAll();
        }

        $this->view('search/results', ['query' => $query, 'users' => $users, 'communities' => $communities]);
    }

    private function checkAuth()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
    }
}
