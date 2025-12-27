<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>orkut - recados</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>

    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar-left">
            <div class="profile-pic">
                <img src="<?= $profileUser['avatar'] ?? 'https://via.placeholder.com/150' ?>" alt="Avatar">
            </div>
            <div class="menu-links" style="margin-top: 10px;">
                <a href="/">🔙 voltar para home</a>
                <a href="/profile?id=<?= $profileUser['id'] ?>">👤 perfil</a>
                <a href="/photos?uid=<?= $profileUser['id'] ?>">📷 fotos</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="welcome-box">
                <h1 style="margin: 0; font-size: 18px; color: #333;">recados de
                    <?= htmlspecialchars($profileUser['name']) ?></h1>
            </div>

            <!-- Post Scrap Box -->
            <div
                style="background: #E0E7F7; padding: 15px; margin-bottom: 20px; border: 1px solid #C4D5F0; border-radius: 5px;">
                <form action="/scraps/store" method="POST">
                    <input type="hidden" name="receiver_id" value="<?= $profileUser['id'] ?>">
                    <b style="color: #333;">Deixe um recado:</b><br>
                    <textarea name="content"
                        style="width: 98%; height: 80px; font-family: Verdana; font-size: 11px; margin-top: 5px; border: 1px solid #999; padding: 3px;"></textarea><br>
                    <div style="text-align: left; margin-top: 5px;">
                        <button type="submit" class="submit-btn" style="background: #688AD4; padding: 4px 10px;">postar
                            recado</button>
                    </div>
                </form>
            </div>

            <div class="section-header" style="display: flex; justify-content: space-between; align-items: center;">
                <span>recados (<?= count($scraps) ?>)</span>
                <span style="font-weight: normal; font-size: 9px;">
                    primeira | &lt; anterior | próxima &gt; | última
                </span>
            </div>

            <div class="content-box" style="border: none; padding: 0;">
                <div
                    style="background: #C4D5F0; padding: 5px; text-align: right; border: 1px solid #C4D5F0; margin-bottom: 2px;">
                    <button class="action-btn-red" style="width: auto; padding: 2px 10px;">excluir selecionados</button>
                </div>

                <?php if (empty($scraps)): ?>
                    <div style="padding: 20px; text-align: center; background: #fff; border: 1px solid #ccc;">
                        Nenhum recado ainda. Seja o primeiro a escrever!
                    </div>
                <?php else: ?>
                    <?php foreach ($scraps as $scrap): ?>
                        <div class="scrap-item" style="display: flex; gap: 10px;">
                            <div style="width: 20px; text-align: center; padding-top: 5px;">
                                <input type="checkbox">
                            </div>
                            <div style="flex: 1;">
                                <div class="scrap-header"
                                    style="background: transparent; border-bottom: 1px dotted #ccc; padding-bottom: 3px;">
                                    <a href="/profile?id=<?= $scrap['sender_id'] ?>">
                                        <img src="<?= $scrap['sender_avatar'] ?? 'https://via.placeholder.com/30' ?>" width="30"
                                            height="30">
                                    </a>
                                    <div style="display: flex; flex-direction: column;">
                                        <b><a
                                                href="/profile?id=<?= $scrap['sender_id'] ?>"><?= htmlspecialchars($scrap['sender_name']) ?></a></b>
                                        <span class="scrap-date"><?= $scrap['created_at'] ?></span>
                                    </div>
                                    <?php if ($scrap['sender_id'] == $_SESSION['user']['id'] || $profileUser['id'] == $_SESSION['user']['id']): ?>
                                        <div style="margin-left: auto;">
                                            <form action="/scraps/delete" method="POST"
                                                onsubmit="return confirm('Apagar este recado?');" style="margin:0;">
                                                <input type="hidden" name="scrap_id" value="<?= $scrap['id'] ?>">
                                                <input type="hidden" name="profile_id" value="<?= $profileUser['id'] ?>">
                                                <button type="submit"
                                                    style="background:none; border:none; color:red; cursor:pointer;"
                                                    title="Apagar">🗑️</button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="scrap-content" style="padding: 10px 0; padding-left: 0;">
                                    <?= nl2br(htmlspecialchars($scrap['content'])) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <div
                    style="background: #C4D5F0; padding: 5px; text-align: right; border: 1px solid #C4D5F0; margin-top: 2px;">
                    <button class="action-btn-red" style="width: auto; padding: 2px 10px;">excluir selecionados</button>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="sidebar-right">
            <div class="section-header">
                amigos
            </div>
            <div class="content-box">
                <p style="padding: 10px; font-size: 11px;">
                    <a href="/friends?uid=<?= $profileUser['id'] ?>">Ver todos os amigos</a>
                </p>
            </div>
        </div>
    </div>
</body>

</html>