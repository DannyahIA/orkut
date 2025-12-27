<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Orkut - Editar Perfil</title>
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

        h1 {
            font-size: 16px;
            color: #B0235F;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input,
        select {
            margin-bottom: 10px;
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
        <b>orkut</b> | <a href="/" style="color: white;">Voltar para Home</a>
    </div>
    <div class="container">
        <h1>Editar Perfil</h1>
        <form action="/profile/update" method="POST" enctype="multipart/form-data">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label>Foto de Perfil:</label>
                    <?php if (!empty($user['avatar'])): ?>
                        <img src="<?= $user['avatar'] ?>" style="max-width: 100px; display: block; margin-bottom: 5px;">
                    <?php endif; ?>
                    <input type="file" name="avatar">

                    <label>País:</label>
                    <input type="text" name="country" value="<?= htmlspecialchars($user['country'] ?? '') ?>" style="width: 100%;">

                    <label>Cidade:</label>
                    <input type="text" name="city" value="<?= htmlspecialchars($user['city'] ?? '') ?>" style="width: 100%;">
                    
                    <label>Estado civil:</label>
                    <select name="relationship" style="width: 100%;">
                        <option value="">Selecione...</option>
                        <option value="solteiro" <?= ($user['relationship_status'] ?? '') == 'solteiro' ? 'selected' : '' ?>>Solteiro(a)</option>
                        <option value="casado" <?= ($user['relationship_status'] ?? '') == 'casado' ? 'selected' : '' ?>>Casado(a)</option>
                        <option value="namorando" <?= ($user['relationship_status'] ?? '') == 'namorando' ? 'selected' : '' ?>>Namorando</option>
                        <option value="enrolado" <?= ($user['relationship_status'] ?? '') == 'enrolado' ? 'selected' : '' ?>>Enrolado(a)</option>
                    </select>
                </div>
                <div>
                    <label>Quem sou eu:</label>
                    <textarea name="bio" rows="5" style="width: 100%;"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>

                    <label>Interesses:</label>
                    <textarea name="interests" rows="3" style="width: 100%;"><?= htmlspecialchars($user['interests'] ?? '') ?></textarea>
                </div>
            </div>
            
            <button type="submit" style="margin-top: 10px;">Salvar Alterações</button>
        </form>
    </div>
</body>

</html>