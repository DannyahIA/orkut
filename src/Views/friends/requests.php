<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>orkut - pedidos de amizade</title>
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
                <a href="/friends/network">🕸️ rede de amigos</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="section-header">
                pedidos de amizade
            </div>

            <div class="content-box">
                <?php if (empty($requests)): ?>
                    <p style="padding: 10px; font-size: 11px;">Você não tem novos pedidos de amizade.</p>
                <?php else: ?>
                    <?php foreach ($requests as $req): ?>
                        <div class="request-item">
                            <div class="request-info">
                                <img src="<?= $req['avatar'] ?? 'https://via.placeholder.com/50' ?>" width="40" height="40"
                                    style="border: 1px solid #ccc;">
                                <div>
                                    <a href="/profile?id=<?= $req['id'] ?>"><b><?= htmlspecialchars($req['name']) ?></b></a>
                                    quer ser seu amigo.
                                    <br>
                                    <span style="color: #666; font-size: 10px;">(<?= $req['mutual'] ?? 0 ?> amigos em
                                        comum)</span>
                                </div>
                            </div>
                            <div class="request-actions">
                                <form action="/friends/accept" method="POST" style="margin: 0;">
                                    <input type="hidden" name="requester_id" value="<?= $req['id'] ?>">
                                    <button type="submit" class="btn-accept">aceitar</button>
                                </form>
                                <form action="/friends/reject" method="POST" style="margin: 0;">
                                    <input type="hidden" name="requester_id" value="<?= $req['id'] ?>">
                                    <button type="submit" class="btn-reject">ignorar</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>