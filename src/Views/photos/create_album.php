<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>orkut - criar novo álbum</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>

    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar-left">
            <div class="menu-links">
                <a href="/photos">🔙 voltar para álbuns</a>
                <a href="/profile">👤 meu perfil</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="welcome-box">
                <h1 style="margin: 0; font-size: 18px; color: #333;">criar novo álbum</h1>
            </div>

            <div class="content-box">
                <form action="/photos/store_album" method="POST" enctype="multipart/form-data">
                    <div style="margin-bottom: 10px;">
                        <label><b>Título do Álbum:</b></label><br>
                        <input type="text" name="title" style="width: 300px; padding: 3px; border: 1px solid #ccc;"
                            required>
                    </div>

                    <div style="margin-bottom: 10px;">
                        <label><b>Descrição:</b></label><br>
                        <textarea name="description"
                            style="width: 300px; height: 60px; padding: 3px; border: 1px solid #ccc; font-family: Verdana; font-size: 11px;"></textarea>
                    </div>

                    <div style="margin-bottom: 10px;">
                        <label><b>Capa do Álbum:</b></label><br>
                        <small>Escolha um arquivo:</small><br>
                        <input type="file" name="cover_file" style="margin-bottom: 5px;"><br>
                        <small>OU cole a URL:</small><br>
                        <input type="text" name="cover_photo_url" placeholder="https://..."
                            style="width: 300px; padding: 3px; border: 1px solid #ccc;">
                    </div>

                    <button type="submit" class="submit-btn" style="padding: 5px 10px;">Criar Álbum</button>
                    <a href="/photos" style="margin-left: 10px; font-size: 11px;">cancelar</a>
                </form>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="sidebar-right">
        </div>
    </div>
</body>

</html>