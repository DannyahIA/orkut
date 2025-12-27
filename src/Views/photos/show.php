<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>orkut - álbum: <?= htmlspecialchars($album['title']) ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>

    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar-left">
            <div class="profile-pic">
                <!-- Ideally we'd show the album owner's pic here, but we might need to fetch it or rely on session if owner -->
                <img src="https://via.placeholder.com/150" alt="Avatar">
            </div>
            <div class="menu-links" style="margin-top: 10px;">
                <a href="/photos?uid=<?= $album['user_id'] ?>">🔙 voltar para álbuns</a>
                <a href="/profile?id=<?= $album['user_id'] ?>">👤 perfil</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="welcome-box">
                <h1 style="margin: 0; font-size: 18px; color: #333;">álbum: <?= htmlspecialchars($album['title']) ?>
                </h1>
                <p style="margin: 5px 0 0 0; color: #666;"><?= htmlspecialchars($album['description']) ?></p>
            </div>

            <?php if ($album['user_id'] == ($_SESSION['user']['id'] ?? 0)): ?>
                <div style="background: #E0E7F7; padding: 10px; margin-bottom: 20px; border: 1px solid #C4D5F0;">
                    <a href="/photos/add?album_id=<?= $album['id'] ?>" style="font-weight: bold;">+ adicionar fotos</a>
                </div>
            <?php endif; ?>

            <div class="content-box" style="border: none; padding: 0;">
                <div class="photo-grid">
                    <?php if (empty($photos)): ?>
                        <div style="width:100%; padding: 20px; text-align: center;">Nenhuma foto neste álbum.</div>
                    <?php else: ?>
                        <?php foreach ($photos as $photo): ?>
                            <div class="photo-item">
                                <a href="<?= $photo['image_url'] ?>" target="_blank">
                                    <img src="<?= $photo['image_url'] ?>" class="photo-img">
                                </a>
                                <div class="photo-caption">
                                    <?= htmlspecialchars($photo['caption'] ?? '') ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="sidebar-right">
        </div>
    </div>
</body>

</html>