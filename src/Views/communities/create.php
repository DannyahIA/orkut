<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>orkut - criar comunidade</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>

    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar-left">
            <div class="menu-links" style="margin-top: 0;">
                <a href="/">🔙 voltar para home</a>
                <a href="/communities">👥 minhas comunidades</a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="section-header">
                criar comunidade
            </div>

            <div class="content-box">
                <form action="/communities/store" method="POST">
                    <div style="margin-bottom: 10px;">
                        <label style="font-weight: bold; font-size: 11px;">Nome:</label><br>
                        <input type="text" name="name" required style="width: 300px; margin-top: 2px;">
                    </div>

                    <div style="margin-bottom: 10px;">
                        <label style="font-weight: bold; font-size: 11px;">Descrição:</label><br>
                        <textarea name="description" rows="5"
                            style="width: 100%; font-family: Verdana; font-size: 11px;"></textarea>
                    </div>

                    <div style="margin-bottom: 10px;">
                        <label style="font-weight: bold; font-size: 11px;">Categoria:</label><br>
                        <select name="category" style="font-size: 11px;">
                            <option value="">selecione...</option>
                            <option value="other">Outros</option>
                        </select>
                    </div>

                    <div style="margin-top: 5px;">
                        <button type="submit" class="submit-btn">criar comunidade</button>
                        <a href="/communities" style="color: #666; margin-left: 10px;">cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>