<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>orkut - adicionar foto</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>

    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar-left">
            <div class="menu-links">
                <a href="/photos/album?id=<?= $album_id ?>">🔙 voltar para álbum</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="welcome-box">
                <h1 style="margin: 0; font-size: 18px; color: #333;">adicionar foto</h1>
            </div>

            <div class="content-box">
                <form action="/photos/store_photo" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="album_id" value="<?= $album_id ?>">

                    <div style="margin-bottom: 10px;">
                        <label><b>Foto:</b></label><br>
                        <small>Escolha um arquivo:</small><br>
                        <input type="file" name="image_file" style="margin-bottom: 5px;"><br>

                        <small>OU cole a URL:</small><br>
                        <input type="text" name="image_url" placeholder="https://..."
                            style="width: 300px; padding: 3px; border: 1px solid #ccc;">
                    </div>

                    <div style="margin-bottom: 10px;">
                        <label><b>Legenda:</b></label><br>
                        <input type="text" name="caption" style="width: 300px; padding: 3px; border: 1px solid #ccc;">
                    </div>

                    <button type="submit" class="submit-btn" style="padding: 5px 10px;">Adicionar Foto</button>
                    <a href="/photos/album?id=<?= $album_id ?>" style="margin-left: 10px; font-size: 11px;">cancelar</a>
                </form>
            </div>
        </div>

        <!-- Right Sidebar -->
        <div class="sidebar-right">
        </div>
    </div>
</body>

</html>