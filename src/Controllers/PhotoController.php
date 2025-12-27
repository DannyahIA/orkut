<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class PhotoController extends Controller
{
    public function index()
    {
        $this->checkAuth();
        $userId = $_GET['uid'] ?? $_SESSION['user']['id'];

        $db = Database::getInstance();

        // Get User info (owner of albums)
        $stmtUser = $db->prepare("SELECT id, name, avatar FROM users WHERE id = ?");
        $stmtUser->execute([$userId]);
        $owner = $stmtUser->fetch();

        if (!$owner) {
            $this->redirect('/');
            return;
        }

        // Get Albums
        $stmt = $db->prepare("SELECT * FROM albums WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        $albums = $stmt->fetchAll();

        // Get Photo Counts per Album
        foreach ($albums as &$album) {
            $stmtCount = $db->prepare("SELECT COUNT(*) as count FROM photos WHERE album_id = ?");
            $stmtCount->execute([$album['id']]);
            $album['count'] = $stmtCount->fetch()['count'];
        }

        $this->view('photos/albums', ['albums' => $albums, 'owner' => $owner]);
    }

    public function createAlbum()
    {
        $this->checkAuth();
        $this->view('photos/create_album');
    }

    public function storeAlbum()
    {
        $this->checkAuth();
        $title = $_POST['title'] ?? null;
        $desc = $_POST['description'] ?? null;
        $cover = $_POST['cover_photo_url'] ?? null;
        $userId = $_SESSION['user']['id'];

        // Handle File Upload
        if (isset($_FILES['cover_file']) && $_FILES['cover_file']['error'] === UPLOAD_ERR_OK) {
            $uploadedPath = $this->handleUpload($_FILES['cover_file']);
            if ($uploadedPath) {
                $cover = $uploadedPath;
            }
        }

        if ($title) {
            $db = Database::getInstance();
            $stmt = $db->prepare("INSERT INTO albums (user_id, title, description, cover_photo_url) VALUES (?, ?, ?, ?)");
            $stmt->execute([$userId, $title, $desc, $cover]);
        }

        $this->redirect('/photos?uid=' . $userId);
    }

    public function showAlbum()
    {
        $this->checkAuth();
        $albumId = $_GET['id'] ?? null;

        if (!$albumId) {
            $this->redirect('/photos');
            return;
        }

        $db = Database::getInstance();

        // Get Album
        $stmt = $db->prepare("SELECT * FROM albums WHERE id = ?");
        $stmt->execute([$albumId]);
        $album = $stmt->fetch();

        if (!$album)
            die("Álbum não encontrado.");

        $_SESSION['user']['id'];

        // Get Photos
        $stmtPhotos = $db->prepare("SELECT * FROM photos WHERE album_id = ? ORDER BY created_at DESC");
        $stmtPhotos->execute([$albumId]);
        $photos = $stmtPhotos->fetchAll();

        $this->view('photos/show', ['album' => $album, 'photos' => $photos]);
    }

    public function addPhoto()
    {
        $this->checkAuth();
        $albumId = $_GET['album_id'] ?? null;
        $this->view('photos/add_photo', ['album_id' => $albumId]);
    }

    public function storePhoto()
    {
        $this->checkAuth();
        $albumId = $_POST['album_id'] ?? null;
        $url = $_POST['image_url'] ?? null;
        $caption = $_POST['caption'] ?? null;
        $userId = $_SESSION['user']['id'];

        // Handle File Upload
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $uploadedPath = $this->handleUpload($_FILES['image_file']);
            if ($uploadedPath) {
                $url = $uploadedPath;
            }
        }

        if ($albumId && $url) {
            $db = Database::getInstance();
            $stmt = $db->prepare("INSERT INTO photos (album_id, user_id, image_url, caption) VALUES (?, ?, ?, ?)");
            $stmt->execute([$albumId, $userId, $url, $caption]);

            // Update album cover if empty
            $stmtAlb = $db->prepare("SELECT cover_photo_url FROM albums WHERE id = ?");
            $stmtAlb->execute([$albumId]);
            if (empty($stmtAlb->fetch()['cover_photo_url'])) {
                $stmtUpd = $db->prepare("UPDATE albums SET cover_photo_url = ? WHERE id = ?");
                $stmtUpd->execute([$url, $albumId]);
            }
        }

        $this->redirect("/photos/album?id=$albumId");
    }

    private function handleUpload($file)
    {
        $targetDir = __DIR__ . '/../../public/uploads/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = basename($file['name']);
        $targetFile = $targetDir . uniqid() . '_' . $fileName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Validation
        $check = getimagesize($file['tmp_name']);
        if ($check === false)
            return null; // Not an image

        // Allow certain formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            return null;
        }

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            // Return relative path
            return '/uploads/' . basename($targetFile);
        }
        return null;
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
