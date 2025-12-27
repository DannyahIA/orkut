<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class FanController extends Controller
{
    public function toggle()
    {
        $this->checkAuth();
        $idolId = $_POST['idol_id'] ?? null;
        $fanId = $_SESSION['user']['id'];

        if (!$idolId || $idolId == $fanId) {
            $this->redirect('/profile?id=' . $idolId);
            return;
        }

        $db = Database::getInstance();

        // Check if already a fan
        $stmt = $db->prepare("SELECT id FROM fans WHERE idol_id = ? AND fan_id = ?");
        $stmt->execute([$idolId, $fanId]);
        $existing = $stmt->fetch();

        if ($existing) {
            // Unfan
            $stmtDel = $db->prepare("DELETE FROM fans WHERE id = ?");
            $stmtDel->execute([$existing['id']]);
        } else {
            // Become a fan
            $stmtAdd = $db->prepare("INSERT INTO fans (idol_id, fan_id) VALUES (?, ?)");
            $stmtAdd->execute([$idolId, $fanId]);
        }

        $this->redirect('/profile?id=' . $idolId);
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
