<?php $pageTitle = "Registo de Utente"; ?>
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
        <h1>Registar Utente</h1>
        [cite_start]<p>Registo público e aberto para utentes do Capolo II[cite: 137, 164].</p>

        <?php if (isset($error)): ?>
            <p class="message error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>register/process" method="POST">
            <div class="form-group">
                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="data_nascimento">Data de Nascimento</label>
                <input type="date" id="data_nascimento" name="data_nascimento" required>
            </div>

            <div class="form-group">
                <label for="genero">Género</label>
                <select id="genero" name="genero" required>
                    <option value="" disabled selected>Selecione</option>
                    <option value="M">Masculino (M)</option>
                    <option value="F">Feminino (F)</option>
                    <option value="Outro">Outro</option>
                </select>
            </div>

            <div class="form-group">
                <label for="password">Palavra-passe</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn primary">Criar Conta</button>
        </form>

        <p class="mt-2">Já tem conta? <a href="<?= BASE_URL ?>login">Fazer Login</a>.</p>
    </div>
</body>

</html>