<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>orkut - busca</title>
    <link rel="stylesheet" href="/css/style.css">
    <script>
        function showTab(tabName) {
            // Hide all contents
            document.getElementById('tab-users').style.display = 'none';
            document.getElementById('tab-communities').style.display = 'none';

            // Remove active class
            document.getElementById('btn-users').classList.remove('active');
            document.getElementById('btn-communities').classList.remove('active');

            // Show selected
            document.getElementById('tab-' + tabName).style.display = 'block';
            document.getElementById('btn-' + tabName).classList.add('active');
        }
    </script>
</head>

<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>

    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar-left">
            <div class="menu-links" style="margin-top: 0;">
                <a href="/">🔙 voltar para home</a>
                <a href="/profile">👤 meu perfil</a>
                <a href="/communities">👥 comunidades</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="welcome-box">
                <h1 style="margin: 0; font-size: 18px; color: #333;">resultados da pesquisa:
                    "<?= htmlspecialchars($query) ?>"</h1>
            </div>

            <div class="tab-container">
                <div id="btn-users" class="tab-item active" onclick="showTab('users')">perfis (<?= count($users) ?>)
                </div>
                <div id="btn-communities" class="tab-item" onclick="showTab('communities')">comunidades
                    (<?= count($communities) ?>)</div>
            </div>

            <div class="content-box">
                <!-- Users Tab -->
                <div id="tab-users">
                    <?php if (empty($users)): ?>
                        <p style="padding: 10px; font-size: 11px;">Nenhum usuário encontrado.</p>
                    <?php else: ?>
                        <div class="search-result-grid">
                            <?php foreach ($users as $u): ?>
                                <div class="friend-card-large">
                                    <a href="/profile?id=<?= $u['id'] ?>">
                                        <img src="<?= $u['avatar'] ?? 'https://via.placeholder.com/90' ?>" width="90"
                                            height="90">
                                    </a>
                                    <br>
                                    <a href="/profile?id=<?= $u['id'] ?>"><b><?= htmlspecialchars($u['name']) ?></b></a>
                                    <br>
                                    <span
                                        style="color: #666; font-size: 10px;"><?= htmlspecialchars($u['city'] ?? 'Brasil') ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Communities Tab -->
                <div id="tab-communities" style="display: none;">
                    <?php if (empty($communities)): ?>
                        <p style="padding: 10px; font-size: 11px;">Nenhuma comunidade encontrada.</p>
                    <?php else: ?>
                        <table width="100%" cellspacing="0">
                            <?php foreach ($communities as $c): ?>
                                <tr>
                                    <td class="community-list-item">
                                        <div style="display: flex; align-items: center;">
                                            <img src="https://via.placeholder.com/40?text=C"
                                                style="border: 1px solid #ccc; margin-right: 10px; width: 40px; height: 40px;">
                                            <div>
                                                <a href="/communities/show?id=<?= $c['id'] ?>"
                                                    style="font-size: 12px;"><?= htmlspecialchars($c['name']) ?></a>
                                                <br>
                                                <span
                                                    style="color: #666; font-size: 10px;"><?= substr(htmlspecialchars($c['description'] ?? ''), 0, 80) ?>...</span>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>