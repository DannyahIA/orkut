<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class VideoController extends Controller
{
    public function index()
    {
        $this->checkAuth();
        $userId = $_GET['uid'] ?? $_SESSION['user']['id'];

        $db = Database::getInstance();

        // Get User
        $stmtUser = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmtUser->execute([$userId]);
        $videoOwner = $stmtUser->fetch();

        if (!$videoOwner) {
            echo "User not found";
            return;
        }

        // Get Videos
        $stmt = $db->prepare("SELECT * FROM videos WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        $videos = $stmt->fetchAll();

        $this->view('videos/index', ['videos' => $videos, 'videoOwner' => $videoOwner]);
    }

    public function create()
    {
        $this->checkAuth();
        $this->view('videos/create');
    }

    public function store()
    {
        $this->checkAuth();
        $url = $_POST['youtube_url'] ?? '';
        $title = $_POST['title'] ?? 'Sem título';
        $userId = $_SESSION['user']['id'];

        // Extract ID
        $videoId = $this->extractYoutubeId($url);

        if ($videoId) {
            $db = Database::getInstance();
            $stmt = $db->prepare("INSERT INTO videos (user_id, youtube_url, title) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $url, $title]);
        }

        $this->redirect('/videos?uid=' . $userId);
    }

    public function delete()
    {
        $this->checkAuth();
        $id = $_POST['id'] ?? null;
        $userId = $_SESSION['user']['id'];

        if ($id) {
            $db = Database::getInstance();
            $stmt = $db->prepare("DELETE FROM videos WHERE id = ? AND user_id = ?");
            $stmt->execute([$id, $userId]);
        }

        $this->redirect('/videos?uid=' . $userId);
    }

    private function extractYoutubeId($url)
    {
        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
        return null; // Invalid URL
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
