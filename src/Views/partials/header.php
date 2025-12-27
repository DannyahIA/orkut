<?php
// Ensure session is started for email display
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$headerUserEmail = $_SESSION['user']['email'] ?? '';
?>
<div class="header-authenticated">
    <div class="header-inner-left">
        <a href="/" class="header-logo">orkut</a>
        <div class="header-nav">
            <a href="/">Início</a>
            <a href="/profile">Perfil</a>
            <a href="/profile?id=<?= $_SESSION['user']['id'] ?? 0 ?>">Página de recados</a>
            <a href="/friends">Amigos</a>
            <a href="/communities">Comunidades</a>
        </div>
    </div>
    <div class="header-user-info">
        <div>
            <b><?= htmlspecialchars($headerUserEmail) ?></b> | <a href="/logout"
                style="color: white; text-decoration: underline;">Sair</a>
        </div>
        <form action="/search" method="GET" style="margin: 0; display: flex; gap: 2px;">
            <input type="text" name="q" placeholder="pesquisar no orkut" class="search-input">
            <button type="submit" class="search-btn">go</button>
        </form>
    </div>
</div>