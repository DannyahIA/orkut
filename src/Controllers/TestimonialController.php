<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class TestimonialController extends Controller
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

        // Get Approved Testimonials
        $sql = "SELECT testimonials.*, users.name as sender_name, users.avatar as sender_avatar 
                FROM testimonials 
                JOIN users ON testimonials.sender_id = users.id 
                WHERE receiver_id = ? AND approved = 1
                ORDER BY created_at DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute([$profileId]);
        $testimonials = $stmt->fetchAll();

        $this->view('testimonials/index', [
            'testimonials' => $testimonials,
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
            $this->redirect('/profile?id=' . $receiverId);
            return;
        }

        // Testimonials start as NOT approved (approved = 0)
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO testimonials (sender_id, receiver_id, content, approved) VALUES (?, ?, ?, 0)");
        $stmt->execute([$senderId, $receiverId, $content]);

        // Could redirect to a success page or just back
        $this->redirect('/profile?id=' . $receiverId);
    }

    public function approve()
    {
        $this->checkAuth();
        $testiId = $_POST['testimonial_id'] ?? null;
        $userId = $_SESSION['user']['id'];

        if ($testiId) {
            $db = Database::getInstance();
            // Only receiver can approve
            $stmt = $db->prepare("UPDATE testimonials SET approved = 1 WHERE id = ? AND receiver_id = ?");
            $stmt->execute([$testiId, $userId]);
        }

        $this->redirect('/profile?id=' . $userId); // Redirect to my profile
    }

    public function delete()
    {
        $this->checkAuth();
        $testiId = $_POST['testimonial_id'] ?? null;
        $profileId = $_POST['profile_id'] ?? null;
        $userId = $_SESSION['user']['id'];

        if ($testiId) {
            $db = Database::getInstance();
            // Sender or Receiver can delete
            $stmt = $db->prepare("DELETE FROM testimonials WHERE id = ? AND (sender_id = ? OR receiver_id = ?)");
            $stmt->execute([$testiId, $userId, $userId]);
        }

        $this->redirect('/profile?id=' . $profileId);
    }

    // Page to manage pending testimonials
    public function pending()
    {
        $this->checkAuth();
        $userId = $_SESSION['user']['id'];
        $db = Database::getInstance();

        $stmt = $db->prepare("SELECT testimonials.*, users.name as sender_name, users.avatar as sender_avatar 
                               FROM testimonials 
                               JOIN users ON testimonials.sender_id = users.id 
                               WHERE receiver_id = ? AND approved = 0");
        $stmt->execute([$userId]);
        $testimonials = $stmt->fetchAll();

        $this->view('profile/testimonials_pending', ['testimonials' => $testimonials]);
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
