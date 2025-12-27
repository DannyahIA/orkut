<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class CommunityController extends Controller
{
    public function index()
    {
        $this->checkAuth();
        $userId = $_SESSION['user']['id'];
        $db = Database::getInstance();

        // Get my communities
        // Get my communities with member count
        $sql = "SELECT communities.*, 
                (SELECT COUNT(*) FROM community_members WHERE community_id = communities.id) as members_count 
                FROM communities 
                JOIN community_members ON communities.id = community_members.community_id 
                WHERE community_members.user_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$userId]);
        $communities = $stmt->fetchAll();

        $this->view('communities/index', ['communities' => $communities]);
    }

    public function create()
    {
        $this->checkAuth();
        $this->view('communities/create');
    }

    public function store()
    {
        $this->checkAuth();
        $name = $_POST['name'] ?? null;
        $description = $_POST['description'] ?? null;
        $userId = $_SESSION['user']['id'];

        if (!$name) {
            die("Nome da comunidade é obrigatório.");
        }

        $db = Database::getInstance();

        // Create community
        $stmt = $db->prepare("INSERT INTO communities (name, description, owner_id) VALUES (?, ?, ?)");
        $stmt->execute([$name, $description, $userId]);
        $communityId = $db->lastInsertId();

        // Auto-join owner
        $stmtJoin = $db->prepare("INSERT INTO community_members (community_id, user_id) VALUES (?, ?)");
        $stmtJoin->execute([$communityId, $userId]);

        $this->redirect('/communities/show?id=' . $communityId);
    }

    public function show()
    {
        $this->checkAuth();
        $id = $_GET['id'] ?? null;
        $user = $_SESSION['user'];

        if (!$id) {
            $this->redirect('/communities');
            return;
        }
        $db = Database::getInstance();

        // Get Community Info
        $stmt = $db->prepare("SELECT * FROM communities WHERE id = ?");
        $stmt->execute([$id]);
        $community = $stmt->fetch();

        if (!$community) {
            die("Comunidade não encontrada.");
        }

        // Get Members Count
        $stmtCount = $db->prepare("SELECT COUNT(*) as count FROM community_members WHERE community_id = ?");
        $stmtCount->execute([$id]);
        $membersCount = $stmtCount->fetch()['count'];

        // Check if I am member
        $myId = $_SESSION['user']['id'];
        $stmtMember = $db->prepare("SELECT * FROM community_members WHERE community_id = ? AND user_id = ?");
        $stmtMember->execute([$id, $myId]);
        $isMember = $stmtMember->fetch();

        // Get Recent Members
        $stmtMembers = $db->prepare("SELECT users.id, users.name, users.avatar FROM users 
                                    JOIN community_members ON users.id = community_members.user_id 
                                    WHERE community_members.community_id = ? LIMIT 9");
        $stmtMembers->execute([$id]);
        $members = $stmtMembers->fetchAll();

        // Get Topics (Recent)
        // Count posts per topic could be a nice add later
        $stmtTopics = $db->prepare("SELECT t.*, u.name as author_name, count(p.id) as post_count 
                                    FROM community_topics t 
                                    JOIN users u ON t.user_id = u.id 
                                    LEFT JOIN community_posts p ON t.id = p.topic_id
                                    WHERE t.community_id = ? 
                                    GROUP BY t.id
                                    ORDER BY t.created_at DESC LIMIT 10");
        $stmtTopics->execute([$id]);
        $topics = $stmtTopics->fetchAll();

        // Get Latest Poll
        $poll = null;
        $pollOptions = [];
        $userVoted = false;
        $totalVotes = 0;

        $stmtPoll = $db->prepare("SELECT * FROM community_polls WHERE community_id = ? ORDER BY created_at DESC LIMIT 1");
        $stmtPoll->execute([$id]);
        $poll = $stmtPoll->fetch();

        if ($poll) {
            // Get Options & Vote Counts
            $stmtOptions = $db->prepare("SELECT o.*, COUNT(v.id) as votes 
                                         FROM community_poll_options o 
                                         LEFT JOIN community_poll_votes v ON o.id = v.option_id 
                                         WHERE o.poll_id = ? 
                                         GROUP BY o.id");
            $stmtOptions->execute([$poll['id']]);
            $pollOptions = $stmtOptions->fetchAll();

            // Check if user voted
            $stmtVoteCheck = $db->prepare("SELECT id FROM community_poll_votes WHERE poll_id = ? AND user_id = ?");
            $stmtVoteCheck->execute([$poll['id'], $user['id']]);
            if ($stmtVoteCheck->fetch()) {
                $userVoted = true;
            }

            // Calculate total votes for percentage
            foreach ($pollOptions as $opt) {
                $totalVotes += $opt['votes'];
            }
        }

        $this->view('communities/show', [
            'community' => $community,
            'membersCount' => $membersCount,
            'isMember' => $isMember,
            'members' => $members,
            'topics' => $topics,
            'poll' => $poll,
            'pollOptions' => $pollOptions,
            'userVoted' => $userVoted,
            'totalVotes' => $totalVotes
        ]);
    }

    public function join()
    {
        $this->checkAuth();
        $communityId = $_POST['community_id'] ?? null;
        $userId = $_SESSION['user']['id'];

        if ($communityId) {
            $db = Database::getInstance();
            try {
                $stmt = $db->prepare("INSERT INTO community_members (community_id, user_id) VALUES (?, ?)");
                $stmt->execute([$communityId, $userId]);
            } catch (\PDOException $e) {
                // Already member, ignore
            }
        }
        $this->redirect('/communities/show?id=' . $communityId);
    }

    public function leave()
    {
        $this->checkAuth();
        $communityId = $_POST['community_id'] ?? null;
        $userId = $_SESSION['user']['id'];

        if ($communityId) {
            $db = Database::getInstance();
            $stmt = $db->prepare("DELETE FROM community_members WHERE community_id = ? AND user_id = ?");
            $stmt->execute([$communityId, $userId]);
        }
        $this->redirect('/communities/show?id=' . $communityId);

    }

    public function edit()
    {
        $this->checkAuth();
        $id = $_GET['id'] ?? null;
        $db = Database::getInstance();

        $stmt = $db->prepare("SELECT * FROM communities WHERE id = ?");
        $stmt->execute([$id]);
        $community = $stmt->fetch();

        // Check ownership
        if (!$community || $community['user_id'] != $_SESSION['user']['id']) {
            $this->redirect('/communities');
            return;
        }

        $this->view('communities/edit', ['community' => $community]);
    }

    public function update()
    {
        $this->checkAuth();
        $id = $_POST['id'] ?? null;
        $name = $_POST['name'] ?? null;
        $desc = $_POST['description'] ?? null;
        $image = $_POST['image'] ?? null;

        $db = Database::getInstance();

        // Ownership check again (security)
        $stmtCheck = $db->prepare("SELECT user_id FROM communities WHERE id = ?");
        $stmtCheck->execute([$id]);
        $comm = $stmtCheck->fetch();

        if ($comm && $comm['user_id'] == $_SESSION['user']['id']) {
            $stmt = $db->prepare("UPDATE communities SET name = ?, description = ?, image = ? WHERE id = ?");
            $stmt->execute([$name, $desc, $image, $id]);
        }

        $this->redirect('/communities/show?id=' . $id);
    }

    public function delete()
    {
        $this->checkAuth();
        $id = $_POST['id'] ?? null;

        $db = Database::getInstance();
        $stmtCheck = $db->prepare("SELECT user_id FROM communities WHERE id = ?");
        $stmtCheck->execute([$id]);
        $comm = $stmtCheck->fetch();

        if ($comm && $comm['user_id'] == $_SESSION['user']['id']) {
            // Delete related data first (FK constraints usually handle this but explicit is safer for SQLite without strict FKs)
            $db->prepare("DELETE FROM community_posts WHERE topic_id IN (SELECT id FROM community_topics WHERE community_id = ?)")->execute([$id]);
            $db->prepare("DELETE FROM community_topics WHERE community_id = ?")->execute([$id]);
            $db->prepare("DELETE FROM community_members WHERE community_id = ?")->execute([$id]);
            $db->prepare("DELETE FROM communities WHERE id = ?")->execute([$id]);
        }

        $this->redirect('/communities');
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
