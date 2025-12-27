<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class ScrapController extends Controller
{
    public function index()
    {
        $this->checkAuth();
        $profileId = $_GET['uid'] ?? $_SESSION['user']['id'];
        $db = Database::getInstance();

        // Get Profile User
        $stmtUser = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmtUser->execute([$profileId]);
        $profileUser = $stmtUser->fetch();

        if (!$profileUser) {
            echo "User not found";
            return;
        }

        // Get All Scraps
        $stmtScraps = $db->prepare("
            SELECT scraps.*, users.name as sender_name, users.avatar as sender_avatar 
            FROM scraps 
            JOIN users ON scraps.sender_id = users.id 
            WHERE receiver_id = ? 
            ORDER BY created_at DESC
        ");
        $stmtScraps->execute([$profileId]);
        $scraps = $stmtScraps->fetchAll();

        $this->view('scraps/index', [
            'scraps' => $scraps,
            'profileUser' => $profileUser
        ]);
    }

    public function store()
    {
        $this->checkAuth();

        $senderId = $_SESSION['user']['id'];
        $receiverId = $_POST['receiver_id'] ?? null;
        $content = $_POST['content'] ?? null;

        if (!$receiverId || !$content) {
            // Should probably show validation error, but redirecting for now
            $this->redirect('/profile?id=' . $receiverId);
            return;
        }

        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO scraps (sender_id, receiver_id, content) VALUES (?, ?, ?)");
        $stmt->execute([$senderId, $receiverId, $content]);

        $this->redirect('/profile?id=' . $receiverId);
    }

    public function delete()
    {
        $this->checkAuth();
        $scrapId = $_POST['scrap_id'] ?? null;
        $profileId = $_POST['profile_id'] ?? null; // To redirect back
        $userId = $_SESSION['user']['id'];

        if ($scrapId) {
            $db = Database::getInstance();
            // Allow deletion if I sent it OR if I received it (it's on my profile)
            $stmt = $db->prepare("DELETE FROM scraps WHERE id = ? AND (sender_id = ? OR receiver_id = ?)");
            $stmt->execute([$scrapId, $userId, $userId]);
        }

        $this->redirect('/profile?id=' . $profileId);
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
