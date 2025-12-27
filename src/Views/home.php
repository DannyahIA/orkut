<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>orkut - home</title>
    <link rel="stylesheet" href="/css/style.css">
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            setTimeout(function () {
                var overlay = document.getElementById('loading-overlay');
                if (overlay) {
                    overlay.style.display = 'none';
                }
            }, 800);
        });
    </script>
</head>

<body>
    <!-- Loading Screen (Optional, keeping as requested/existing) -->
    <div id="loading-overlay"
        style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.9); z-index: 9999; display: flex; justify-content: center; align-items: center; flex-direction: column;">
        <div class="donut-spinner"
            style="width: 50px; height: 50px; border: 5px solid #ccc; border-top: 5px solid #B0235F; border-radius: 50%; animation: spin 1s linear infinite;">
        </div>
        <div style="margin-top: 10px; color: #3B5998; font-weight: bold;">carregando...</div>
    </div>
    <style>
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <?php require __DIR__ . '/partials/header.php'; ?>

    <div class="container">
        <!-- Left Column -->
        <div class="sidebar-left">
            <div class="profile-pic">
                <?php if (!empty($user['avatar'])): ?>
                    <img src="<?= $user['avatar'] ?>" alt="Avatar">
                <?php else: ?>
                    <img src="https://via.placeholder.com/150" alt="No Avatar">
                <?php endif; ?>
            </div>

            <div class="user-info-box">
                <!-- Placeholder for Status -->
                off-line<br>
                <br>
                <?= htmlspecialchars($user['name']) ?><br>
                <?= ($user['gender'] == 'M' ? 'Masculino' : ($user['gender'] == 'F' ? 'Feminino' : 'Outro')) ?><br>
                <?= htmlspecialchars($user['relationship_status'] ?? '') ?><br>
                <?= htmlspecialchars($user['city'] ?? '') ?>
            </div>

            <div class="menu-links">
                <a href="/profile">👤 perfil</a>
                <a href="/profile?id=<?= $user['id'] ?>">📝 recados</a>
                <a href="/photos?uid=<?= $user['id'] ?>">📷 fotos</a>
                <a href="#">📹 vídeos</a>
                <a href="/profile?id=<?= $user['id'] ?>">💬 depoimentos</a>
            </div>

            <div class="section-header" style="font-size: 10px;">Apps</div>
            <a href="#" style="font-size: 10px; padding-left: 5px;">+ adicionar apps</a>
        </div>

        <!-- Center Column -->
        <div class="main-content">
            <?php if (!empty($pendingTestimonials) && $pendingTestimonials > 0): ?>
                <div
                    style="background: #FFF9D7; border: 1px solid #E2C822; padding: 10px; margin-bottom: 10px; font-size: 11px;">
                    <img src="https://via.placeholder.com/15/FFFF00/000000?text=!" style="vertical-align: bottom;">
                    Você tem <a href="/testimonials/pending"><b><?= $pendingTestimonials ?> depoimento(s) novo(s)</b></a>
                    aguardando aprovação.
                </div>
            <?php endif; ?>

            <!-- Welcome Box -->
            <div class="welcome-box">
                <h1 style="margin: 0; font-size: 18px; color: #333;">Bem-vindo(a),
                    <?= htmlspecialchars($user['name']) ?></h1>
            </div>

            <!-- Status Box -->
            <div class="status-box">
                <span style="color: #666;">Defina seu status aqui</span>
                <button
                    style="border: 1px solid #F3E6C7; background: white; cursor: pointer; color: #0045AD; font-size: 10px;">editar</button>
            </div>

            <!-- Stats Row with Icons -->
            <div class="stats-row">
                <div style="text-align: center;">recados<br><a href="/profile?id=<?= $user['id'] ?>">📝
                        <?= $scrapsCount ?></a></div>
                <div style="text-align: center;">fotos<br><a href="/photos?uid=<?= $user['id'] ?>">📷
                        <?= $photosCount ?></a></div>
                <div style="text-align: center;">fãs<br><a href="/profile?id=<?= $user['id'] ?>">⭐ <?= $fansCount ?></a>
                </div>
                <div style="text-align: center;">mensagens<br><a href="#">📧 0</a></div>
            </div>

            <!-- Misc Info -->
            <div style="margin-bottom: 10px;">
                <b>Sorte de hoje:</b> "<?= htmlspecialchars($luck) ?>"
            </div>

            <!-- Updates -->
            <div class="section-header">
                Atualizações
            </div>
            <div class="content-box" style="min-height: 100px;">
                <?php if (empty($updates)): ?>
                    <small>Nenhuma atualização recente.</small>
                <?php else: ?>
                    <table width="100%" cellspacing="0">
                        <?php foreach ($updates as $up): ?>
                            <tr>
                                <td style="border-bottom: 1px solid #eee; padding: 4px;">
                                    <img src="https://via.placeholder.com/15?text=T" style="vertical-align: middle;">
                                    <a
                                        href="/communities/topic?id=<?= $up['id'] ?>"><b><?= htmlspecialchars($up['title']) ?></b></a>
                                    <span style="color: #666; font-size: 10px;">
                                        - <a
                                            href="/communities/show?id=<?= $up['community_id'] ?>"><?= htmlspecialchars($up['community_name']) ?></a>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>
            </div>

            <!-- Birthdays -->
            <div class="section-header" style="margin-top: 10px;">
                Próximos aniversários
            </div>
            <div class="content-box">
                <small>Nenhum aniversário nos próximos dias.</small>
            </div>
        </div>

        <!-- Right Column -->
        <div class="sidebar-right">
            <div class="section-header">
                meus amigos (<?= $friendsCount ?>)
            </div>
            <div style="background: #E0E7F7; padding: 5px;">
                <input type="text" placeholder="procurar amigos"
                    style="width: 95%; font-size: 10px; border: 1px solid #999;">
            </div>

            <div class="friends-grid">
                <?php if ($friendsCount == 0): ?>
                    <small style="grid-column: span 3;">você não tem amigos ainda :(</small>
                <?php else: ?>
                    <?php foreach ($sidebarFriends as $f): ?>
                        <div class="friend-item">
                            <a href="/profile?id=<?= $f['id'] ?>" title="<?= htmlspecialchars($f['name']) ?>">
                                <img src="<?= $f['avatar'] ?? 'https://via.placeholder.com/50' ?>">
                            </a>
                            <div class="friend-name">
                                <a href="/profile?id=<?= $f['id'] ?>"><?= htmlspecialchars(explode(' ', $f['name'])[0]) ?></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div style="text-align: right; font-size: 10px; margin-top: 5px;">
                <a href="/friends">ver todos</a> | <a href="/friends">gerenciar</a>
            </div>

            <div class="section-header" style="margin-top: 20px;">
                meus seguidores
            </div>
            <div style="padding: 10px; text-align: center; color: #666;">
                0 seguidores
            </div>
        </div>
    </div>
</body>

</html>