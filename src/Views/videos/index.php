<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>orkut - vídeos de <?= htmlspecialchars($videoOwner['name']) ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>

    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar-left">
            <div class="profile-pic">
                 <img src="<?= $videoOwner['avatar'] ?? 'https://via.placeholder.com/150' ?>" alt="Avatar">
            </div>
             <div class="menu-links" style="margin-top: 10px;">
                <a href="/">🔙 voltar para home</a>
                <a href="/profile?id=<?= $videoOwner['id'] ?>">👤 perfil</a>
                <a href="/scraps?uid=<?= $videoOwner['id'] ?>">📝 recados</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="welcome-box">
                <h1 style="margin: 0; font-size: 18px; color: #333;">vídeos de <?= htmlspecialchars($videoOwner['name']) ?></h1>
            </div>

            <?php if ($videoOwner['id'] == ($_SESSION['user']['id'] ?? 0)): ?>
                <div style="background: #E0E7F7; padding: 10px; margin-bottom: 20px; border: 1px solid #C4D5F0;">
                    <a href="/videos/create" style="font-weight: bold;">+ adicionar vídeo do YouTube</a>
                </div>
            <?php endif; ?>

            <div class="content-box" style="border: none; padding: 0;">
                <?php if (empty($videos)): ?>
                    <div style="padding: 20px; text-align: center; background: #fff; border: 1px solid #ccc;">
                        Nenhum vídeo encontrado.
                    </div>
                <?php else: ?>
                    <div class="video-grid" style="display: flex; flex-wrap: wrap; gap: 20px;">
                        <?php foreach ($videos as $video): ?>
                            <div class="video-item" style="width: 240px; background: #E5E5E5; padding: 5px; border: 1px solid #ccc;">
                                <div style="margin-bottom: 5px;">
                                    <b><?= htmlspecialchars($video['title']) ?></b>
                                </div>
                                <?php 
                                    // Extract ID again just in case or use what's stored if we stored full URL (controller logic stored full URL but validated ID)
                                    // Actually controller stored full URL. Let's extract ID for embed.
                                    preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $video['youtube_url'], $matches);
                                    $vidId = $matches[1] ?? '';
                                ?>
                                <?php if($vidId): ?>
                                    <iframe width="230" height="170" src="https://www.youtube.com/embed/<?= $vidId ?>" frameborder="0" allowfullscreen></iframe>
                                <?php else: ?>
                                    <div style="width: 230px; height: 170px; background: #000; color: #fff; display: flex; align-items: center; justify-content: center;">Erro no vídeo</div>
                                <?php endif; ?>
                                
                                <?php if ($videoOwner['id'] == ($_SESSION['user']['id'] ?? 0)): ?>
                                    <div style="margin-top: 5px; text-align: right;">
                                        <form action="/videos/delete" method="POST" onsubmit="return confirm('Apagar vídeo?');">
                                            <input type="hidden" name="id" value="<?= $video['id'] ?>">
                                            <button type="submit" style="font-size: 10px; color: red; cursor: pointer;">excluir</button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

         <div class="sidebar-right">
        </div>
    </div>
</body>
</html>
