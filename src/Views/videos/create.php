<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>orkut - adicionar vídeo</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>

    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar-left">
            <div class="menu-links">
                <a href="/videos">🔙 voltar para vídeos</a>
                <a href="/profile">👤 meu perfil</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="welcome-box">
                <h1 style="margin: 0; font-size: 18px; color: #333;">adicionar vídeo</h1>
            </div>

            <div class="content-box">
                <form action="/videos/store" method="POST">
                    <div style="margin-bottom: 15px;">
                        <label><b>URL do YouTube:</b></label><br>
                        <small style="color: #666;">Ex: https://www.youtube.com/watch?v=dQw4w9WgXcQ</small><br>
                        <input type="text" name="youtube_url" placeholder="Cole o link aqui..."
                            style="width: 400px; padding: 3px; border: 1px solid #ccc; margin-top: 3px;" required>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label><b>Título (opcional):</b></label><br>
                        <input type="text" name="title" style="width: 400px; padding: 3px; border: 1px solid #ccc;">
                    </div>

                    <button type="submit" class="submit-btn" style="padding: 5px 10px;">Adicionar Vídeo</button>
                    <a href="/videos" style="margin-left: 10px; font-size: 11px;">cancelar</a>
                </form>
            </div>
        </div>

        <div class="sidebar-right">
        </div>
    </div>
</body>

</html>