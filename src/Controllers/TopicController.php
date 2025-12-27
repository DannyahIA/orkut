<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class TopicController extends Controller
{
    public function create()
    {
        $this->checkAuth();
        $communityId = $_GET['community_id'] ?? null;

        if (!$communityId) {
            $this->redirect('/communities');
            return;
        }

        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM communities WHERE id = ?");
        $stmt->execute([$communityId]);
        $community = $stmt->fetch();

        $this->view('communities/create_topic', ['community' => $community]);
    }

    public function store()
    {
        $this->checkAuth();
        $communityId = $_POST['community_id'] ?? null;
        $title = $_POST['title'] ?? null;
        $content = $_POST['content'] ?? null;
        $userId = $_SESSION['user']['id'];

        if (!$communityId || !$title || !$content) {
            // Validation error handling could be better
            $this->redirect('/communities/show?id=' . $communityId);
            return;
        }

        $db = Database::getInstance();

        // 1. Create Topic
        $stmtTopic = $db->prepare("INSERT INTO community_topics (community_id, user_id, title) VALUES (?, ?, ?)");
        $stmtTopic->execute([$communityId, $userId, $title]);
        $topicId = $db->lastInsertId();

        // 2. Create First Post
        $stmtPost = $db->prepare("INSERT INTO community_posts (topic_id, user_id, content) VALUES (?, ?, ?)");
        $stmtPost->execute([$topicId, $userId, $content]);

        $this->redirect("/communities/topic?id=$topicId");
    }

    public function show()
    {
        $this->checkAuth();
        $topicId = $_GET['id'] ?? null;

        if (!$topicId) {
            $this->redirect('/communities');
            return;
        }

        $db = Database::getInstance();

        // Get Topic & Community Info
        $stmtTopic = $db->prepare("SELECT t.*, c.name as community_name, c.id as community_id 
                                   FROM community_topics t 
                                   JOIN communities c ON t.community_id = c.id 
                                   WHERE t.id = ?");
        $stmtTopic->execute([$topicId]);
        $topic = $stmtTopic->fetch();

        if (!$topic)
            die("Tópico não encontrado.");

        // Get Posts
        $stmtPosts = $db->prepare("SELECT p.*, u.name as author_name, u.avatar as author_avatar, u.id as author_id 
                                   FROM community_posts p 
                                   JOIN users u ON p.user_id = u.id 
                                   WHERE p.topic_id = ? 
                                   ORDER BY p.created_at ASC");
        $stmtPosts->execute([$topicId]);
        $posts = $stmtPosts->fetchAll();

        $this->view('communities/topic', ['topic' => $topic, 'posts' => $posts]);
    }

    public function reply()
    {
        $this->checkAuth();
        $topicId = $_POST['topic_id'] ?? null;
        $content = $_POST['content'] ?? null;
        $userId = $_SESSION['user']['id'];

        if ($topicId && $content) {
            $db = Database::getInstance();
            $stmt = $db->prepare("INSERT INTO community_posts (topic_id, user_id, content) VALUES (?, ?, ?)");
            $stmt->execute([$topicId, $userId, $content]);
        }

        $this->redirect("/communities/topic?id=$topicId");
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
