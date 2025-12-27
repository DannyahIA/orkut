<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>orkut - minhas comunidades</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>

    <div class="container">
        <!-- Left Column (Reuse Sidebar for consistency or simplified menu) -->
        <div class="sidebar-left">
            <!-- Simplified Menu or Profile Pic -->
            <div class="menu-links" style="margin-top: 0;">
                <a href="/">🔙 voltar para home</a>
                <a href="/profile">👤 meu perfil</a>
                <a href="/communities">👥 comunidades</a>
            </div>
        </div>

        <!-- Center Column -->
        <div class="main-content">
            <div class="welcome-box">
                <h1 style="margin: 0; font-size: 18px; color: #333;">minhas comunidades</h1>
            </div>

            <div class="section-header" style="display: flex; justify-content: space-between; align-items: center;">
                <span>comunidades que participo</span>
                <a href="/communities/create" style="font-size: 10px; font-weight: bold; color: #3B5998;">[+ criar
                    comunidade]</a>
            </div>

            <div class="content-box">
                <?php if (empty($communities)): ?>
                    <p style="padding: 10px; font-size: 11px;">Você não participa de nenhuma comunidade.</p>
                <?php else: ?>
                    <table width="100%" cellspacing="0">
                        <?php foreach ($communities as $c): ?>
                            <tr>
                                <td class="community-list-item">
                                    <div style="display: flex; align-items: center;">
                                        <img src="https://via.placeholder.com/30?text=C"
                                            style="border: 1px solid #ccc; margin-right: 10px;">
                                        <div>
                                            <a href="/communities/show?id=<?= $c['id'] ?>"
                                                style="font-size: 12px;"><?= htmlspecialchars($c['name']) ?></a><br>
                                            <span style="color: #666; font-size: 10px;">(<?= $c['members_count'] ?>
                                                membros)</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Column (Optional) -->
        <div class="sidebar-right">
            <div class="section-header">
                busca
            </div>
            <div style="background: #E0E7F7; padding: 5px;">
                <input type="text" placeholder="encontrar comunidade"
                    style="width: 95%; font-size: 10px; border: 1px solid #999;">
            </div>
        </div>
    </div>
</body>

</html>