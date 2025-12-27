<?php

namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
            return;
        }

        $user = $_SESSION['user'];
        $db = \App\Core\Database::getInstance();

        // Get Friends Count
        $stmtFriends = $db->prepare("SELECT COUNT(*) as count FROM friends WHERE (requester_id = ? OR addressee_id = ?) AND status = 'accepted'");
        $stmtFriends->execute([$user['id'], $user['id']]);
        $friendsCount = $stmtFriends->fetch()['count'];

        // Get Communities Count
        $stmtComm = $db->prepare("SELECT COUNT(*) as count FROM community_members WHERE user_id = ?");
        $stmtComm->execute([$user['id']]);
        $communitiesCount = $stmtComm->fetch()['count'];

        // Get Pending Requests Count (for sidebar notification)
        $stmtReq = $db->prepare("SELECT COUNT(*) as count FROM friends WHERE addressee_id = ? AND status = 'pending'");
        $stmtReq->execute([$user['id']]);
        $requestsCount = $stmtReq->fetch()['count'];

        // Sorte do Dia Logic
        $quotes = [
            "O sucesso é ir de fracasso em fracasso sem perder o entusiasmo.",
            "Não sabendo que era impossível, foi lá e fez.",
            "Acredite em milagres, mas não dependa deles.",
            "Quem tem amigos, tem tudo.",
            "A vida é uma peça de teatro que não permite ensaios.",
            "Tudo vale a pena se a alma não é pequena.",
            "O que não provoca minha morte faz com que eu fique mais forte.",
            "Penso, logo existo."
        ];
        $luck = $quotes[array_rand($quotes)];

        // Get Dynamic Stats (Recados, Fotos, Fãs)
        $stmtScraps = $db->prepare("SELECT COUNT(*) as count FROM scraps WHERE receiver_id = ?");
        $stmtScraps->execute([$user['id']]);
        $scrapsCount = $stmtScraps->fetch()['count'];

        $stmtPhotos = $db->prepare("SELECT COUNT(*) as count FROM photos WHERE user_id = ?");
        $stmtPhotos->execute([$user['id']]);
        $photosCount = $stmtPhotos->fetch()['count'];

        $stmtFans = $db->prepare("SELECT COUNT(*) as count FROM fans WHERE idol_id = ?");
        $stmtFans->execute([$user['id']]);
        $fansCount = $stmtFans->fetch()['count'];

        // Get Recent Community Updates (Topics from my communities)
        $stmtUpdates = $db->prepare("
            SELECT t.id, t.title, t.created_at, c.id as community_id, c.name as community_name, u.name as author_name
            FROM community_topics t
            JOIN communities c ON t.community_id = c.id
            JOIN community_members cm ON c.id = cm.community_id
            JOIN users u ON t.user_id = u.id
            WHERE cm.user_id = ?
            ORDER BY t.created_at DESC
            LIMIT 10
        ");
        $stmtUpdates->execute([$user['id']]);
        $updates = $stmtUpdates->fetchAll();

        // Get Top 9 Friends for Sidebar
        $stmtFriends = $db->prepare("SELECT u.id, u.name, u.avatar FROM users u
                                     JOIN friends f ON (u.id = f.requester_id OR u.id = f.addressee_id)
                                     WHERE (f.requester_id = ? OR f.addressee_id = ?)
                                     AND f.status = 'accepted'
                                     AND u.id != ?
                                     LIMIT 9");
        $stmtFriends->execute([$user['id'], $user['id'], $user['id']]);
        $sidebarFriends = $stmtFriends->fetchAll();

        // Get pending testimonials count
        $stmtTesti = $db->prepare("SELECT COUNT(*) FROM testimonials WHERE receiver_id = ? AND approved = 0");
        $stmtTesti->execute([$user['id']]);
        $pendingTestimonials = $stmtTesti->fetchColumn();

        $this->view('home', [
            'user' => $user,
            'friendsCount' => $friendsCount,
            'communitiesCount' => $communitiesCount,
            'requestsCount' => $requestsCount,
            'luck' => $luck,
            'scrapsCount' => $scrapsCount,
            'photosCount' => $photosCount,
            'fansCount' => $fansCount,
            'updates' => $updates,
            'sidebarFriends' => $sidebarFriends
        ]);
    }
}
