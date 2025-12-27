<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>orkut - <?= htmlspecialchars($topic['title']) ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>

    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar-left">
            <div class="community-img-box">
                <img src="https://via.placeholder.com/150?text=C" alt="Community">
            </div>
            <div class="menu-links">
                <a href="/">🔙 voltar para home</a>
                <a href="/communities">👥 minhas comunidades</a>
                <a href="/communities/show?id=<?= $topic['community_id'] ?>">⬅ voltar para comunidade</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="breadcrumbs">
                <a href="/">Início</a> &gt;
                <a href="/communities">Comunidades</a> &gt;
                <a
                    href="/communities/show?id=<?= $topic['community_id'] ?>"><?= htmlspecialchars($topic['community_name']) ?></a>
                &gt;
                <b>Tópicos</b>
            </div>

            <div class="section-header">
                <?= htmlspecialchars($topic['title']) ?>
            </div>

            <div class="content-box" style="padding: 0; border: none;">
                <table class="posts-table">
                    <?php foreach ($posts as $pos => $post): ?>
                        <tr>
                            <td class="author-col">
                                <a href="/profile?id=<?= $post['author_id'] ?>"
                                    class="author-name"><?= htmlspecialchars($post['author_name']) ?></a>
                                <img src="<?= $post['author_avatar'] ?? 'https://via.placeholder.com/75' ?>"
                                    style="width: 75px; height: 75px; object-fit: cover; border: 1px solid white;">
                            </td>
                            <td class="content-col">
                                <div class="post-date">
                                    <span style="float: right;">Postado:
                                        <?= date('d/m/Y H:i', strtotime($post['created_at'])) ?></span>
                                    &nbsp;
                                </div>
                                <div>
                                    <?= nl2br(htmlspecialchars($post['content'])) ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <!-- Reply Form -->
            <div class="reply-box">
                <b>Responder</b>
                <form action="/topics/reply" method="POST" style="margin-top: 5px;">
                    <input type="hidden" name="topic_id" value="<?= $topic['id'] ?>">
                    <textarea name="content" required></textarea><br>
                    <button type="submit" class="submit-btn" style="margin-top: 5px;">enviar resposta</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>