<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class StatsController extends Controller
{
    public function vote()
    {
        $this->checkAuth();

        $voterId = $_SESSION['user']['id'];
        $targetId = $_POST['target_id'] ?? null;
        $type = $_POST['type'] ?? null; // 'trusty', 'cool', 'sexy'
        $value = $_POST['value'] ?? 0; // 0, 1, 2, 3

        if (!$targetId || !$type || $voterId == $targetId) {
            $this->redirect('/profile?id=' . $targetId);
            return;
        }

        // Validate type
        if (!in_array($type, ['trusty', 'cool', 'sexy'])) {
            $this->redirect('/profile?id=' . $targetId);
            return;
        }

        $db = Database::getInstance();

        // Check if vote exists
        $stmt = $db->prepare("SELECT id FROM profile_stats_votes WHERE voter_id = ? AND target_id = ?");
        $stmt->execute([$voterId, $targetId]);
        $existing = $stmt->fetch();

        if ($existing) {
            // Update
            $sql = "UPDATE profile_stats_votes SET $type = ? WHERE id = ?";
            $stmtUpdate = $db->prepare($sql);
            $stmtUpdate->execute([$value, $existing['id']]);
        } else {
            // Insert (initialize others to 0)
            $sql = "INSERT INTO profile_stats_votes (voter_id, target_id, $type) VALUES (?, ?, ?)";
            $stmtInsert = $db->prepare($sql);
            $stmtInsert->execute([$voterId, $targetId, $value]);
        }

        // Recalculate Average (CACHE IT in users table for performance? Or just calc on fly?)
        // For now, let's just redirect. The ProfileController will calculate on fly.
        // In a real high-scale app, we would cache this.

        $this->redirect('/profile?id=' . $targetId);
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
