<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>orkut - login</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="top-bar-nav">
            <b>Home</b> | <a href="/register">join orkut</a> | <a href="#">help</a>
        </div>
        <div>
            <span class="orkut-logo-small">orkut</span> <span
                style="font-size: 9px; color: #666; vertical-align: super;">beta</span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-container-login">

        <!-- Left Section: Intro Text & Collage -->
        <div class="intro-text-column">
            <div>
                <span class="intro-logo-large">orkut</span> is an online community that connects people through a
                network of trusted friends.
            </div>
            <p>
                We are committed to providing an online meeting place where people can socialize, make new acquaintances
                and find others who share their interests.
            </p>

            <div class="collage-container">
                <!-- "Who do you know?" collage simulation -->
                <div
                    style="position: relative; display: inline-block; width: 400px; height: 200px; background: #f0f0f0; border: 1px solid #ccc; display: flex; align-items: center; justify-content: center;">
                    <!-- Placeholder images would go here, simplified for now -->
                    <span style="font-family: Georgia, serif; font-style: italic; font-size: 24px; color: #666;">who do
                        YO U know?</span>
                </div>
            </div>

            <p style="text-align: center;">
                <b>join <span style="color: #B0235F;">orkut</span> to expand the circumference of your social
                    circle.</b>
            </p>
        </div>

        <!-- Right Section: Login Box -->
        <div class="login-box-column">
            <div class="login-box">
                <div class="login-box-header">login</div>
                <div class="login-box-body">
                    <form action="/login" method="POST">
                        <div class="form-field">
                            <label class="form-label">username:</label><br>
                            <input type="email" name="email" required>
                        </div>
                        <div class="form-field">
                            <label class="form-label">password:</label><br>
                            <input type="password" name="password" required>
                        </div>

                        <div style="margin: 5px 0;">
                            <span style="font-size: 9px;">[ <a href="#">forgot your password?</a> ]</span>
                        </div>

                        <button type="submit" class="submit-btn">submit</button>
                    </form>
                </div>
            </div>

            <div style="margin-top: 15px; text-align: center;">
                <span style="font-size: 11px;">Not a member?</span><br>
                <a href="/register"><b>join orkut!</b></a>
            </div>
        </div>

    </div>

    <!-- Footer -->
    <div class="footer">
        <div>In affiliation with Google</div>
        <div>
            <a href="#">about orkut</a> | <a href="#">privacy</a> | <a href="#">terms</a>
        </div>
    </div>

</body>

</html>