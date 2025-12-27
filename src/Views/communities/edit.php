<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Orkut - Editar Comunidade</title>
    <style>
        body {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            background-color: #E5E5E5;
            margin: 0;
            padding: 0;
            font-size: 11px;
        }

        .container {
            width: 900px;
            margin: 10px auto;
            background: white;
            padding: 10px;
            border: 1px solid #ccc;
        }

        input[type="text"],
        textarea {
            width: 400px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            padding: 3px;
        }

        .header {
            background: #B0235F;
            color: white;
            padding: 5px 10px;
        }
    </style>
</head>

<body>
    <?php require __DIR__ . '/../partials/header.php'; ?>

    <div class="container">
        <h3>Editar Comunidade: <?= htmlspecialchars($community['name']) ?></h3>

        <form action="/communities/update" method="POST">
            <input type="hidden" name="id" value="<?= $community['id'] ?>">

            <label><b>Nome:</b></label><br>
            <input type="text" name="name" value="<?= htmlspecialchars($community['name']) ?>" required><br>

            <label><b>Descrição:</b></label><br>
            <textarea name="description" rows="5"
                required><?= htmlspecialchars($community['description']) ?></textarea><br>

            <label><b>Imagem (URL):</b></label><br>
            <input type="text" name="image" value="<?= htmlspecialchars($community['image'] ?? '') ?>"><br>

            <div style="margin-top: 10px;">
                <button type="submit">Salvar Alterações</button>
                ou <a href="/communities/show?id=<?= $community['id'] ?>">cancelar</a>
            </div>
        </form>

        <div style="margin-top: 30px; border-top: 1px solid #ccc; padding-top: 10px;">
            <b style="color: red;">Zona de Perigo</b><br><br>
            <form action="/communities/delete" method="POST"
                onsubmit="return confirm('Tem certeza que deseja EXCLUIR esta comunidade? Tópicos e mensagens serão apagados para sempre.');">
                <input type="hidden" name="id" value="<?= $community['id'] ?>">
                <button type="submit" style="color: red;">Excluir Comunidade</button>
            </form>
        </div>
    </div>
</body>

</html>