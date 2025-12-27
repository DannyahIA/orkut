<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>orkut - depoimentos pendentes</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <?php require __DIR__ . '/../../partials/header.php'; // Adjusted path ?>

    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar-left">
            <div class="menu-links">
                <a href="/">🔙 voltar para home</a>
                <a href="/profile">👤 meu perfil</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="welcome-box">
                <h1 style="margin: 0; font-size: 18px; color: #333;">depoimentos pendentes</h1>
            </div>

            <div class="content-box" style="border: none; padding: 0;">
                <?php if (empty($testimonials)): ?>
                    <div style="padding: 20px; text-align: center; background: #fff; border: 1px solid #ccc;">
                        Nenhum depoimento pendente.
                    </div>
                <?php else: ?>
                    <?php foreach ($testimonials as $testi): ?>
                        <div class="scrap-item" style="display: flex; gap: 10px;">
                            <div style="flex: 1;">
                                <div class="scrap-header">
                                    <a href="/profile?id=<?= $testi['sender_id'] ?>">
                                        <img src="<?= $testi['sender_avatar'] ?? 'https://via.placeholder.com/30' ?>" width="30"
                                            height="30">
                                    </a>
                                    <div>
                                        <b><a
                                                href="/profile?id=<?= $testi['sender_id'] ?>"><?= htmlspecialchars($testi['sender_name']) ?></a></b>
                                        <span class="scrap-date"><?= $testi['created_at'] ?></span>
                                    </div>
                                </div>
                                <div class="scrap-content">
                                    <?= nl2br(htmlspecialchars($testi['content'])) ?>
                                </div>
                                <div style="margin-top: 10px; text-align: right;">
                                    <form action="/testimonials/approve" method="POST" style="display:inline;">
                                        <input type="hidden" name="testimonial_id" value="<?= $testi['id'] ?>">
                                        <button type="submit" class="submit-btn" style="background: #4CAF50;">aceitar</button>
                                    </form>
                                    <form action="/testimonials/delete" method="POST" style="display:inline; margin-left: 5px;">
                                        <input type="hidden" name="testimonial_id" value="<?= $testi['id'] ?>">
                                        <input type="hidden" name="profile_id" value="<?= $_SESSION['user']['id'] ?>">
                                        <button type="submit" class="submit-btn" style="background: #F44336;">recusar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>