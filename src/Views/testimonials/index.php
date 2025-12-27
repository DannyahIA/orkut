<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>orkut - depoimentos</title>
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
                <a href="/scraps?uid=<?= $profileUser['id'] ?>">📝 recados</a>
                <a href="/photos?uid=<?= $profileUser['id'] ?>">📷 fotos</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="welcome-box">
                <h1 style="margin: 0; font-size: 18px; color: #333;">depoimentos de
                    <?= htmlspecialchars($profileUser['name']) ?></h1>
            </div>

            <!-- Write Testimonial Box -->
            <div
                style="background: #E0E7F7; padding: 15px; margin-bottom: 20px; border: 1px solid #C4D5F0; border-radius: 5px;">
                <form action="/testimonials/store" method="POST">
                    <input type="hidden" name="receiver_id" value="<?= $profileUser['id'] ?>">
                    <b style="color: #333;">Escreva um depoimento:</b><br>
                    <textarea name="content"
                        style="width: 98%; height: 80px; font-family: Verdana; font-size: 11px; margin-top: 5px; border: 1px solid #999; padding: 3px;"></textarea><br>
                    <div style="text-align: left; margin-top: 5px;">
                        <button type="submit" class="submit-btn" style="background: #688AD4; padding: 4px 10px;">enviar
                            depoimento</button>
                    </div>
                </form>
            </div>

            <div class="section-header">
                depoimentos (<?= count($testimonials) ?>)
            </div>

            <div class="content-box" style="border: none; padding: 0;">
                <?php if (empty($testimonials)): ?>
                    <div style="padding: 20px; text-align: center; background: #fff; border: 1px solid #ccc;">
                        Nenhum depoimento ainda.
                    </div>
                <?php else: ?>
                    <?php foreach ($testimonials as $testi): ?>
                        <div class="scrap-item" style="display: flex; gap: 10px;">
                            <div style="flex: 1;">
                                <div class="scrap-header"
                                    style="background: transparent; border-bottom: 1px dotted #ccc; padding-bottom: 3px;">
                                    <a href="/profile?id=<?= $testi['sender_id'] ?>">
                                        <img src="<?= $testi['sender_avatar'] ?? 'https://via.placeholder.com/30' ?>" width="30"
                                            height="30" style="border: 1px solid #999;">
                                    </a>
                                    <div style="display: flex; flex-direction: column;">
                                        <b><a
                                                href="/profile?id=<?= $testi['sender_id'] ?>"><?= htmlspecialchars($testi['sender_name']) ?></a></b>
                                        <span class="scrap-date"><?= $testi['created_at'] ?></span>
                                    </div>
                                    <?php if ($testi['sender_id'] == $_SESSION['user']['id'] || $profileUser['id'] == $_SESSION['user']['id']): ?>
                                        <div style="margin-left: auto;">
                                            <form action="/testimonials/delete" method="POST"
                                                onsubmit="return confirm('Apagar este depoimento?');" style="margin:0;">
                                                <input type="hidden" name="testimonial_id" value="<?= $testi['id'] ?>">
                                                <input type="hidden" name="profile_id" value="<?= $profileUser['id'] ?>">
                                                <button type="submit"
                                                    style="background:none; border:none; color:red; cursor:pointer;"
                                                    title="Apagar">🗑️</button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="scrap-content" style="padding: 10px 0; padding-left: 0;">
                                    <?= nl2br(htmlspecialchars($testi['content'])) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
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