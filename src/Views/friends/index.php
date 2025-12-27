<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>orkut - amigos</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>

    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar-left">
            <div class="menu-links" style="margin-top: 0;">
                <a href="/">🔙 voltar para home</a>
                <a href="/profile">👤 meu perfil</a>
                <a href="/friends/requests">📩 pedidos de amizade</a>
                <a href="/friends/network">🕸️ rede de amigos</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="welcome-box">
                <h1 style="margin: 0; font-size: 18px; color: #333;">amigos de <?= htmlspecialchars($user['name']) ?>
                </h1>
            </div>

            <div class="section-header">
                todos os amigos (<?= count($friends) ?>)
            </div>

            <div class="content-box">
                <?php if (empty($friends)): ?>
                    <p style="padding: 10px; font-size: 11px;">Nenhum amigo encontrado.</p>
                <?php else: ?>
                    <div class="friends-page-grid">
                        <?php foreach ($friends as $f): ?>
                            <div class="friend-card-large">
                                <a href="/profile?id=<?= $f['id'] ?>">
                                    <img src="<?= $f['avatar'] ?? 'https://via.placeholder.com/90' ?>"
                                        alt="<?= htmlspecialchars($f['name']) ?>" width="90" height="90">
                                </a>
                                <br>
                                <a href="/profile?id=<?= $f['id'] ?>"><b><?= htmlspecialchars($f['name']) ?></b></a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>