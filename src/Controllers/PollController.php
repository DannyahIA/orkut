<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class PollController extends Controller
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

        $this->view('communities/create_poll', ['community' => $community]);
    }

    public function store()
    {
        $this->checkAuth();
        $communityId = $_POST['community_id'] ?? null;
        $question = $_POST['question'] ?? null;
        $options = $_POST['options'] ?? []; // Array of strings
        $userId = $_SESSION['user']['id'];

        if (!$communityId || !$question || count($options) < 2) {
            // Basic validation
            $this->redirect('/communities/show?id=' . $communityId);
            return;
        }

        $db = Database::getInstance();

        // 1. Create Poll
        $stmtPoll = $db->prepare("INSERT INTO community_polls (community_id, user_id, question) VALUES (?, ?, ?)");
        $stmtPoll->execute([$communityId, $userId, $question]);
        $pollId = $db->lastInsertId();

        // 2. Create Options
        $stmtOption = $db->prepare("INSERT INTO community_poll_options (poll_id, option_text) VALUES (?, ?)");
        foreach ($options as $opt) {
            if (!empty(trim($opt))) {
                $stmtOption->execute([$pollId, trim($opt)]);
            }
        }

        $this->redirect("/communities/show?id=$communityId");
    }

    public function vote()
    {
        $this->checkAuth();
        $pollId = $_POST['poll_id'] ?? null;
        $optionId = $_POST['option_id'] ?? null;
        $communityId = $_POST['community_id'] ?? null;
        $userId = $_SESSION['user']['id'];

        if ($pollId && $optionId) {
            $db = Database::getInstance();
            try {
                $stmt = $db->prepare("INSERT INTO community_poll_votes (poll_id, option_id, user_id) VALUES (?, ?, ?)");
                $stmt->execute([$pollId, $optionId, $userId]);
            } catch (\PDOException $e) {
                // User likely already voted, ignore constraint violation
            }
        }

        $this->redirect("/communities/show?id=$communityId");
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
