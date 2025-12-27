<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Orkut - Criar Enquete</title>
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

        input[type="text"] {
            width: 100%;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            padding: 3px;
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
        <h3>Criar Enquete em <?= htmlspecialchars($community['name']) ?></h3>
        <form action="/polls/store" method="POST">
            <input type="hidden" name="community_id" value="<?= $community['id'] ?>">

            <label><b>Pergunta:</b></label>
            <input type="text" name="question" required placeholder="Ex: Qual o melhor jogo?">

            <br><br>
            <label><b>Opções:</b></label><br>
            <input type="text" name="options[]" placeholder="Opção 1" required>
            <input type="text" name="options[]" placeholder="Opção 2" required>
            <input type="text" name="options[]" placeholder="Opção 3">
            <input type="text" name="options[]" placeholder="Opção 4">
            <input type="text" name="options[]" placeholder="Opção 5">

            <button type="submit">Criar Enquete</button>
        </form>
    </div>
</body>

</html>