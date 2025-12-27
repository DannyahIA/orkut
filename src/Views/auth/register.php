<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Orkut - Register</title>
    <style>
        body {
            font-family: Verdana, Arial, Helvetica, sans-serif;
            background-color: #E5E5E5;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }

        .logo {
            color: #B0235F;
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .login-box {
            background: #D4DDED;
            padding: 20px;
            border: 1px solid #9FB2D6;
            width: 400px;
        }

        input,
        select {
            width: 100%;
            margin-bottom: 10px;
            padding: 5px;
            box-sizing: border-box;
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
    <div class="container">
        <div class="logo">orkut</div>
        <div class="login-box">
            <h3>Criar nova conta</h3>
            <form action="/register" method="POST">
                <label>Nome:</label>
                <input type="text" name="name" required>

                <label>Email:</label>
                <input type="email" name="email" required>

                <label>Senha:</label>
                <input type="password" name="password" required>

                <label>Data de Nascimento:</label>
                <input type="date" name="birthdate" required>

                <label>Sexo:</label>
                <select name="gender">
                    <option value="M">Masculino</option>
                    <option value="F">Feminino</option>
                    <option value="Other">Outro</option>
                </select>

                <button type="submit">Criar conta</button>
            </form>
            <p><a href="/login">Voltar para Login</a></p>
        </div>
    </div>
</body>

</html>