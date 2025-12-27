<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class FriendController extends Controller
{
    public function add()
    {
        $this->checkAuth();

        $requesterId = $_SESSION['user']['id'];
        $addresseeId = $_POST['friend_id'] ?? null;

        if (!$addresseeId || $requesterId == $addresseeId) {
            $this->redirect('/');
            return;
        }

        $db = Database::getInstance();

        // Check if already friends or pending
        $stmt = $db->prepare("SELECT * FROM friends WHERE (requester_id = ? AND addressee_id = ?) OR (requester_id = ? AND addressee_id = ?)");
        $stmt->execute([$requesterId, $addresseeId, $addresseeId, $requesterId]);

        if ($stmt->rowCount() == 0) {
            $stmt = $db->prepare("INSERT INTO friends (requester_id, addressee_id, status) VALUES (?, ?, 'pending')");
            $stmt->execute([$requesterId, $addresseeId]);
        }

        $this->redirect('/profile?id=' . $addresseeId);
    }

    public function accept()
    {
        $this->checkAuth();
        $requesterId = $_POST['requester_id'] ?? null;
        $myId = $_SESSION['user']['id'];

        if ($requesterId) {
            $db = Database::getInstance();
            $stmt = $db->prepare("UPDATE friends SET status = 'accepted' WHERE requester_id = ? AND addressee_id = ?");
            $stmt->execute([$requesterId, $myId]);
        }

        $this->redirect('/friends/requests');
    }

    public function reject()
    {
        $this->checkAuth();
        $requesterId = $_POST['requester_id'] ?? null;
        $myId = $_SESSION['user']['id'];

        if ($requesterId) {
            $db = Database::getInstance();
            $stmt = $db->prepare("DELETE FROM friends WHERE requester_id = ? AND addressee_id = ?");
            $stmt->execute([$requesterId, $myId]);
        }

        $this->redirect('/friends/requests');
    }

    public function requests()
    {
        $this->checkAuth();
        $myId = $_SESSION['user']['id'];
        $db = Database::getInstance();

        // Get pending requests
        $sql = "SELECT users.id, users.name, users.avatar FROM users 
                JOIN friends ON users.id = friends.requester_id 
                WHERE friends.addressee_id = ? AND friends.status = 'pending'";
        $stmt = $db->prepare($sql);
        $stmt->execute([$myId]);
        $requests = $stmt->fetchAll();

        $this->view('friends/requests', ['requests' => $requests]);
    }

    public function index()
    {
        $this->checkAuth();
        $userId = $_GET['uid'] ?? $_SESSION['user']['id']; // View my friends or someone else's
        $db = Database::getInstance();

        // Get friends (accepted)
        // complex query because friend can be requester or addressee
        $sql = "SELECT users.id, users.name, users.avatar FROM users 
                JOIN friends ON (users.id = friends.requester_id OR users.id = friends.addressee_id)
                WHERE (friends.requester_id = ? OR friends.addressee_id = ?) 
                AND friends.status = 'accepted'
                AND users.id != ?";

        $stmt = $db->prepare($sql);
        $stmt->execute([$userId, $userId, $userId]);
        $friends = $stmt->fetchAll();

        // Get User Name for the title
        $stmtUser = $db->prepare("SELECT name FROM users WHERE id = ?");
        $stmtUser->execute([$userId]);
        $user = $stmtUser->fetch();

        $this->view('friends/index', ['friends' => $friends, 'user' => $user]);
    }

    public function network()
    {
        $this->checkAuth();
        $this->view('friends/network');
    }

    public function networkJson()
    {
        $this->checkAuth();
        $userId = $_SESSION['user']['id'];
        $db = Database::getInstance();

        // 1. Get central user (me)
        $nodes = [];
        $edges = [];

        $stmtMe = $db->prepare("SELECT id, name, avatar FROM users WHERE id = ?");
        $stmtMe->execute([$userId]);
        $me = $stmtMe->fetch();

        $nodes[] = [
            'id' => $me['id'],
            'label' => $me['name'],
            'image' => $me['avatar'] ?? 'https://via.placeholder.com/50',
            'shape' => 'circularImage',
            'color' => '#B0235F',
            'size' => 30
        ];

        // 2. Get my friends (Level 1)
        // complex query because friend can be requester or addressee
        $sql = "SELECT users.id, users.name, users.avatar FROM users 
                JOIN friends ON (users.id = friends.requester_id OR users.id = friends.addressee_id)
                WHERE (friends.requester_id = ? OR friends.addressee_id = ?) 
                AND friends.status = 'accepted'
                AND users.id != ?";

        $stmt = $db->prepare($sql);
        $stmt->execute([$userId, $userId, $userId]);
        $friends = $stmt->fetchAll();

        $friendIds = [];

        foreach ($friends as $f) {
            $nodes[] = [
                'id' => $f['id'],
                'label' => $f['name'],
                'image' => $f['avatar'] ?? 'https://via.placeholder.com/50',
                'shape' => 'circularImage',
                'size' => 20
            ];
            // Connected to ME
            $edges[] = ['from' => $userId, 'to' => $f['id']];

            $friendIds[] = $f['id'];
        }

        // 3. Get connections between friends (Level 1.5 - The Web)
        if (!empty($friendIds)) {
            // Create placeholders for IN clause: ?,?,?
            $placeholders = implode(',', array_fill(0, count($friendIds), '?'));
            $params = array_merge($friendIds, $friendIds); // We need the IDs twice

            $sqlInter = "SELECT requester_id, addressee_id FROM friends 
                         WHERE requester_id IN ($placeholders) 
                         AND addressee_id IN ($placeholders) 
                         AND status = 'accepted'";

            $stmtInter = $db->prepare($sqlInter);
            $stmtInter->execute($params);
            $interConnections = $stmtInter->fetchAll();

            foreach ($interConnections as $conn) {
                // Add edge between friend A and friend B
                $edges[] = ['from' => $conn['requester_id'], 'to' => $conn['addressee_id']];
            }
        }

        header('Content-Type: application/json');
        echo json_encode(['nodes' => $nodes, 'edges' => $edges]);
        exit;
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
