<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>orkut - álbuns de <?= htmlspecialchars($owner['name']) ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>

    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar-left">
            <div class="profile-pic">
                <img src="<?= $owner['avatar'] ?? 'https://via.placeholder.com/150' ?>" alt="Avatar">
            </div>
            <div class="menu-links" style="margin-top: 10px;">
                <a href="/">🔙 voltar para home</a>
                <a href="/profile?id=<?= $owner['id'] ?>">👤 perfil</a>
                <a href="/scraps?uid=<?= $owner['id'] ?>">📝 recados</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="welcome-box">
                <h1 style="margin: 0; font-size: 18px; color: #333;">álbuns de <?= htmlspecialchars($owner['name']) ?>
                </h1>
            </div>

            <?php if ($owner['id'] == ($_SESSION['user']['id'] ?? 0)): ?>
                <div style="background: #E0E7F7; padding: 10px; margin-bottom: 20px; border: 1px solid #C4D5F0;">
                    <a href="/photos/create_album" style="font-weight: bold;">+ criar novo álbum</a>
                </div>
            <?php endif; ?>

            <div class="content-box" style="border: none; padding: 0;">
                <?php if (empty($albums)): ?>
                    <div style="padding: 20px; text-align: center; background: #fff; border: 1px solid #ccc;">
                        Nenhum álbum encontrado.
                    </div>
                <?php else: ?>
                    <div class="album-grid">
                        <?php foreach ($albums as $album): ?>
                            <div class="album-item">
                                <a href="/photos/album?id=<?= $album['id'] ?>">
                                    <img src="<?= $album['cover_photo_url'] ?? 'https://via.placeholder.com/120' ?>"
                                        class="album-cover">
                                    <br>
                                    <span><?= htmlspecialchars($album['title']) ?></span>
                                </a>
                                <div style="font-size: 9px; color: #666; margin-top: 2px;">
                                    <?= $album['count'] ?> fotos
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Sidebar (Standard) -->
        <div class="sidebar-right">
            <!-- Could show something here, keeping empty/standard for now -->
        </div>
    </div>
</body>

</html>