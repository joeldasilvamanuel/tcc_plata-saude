<?php $pageTitle = "Login"; ?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/style.css">

    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>

<body>
    <div class="form-container">
        <h1>Entrar</h1>
        <p>Acesse a sua conta para monitoramento de saúde.</p>

        <?php if (!empty($error)): ?>
            <p class="message error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <?php if (!empty($success_message)): ?>
            <p class="message success-message"><?= htmlspecialchars($success_message) ?></p>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>login/process" method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Palavra-passe</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn primary">Entrar</button>
        </form>

        <p class="mt-2">É um novo utente? <a href="<?= BASE_URL ?>register">Crie uma conta</a>.</p>
        <p class="mt-1 small">Profissionais acedem pelo mesmo e-mail (registo por convite).</p>
    </div>
</body>

</html>