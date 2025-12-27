<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>orkut - <?= htmlspecialchars($community['name']) ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>

    <div class="container">
        <!-- Left Sidebar (Community Image & Actions) -->
        <div class="sidebar-left">
            <div class="community-img-box">
                <img src="https://via.placeholder.com/150?text=Community" alt="Community">
            </div>

            <?php if ($isMember): ?>
                <form action="/communities/leave" method="POST">
                    <input type="hidden" name="community_id" value="<?= $community['id'] ?>">
                    <button type="submit" class="action-btn-red">deixar comunidade</button>
                </form>
            <?php else: ?>
                <form action="/communities/join" method="POST">
                    <input type="hidden" name="community_id" value="<?= $community['id'] ?>">
                    <button type="submit" class="action-btn">participar</button>
                </form>
            <?php endif; ?>

            <div class="menu-links">
                <a href="/">🔙 voltar para home</a>
                <a href="/communities">👥 minhas comunidades</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div style="margin-bottom: 20px;">
                <h1 style="margin: 0; font-size: 18px; color: #333;"><?= htmlspecialchars($community['name']) ?></h1>
                <div style="margin-top: 5px; color: #666; line-height: 1.4;">
                    <?= nl2br(htmlspecialchars($community['description'])) ?>
                </div>
            </div>

            <!-- Poll Section -->
            <?php if ($poll): ?>
                <div class="poll-box">
                    <b>Enquete: <?= htmlspecialchars($poll['question']) ?></b>
                    <div style="margin-top: 10px;">
                        <?php if ($userVoted): ?>
                            <!-- Results View -->
                            <?php foreach ($pollOptions as $opt): ?>
                                <?php $pct = $totalVotes > 0 ? round(($opt['votes'] / $totalVotes) * 100) : 0; ?>
                                <div style="margin-bottom: 5px;">
                                    <?= htmlspecialchars($opt['option_text']) ?> (<?= $opt['votes'] ?> votos - <?= $pct ?>%)
                                    <div style="background: #ccc; height: 10px; width: 200px; display: inline-block;">
                                        <div style="background: #B0235F; height: 100%; width: <?= $pct ?>%;"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <small>Total de votos: <?= $totalVotes ?></small>
                        <?php elseif ($isMember): ?>
                            <!-- Voting View -->
                            <form action="/polls/vote" method="POST">
                                <input type="hidden" name="poll_id" value="<?= $poll['id'] ?>">
                                <input type="hidden" name="community_id" value="<?= $community['id'] ?>">
                                <?php foreach ($pollOptions as $opt): ?>
                                    <div style="margin-bottom: 2px;">
                                        <input type="radio" name="option_id" value="<?= $opt['id'] ?>" required>
                                        <?= htmlspecialchars($opt['option_text']) ?>
                                    </div>
                                <?php endforeach; ?>
                                <button type="submit" class="submit-btn" style="margin-top: 5px;">Votar</button>
                            </form>
                        <?php else: ?>
                            <p>Entre na comunidade para votar.</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php elseif ($isMember): ?>
                <div style="margin-top: 10px;">
                    <a href="/polls/create?community_id=<?= $community['id'] ?>" style="font-size: 10px;">[criar
                        enquete]</a>
                </div>
            <?php endif; ?>

            <div class="section-header" style="margin-top: 20px; display: flex; justify-content: space-between;">
                <span>fórum</span>
                <?php if ($isMember): ?>
                    <a href="/topics/create?community_id=<?= $community['id'] ?>" style="color: #3B5998; font-size: 10px;">+
                        criar tópico</a>
                <?php endif; ?>
            </div>

            <div class="content-box">
                <table class="forum-table" width="100%" cellspacing="0" cellpadding="0">
                    <tr style="background: #E0E7F7; font-weight: bold;">
                        <td style="padding: 4px;">tópico</td>
                        <td style="padding: 4px; text-align: right; width: 120px;">autor</td>
                        <td style="padding: 4px; text-align: right; width: 50px;">posts</td>
                    </tr>
                    <?php if (empty($topics)): ?>
                        <tr>
                            <td colspan="3" style="color: #666; padding: 10px;">Nenhum tópico ainda.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($topics as $topic): ?>
                            <tr>
                                <td style="padding: 4px; border-bottom: 1px solid #eee;">
                                    <img src="https://via.placeholder.com/15?text=T" style="vertical-align: middle;">
                                    <a
                                        href="/communities/topic?id=<?= $topic['id'] ?>"><b><?= htmlspecialchars($topic['title']) ?></b></a>
                                </td>
                                <td
                                    style="padding: 4px; border-bottom: 1px solid #eee; font-size: 10px; color: #666; text-align: right;">
                                    <?= htmlspecialchars($topic['author_name']) ?>
                                </td>
                                <td
                                    style="padding: 4px; border-bottom: 1px solid #eee; font-size: 10px; color: #666; text-align: right;">
                                    <?= $topic['post_count'] ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <!-- Right Sidebar (Members) -->
        <div class="sidebar-right">
            <div class="section-header">
                membros (<?= $membersCount ?>)
            </div>
            <div class="friends-grid">
                <?php foreach ($members as $m): ?>
                    <div class="friend-item">
                        <a href="/profile?id=<?= $m['id'] ?>">
                            <img src="<?= $m['avatar'] ?? 'https://via.placeholder.com/50' ?>">
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <div style="text-align: right; font-size: 10px; margin-top: 5px;">
                <a href="#">ver todos</a>
            </div>
        </div>
    </div>
</body>

</html>