<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class ProfileController extends Controller
{
    public function edit()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        $user = $_SESSION['user'];
        $this->view('profile/edit', ['user' => $user]);
    }

    public function update()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        $user = $_SESSION['user'];
        $userId = $user['id'];

        // Get data
        $relationship = $_POST['relationship'] ?? null;
        $city = $_POST['city'] ?? null;
        $country = $_POST['country'] ?? null;
        $bio = $_POST['bio'] ?? null;
        $interests = $_POST['interests'] ?? null;

        // Handle Avatar Upload
        $avatarPath = $user['avatar'] ?? null;
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = ROOT_PATH . '/public/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileExt = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $fileName = 'user_' . $userId . '_' . time() . '.' . $fileExt;
            $destPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $destPath)) {
                $avatarPath = '/uploads/' . $fileName;
            }
        }

        // Update DB
        $db = Database::getInstance();
        $sql = "UPDATE users SET relationship_status = ?, city = ?, country = ?, bio = ?, interests = ?, avatar = ? WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$relationship, $city, $country, $bio, $interests, $avatarPath, $userId]);

        // Update session
        $_SESSION['user']['relationship_status'] = $relationship;
        $_SESSION['user']['city'] = $city;
        $_SESSION['user']['country'] = $country;
        $_SESSION['user']['bio'] = $bio;
        $_SESSION['user']['interests'] = $interests;
        $_SESSION['user']['avatar'] = $avatarPath;

        $this->redirect('/profile?id=' . $userId);
    }

    public function show()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        $currentUserId = $_SESSION['user']['id'];
        $targetId = $_GET['id'] ?? $currentUserId;

        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$targetId]);
        $profileUser = $stmt->fetch();

        if (!$profileUser) {
            die("Usuário não encontrado.");
        }

        $isOwner = ($currentUserId == $targetId);
        $friendStatus = null;

        if (!$isOwner) {
            $stmt = $db->prepare("SELECT status FROM friends WHERE (requester_id = ? AND addressee_id = ?) OR (requester_id = ? AND addressee_id = ?)");
            $stmt->execute([$currentUserId, $targetId, $targetId, $currentUserId]);
            $relationship = $stmt->fetch();
            if ($relationship) {
                $friendStatus = $relationship['status']; // 'pending' or 'accepted'
            }
        }

        // Get Scraps (Newest first)
        $stmtScraps = $db->prepare("SELECT scraps.*, users.name as sender_name, users.avatar as sender_avatar 
                                    FROM scraps 
                                    JOIN users ON scraps.sender_id = users.id 
                                    WHERE receiver_id = ? 
                                    ORDER BY created_at DESC");
        $stmtScraps->execute([$targetId]);
        $scraps = $stmtScraps->fetchAll();

        // Get Testimonials (Approved only)
        $stmtTesti = $db->prepare("SELECT testimonials.*, users.name as sender_name, users.avatar as sender_avatar 
                                    FROM testimonials 
                                    JOIN users ON testimonials.sender_id = users.id 
                                    WHERE receiver_id = ? AND approved = 1
                                    ORDER BY created_at DESC");
        $stmtTesti->execute([$targetId]);
        $testimonials = $stmtTesti->fetchAll();

        // Count Pending Testimonials (if owner)
        $pendingTestimonialsCount = 0;
        if ($isOwner) {
            $stmtCount = $db->prepare("SELECT COUNT(*) as count FROM testimonials WHERE receiver_id = ? AND approved = 0");
            $stmtCount->execute([$currentUserId]);
            $pendingTestimonialsCount = $stmtCount->fetch()['count'];
        }

        // Stats Calculation
        $stmtStats = $db->prepare("SELECT 
            SUM(trusty) as sum_trusty, 
            SUM(cool) as sum_cool, 
            SUM(sexy) as sum_sexy,
            COUNT(*) as total_voters 
            FROM profile_stats_votes WHERE target_id = ?");
        $stmtStats->execute([$targetId]);
        $statsData = $stmtStats->fetch();

        // Calculate Percentages (Max score per voter is 3. So total possible is total_voters * 3)
        $trustyPct = 0;
        $coolPct = 0;
        $sexyPct = 0;

        if ($statsData['total_voters'] > 0) {
            $maxPossible = $statsData['total_voters'] * 3;
            if ($maxPossible > 0) {
                $trustyPct = round(($statsData['sum_trusty'] / $maxPossible) * 100);
                $coolPct = round(($statsData['sum_cool'] / $maxPossible) * 100);
                $sexyPct = round(($statsData['sum_sexy'] / $maxPossible) * 100);
            }
        }

        // Get My Vote if logged in
        $myVote = ['trusty' => 0, 'cool' => 0, 'sexy' => 0];
        if (!$isOwner) {
            $stmtMyVote = $db->prepare("SELECT * FROM profile_stats_votes WHERE voter_id = ? AND target_id = ?");
            $stmtMyVote->execute([$currentUserId, $targetId]);
            $voteData = $stmtMyVote->fetch();
            if ($voteData) {
                $myVote = $voteData;
            }
        }

        // Fans Count & Status
        $stmtFans = $db->prepare("SELECT COUNT(*) as count FROM fans WHERE idol_id = ?");
        $stmtFans->execute([$targetId]);
        $fansCount = $stmtFans->fetch()['count'];

        $amIFan = false;
        if (!$isOwner) {
            $stmtAmIFan = $db->prepare("SELECT id FROM fans WHERE idol_id = ? AND fan_id = ?");
            $stmtAmIFan->execute([$targetId, $currentUserId]);
            if ($stmtAmIFan->fetch()) {
                $amIFan = true;
            }
        }

        // Photos Count
        $stmtPhotos = $db->prepare("SELECT COUNT(*) as count FROM photos WHERE user_id = ?");
        $stmtPhotos->execute([$targetId]);
        $photosCount = $stmtPhotos->fetch()['count'];

        // Videos Count
        $stmtVideos = $db->prepare("SELECT COUNT(*) as count FROM videos WHERE user_id = ?");
        $stmtVideos->execute([$targetId]);
        $videosCount = $stmtVideos->fetch()['count'];

        // Get Friends (for Right Sidebar)
        $stmtFriends = $db->prepare("SELECT u.id, u.name, u.avatar FROM users u
                                     JOIN friends f ON (u.id = f.requester_id OR u.id = f.addressee_id)
                                     WHERE (f.requester_id = ? OR f.addressee_id = ?)
                                     AND f.status = 'accepted'
                                     AND u.id != ?
                                     LIMIT 9");
        $stmtFriends->execute([$targetId, $targetId, $targetId]);
        $friends = $stmtFriends->fetchAll();

        $this->view('profile/show', [
            'profileUser' => $profileUser,
            'isOwner' => $isOwner,
            'friendStatus' => $friendStatus,
            'scraps' => $scraps,
            'photosCount' => $photosCount,
            'videosCount' => $videosCount,
            'friends' => $friends,
            'testimonials' => $testimonials,
            'pendingTestimonialsCount' => $pendingTestimonialsCount,
            'stats' => [
                'trusty' => $trustyPct,
                'cool' => $coolPct,
                'sexy' => $sexyPct
            ],
            'myVote' => $myVote,
            'fansCount' => $fansCount,
            'amIFan' => $amIFan
        ]);
    }
}
