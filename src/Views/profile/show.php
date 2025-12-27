<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>orkut - <?= htmlspecialchars($profileUser['name']) ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>

    <div class="container">
        <!-- Left Column -->
        <div class="sidebar-left">
            <div class="profile-pic">
                <?php if (!empty($profileUser['avatar'])): ?>
                    <img src="<?= $profileUser['avatar'] ?>" alt="Avatar">
                <?php else: ?>
                    <img src="https://via.placeholder.com/150" alt="No Avatar">
                <?php endif; ?>
            </div>

            <div style="margin-top: 5px; font-size: 10px; color: #666;">
                <!-- Info -->
                <?= ($profileUser['gender'] == 'M' ? 'masculino' : ($profileUser['gender'] == 'F' ? 'feminino' : 'Outro')) ?>,
                <?= htmlspecialchars($profileUser['relationship_status'] ?? '') ?><br>
                <?= htmlspecialchars($profileUser['city'] ?? '') ?>,
                <?= htmlspecialchars($profileUser['country'] ?? '') ?>

                <!-- Actions -->
                <div style="margin-top: 10px;">
                    <?php if (!$isOwner): ?>
                        <a href="/friends/add?id=<?= $profileUser['id'] ?>" class="add-friend-btn">
                            <span style="color: goldenrod; font-weight: bold;">+</span> amigo
                        </a>
                    <?php endif; ?>
                    <a href="#" class="mais-btn">mais &raquo;</a>
                </div>
            </div>

            <div class="menu-links" style="margin-top: 15px;">
                <a href="/profile?id=<?= $profileUser['id'] ?>">👤 perfil</a>
                <a href="/scraps?uid=<?= $profileUser['id'] ?>">📝 recados</a>
                <a href="/photos?uid=<?= $profileUser['id'] ?>">📷 fotos</a>
                <a href="/videos?uid=<?= $profileUser['id'] ?>">📹 vídeos</a>
                <a href="/testimonials?uid=<?= $profileUser['id'] ?>">💬 depoimentos</a>
            </div>
        </div>

        <!-- Center Column -->
        <div class="main-content">
            <div class="profile-name-header">
                <?= htmlspecialchars($profileUser['name']) ?>
            </div>

            <!-- Stats & Karma Ribbon -->
            <div class="stats-ribbon">
                <div class="stats-item"><a href="/scraps?uid=<?= $profileUser['id'] ?>" style="text-decoration: none; color: inherit;">recados<br><span style="color:#000">📝 <?= count($scraps ?? []) ?></span></a></div>
                <div class="stats-item"><a href="/photos?uid=<?= $profileUser['id'] ?>" style="text-decoration: none; color: inherit;">fotos<br><span style="color:#000">📷 <?= $photosCount ?></span></a></div>
                <div class="stats-item"><a href="/videos?uid=<?= $profileUser['id'] ?>" style="text-decoration: none; color: inherit;">vídeos<br><span style="color:#000">📹 <?= $videosCount ?></span></a></div>
                <div class="stats-item"><a href="#" style="text-decoration: none; color: inherit;">fãs<br><span style="color:#000">⭐ <?= $fansCount ?></span></a></div>

                <!-- Karma Inline -->
                <div class="stats-item"
                    style="margin-left: 10px; display: flex; flex-direction: column; align-items: start;">
                    <div class="karma-group" title="confiável">
                        confiável:
                        <?php for ($i = 0; $i < 3; $i++)
                            echo ($i < $stats['trusty'] ? '🙂' : '<span style="opacity:0.3">🙂</span>'); ?>
                    </div>
                    <div class="karma-group" title="legal">
                        legal:
                        <?php for ($i = 0; $i < 3; $i++)
                            echo ($i < $stats['cool'] ? '🧊' : '<span style="opacity:0.3">🧊</span>'); ?>
                    </div>
                    <div class="karma-group" title="sexy">
                        sexy:
                        <?php for ($i = 0; $i < 3; $i++)
                            echo ($i < $stats['sexy'] ? '❤️' : '<span style="opacity:0.3">❤️</span>'); ?>
                    </div>
                </div>
            </div>

            <!-- Social Box -->
            <div class="social-container">
                <div class="social-header">social</div>
                <div class="social-content">
                    <table class="social-table">
                        <tr>
                            <td class="label-col">relacionamento:</td>
                            <td class="value-col">
                                <?= htmlspecialchars($profileUser['relationship_status'] ?? 'solteiro(a)') ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-col">aniversário:</td>
                            <td class="value-col"><?= htmlspecialchars($profileUser['birthdate'] ?? '') ?></td>
                        </tr>
                        <tr>
                            <td class="label-col">idade:</td>
                            <td class="value-col">
                                <?php
                                if (!empty($profileUser['birthdate'])) {
                                    try {
                                        $dob = new DateTime($profileUser['birthdate']);
                                        $now = new DateTime();
                                        echo $now->diff($dob)->y;
                                    } catch (Exception $e) {
                                        echo '?';
                                    }
                                } else {
                                    echo '?';
                                }
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-col">interesses no orkut:</td>
                            <td class="value-col">amigos, companheiros para atividades, namoro (mulheres)</td>
                        </tr>
                        <tr>
                            <td class="label-col">quem sou eu:</td>
                            <td class="value-col">
                                <div style="max-height: 100px; overflow-y: auto;">
                                    <?= !empty($profileUser['bio']) ? nl2br(htmlspecialchars($profileUser['bio'])) : "..." ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-col">filhos:</td>
                            <td class="value-col">não</td>
                        </tr>
                        <tr>
                            <td class="label-col">etnia:</td>
                            <td class="value-col">caucasiano (branco)</td>
                        </tr>
                        <tr>
                            <td class="label-col">humor:</td>
                            <td class="value-col">extrovertido/extravagante</td>
                        </tr>
                        <tr>
                            <td class="label-col">orientação sexual:</td>
                            <td class="value-col">heterossexual</td>
                        </tr>
                        <tr>
                            <td class="label-col">estilo:</td>
                            <td class="value-col">urbano</td>
                        </tr>
                        <tr>
                            <td class="label-col">fumo:</td>
                            <td class="value-col">não</td>
                        </tr>
                        <tr>
                            <td class="label-col">bebo:</td>
                            <td class="value-col">não</td>
                        </tr>
                        <tr>
                            <td class="label-col">animais de estimação:</td>
                            <td class="value-col">não gosto de animais de estimação</td>
                        </tr>
                        <tr>
                            <td class="label-col">moro:</td>
                            <td class="value-col">com meus pais</td>
                        </tr>
                        <tr>
                            <td class="label-col">cidade natal:</td>
                            <td class="value-col"><?= htmlspecialchars($profileUser['city'] ?? '') ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Scraps Section -->
            <div style="margin-top: 20px;">
                <div class="section-header">
                    recados recentes
                </div>
                <div class="content-box">
                    <?php if (empty($scraps)): ?>
                        <small>Nenhum recado.</small>
                    <?php else: ?>
                        <?php foreach (array_slice($scraps, 0, 5) as $scrap): ?>
                            <div class="scrap-item"
                                style="border: none; border-bottom: 1px solid #ccc; padding: 5px; margin-bottom: 0;">
                                <div class="scrap-header">
                                    <a href="/profile?id=<?= $scrap['sender_id'] ?>">
                                        <img src="<?= $scrap['sender_avatar'] ?? 'https://via.placeholder.com/30' ?>" width="30"
                                            height="30">
                                    </a>
                                    <div>
                                        <b><a
                                                href="/profile?id=<?= $scrap['sender_id'] ?>"><?= htmlspecialchars($scrap['sender_name']) ?></a></b>
                                        <span class="scrap-date">(<?= $scrap['created_at'] ?>)</span>
                                    </div>
                                </div>
                                <div class="scrap-content" style="padding-left: 0;">
                                    <?= nl2br(htmlspecialchars($scrap['content'])) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <div style="text-align: right; font-size: 10px; margin-top: 5px;">
                            [ <a href="/scraps?uid=<?= $profileUser['id'] ?>">ver todos</a> ]
                        </div>
                    <?php endif; ?>

                    <form action="/scraps/store" method="POST"
                        style="background: #FFF4Cffee; padding: 10px; margin-top: 10px; border: 1px solid #F3E6C7;">
                        <input type="hidden" name="receiver_id" value="<?= $profileUser['id'] ?>">
                        <b style="color: #666;">Deixe um recado para
                            <?= htmlspecialchars(explode(' ', $profileUser['name'])[0]) ?>:</b><br>
                        <textarea name="content"
                            style="width: 98%; height: 50px; font-family: Verdana; font-size: 11px; margin-top: 5px;"></textarea><br>
                        <div style="text-align: right; margin-top: 2px;">
                            <button type="submit" class="submit-btn">postar recado</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="sidebar-right">
            <div class="section-header">
                amigos (<?= count($friends) ?>)
            </div>

            <div class="friends-grid">
                <?php foreach (array_slice($friends, 0, 6) as $f): ?>
                    <div class="friend-item">
                        <a href="/profile?id=<?= $f['id'] ?>">
                            <img src="<?= $f['avatar'] ?? 'https://via.placeholder.com/50' ?>"
                                style="width: 50px; height: 50px; object-fit: cover; border: 1px solid #ccc;">
                        </a>
                        <div class="friend-name">
                            <a href="/profile?id=<?= $f['id'] ?>"><?= htmlspecialchars(explode(' ', $f['name'])[0]) ?></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div style="text-align: right; font-size: 10px; margin-top: 5px;">
                <a href="/friends?id=<?= $profileUser['id'] ?>">ver todos</a>
            </div>

            <!-- Legend Box -->
            <div class="legend-box">
                <b>legenda</b>
                <table class="legend-table" cellspacing="0">
                    <tr>
                        <td>⭐</td>
                        <td>sou fã</td>
                    </tr>
                    <tr>
                        <td>🙂</td>
                        <td>confiável</td>
                    </tr>
                    <tr>
                        <td>🙂🙂</td>
                        <td>muito confiável</td>
                    </tr>
                    <tr>
                        <td>🙂🙂🙂</td>
                        <td>super confiável</td>
                    </tr>
                    <tr>
                        <td>🧊</td>
                        <td>legal</td>
                    </tr>
                    <tr>
                        <td>🧊🧊</td>
                        <td>muito legal</td>
                    </tr>
                    <tr>
                        <td>🧊🧊🧊</td>
                        <td>super legal</td>
                    </tr>
                    <tr>
                        <td>❤️</td>
                        <td>sexy</td>
                    </tr>
                    <tr>
                        <td>❤️ ❤️</td>
                        <td>muito sexy</td>
                    </tr>
                    <tr>
                        <td>❤️❤️❤️</td>
                        <td>super sexy</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>

</html>