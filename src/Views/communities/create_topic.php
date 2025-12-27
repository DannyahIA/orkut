<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Orkut - Criar Tópico</title>
    <style>
        body {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            background-color: #E5E5E5;
            margin: 0;
            padding: 0;
            font-size: 11px;
        }

        .header {
            background: #B0235F;
            color: white;
            padding: 5px 10px;
        }

        .container {
            width: 900px;
            margin: 10px auto;
            background: white;
            padding: 10px;
            border: 1px solid #ccc;
        }

        input,
        textarea {
            width: 100%;
            margin-bottom: 10px;
            border: 1px solid #ccc;
        }

        button {
            background: #688AD4;
            color: white;
            border: 1px solid #3E60A8;
            padding: 5px 10px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="header">
        <b>orkut</b> | <a href="/communities/show?id=<?= $community['id'] ?>" style="color: white;">Voltar para
            Comunidade</a>
    </div>
    <div class="container">
        <h3>Criar Tópico em <?= htmlspecialchars($community['name']) ?></h3>
        <form action="/topics/store" method="POST">
            <input type="hidden" name="community_id" value="<?= $community['id'] ?>">

            <label>Título:</label>
            <input type="text" name="title" required>

            <label>Mensagem:</label>
            <textarea name="content" rows="10" required></textarea>

            <button type="submit">Criar Tópico</button>
        </form>
    </div>
</body>

</html>