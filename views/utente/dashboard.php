<?php $pageTitle = "Dashboard Utente"; ?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>

<body>
    <div class="form-container" style="max-width: 800px; padding: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1>Dashboard do Utente</h1>
            <a href="<?= BASE_URL ?>logout" class="btn primary" style="width: auto; margin: 0; background-color: #dc3545;">Sair</a>
        </div>

        <p style="text-align: left; color: #333;">Bem-vindo, <?= htmlspecialchars($_SESSION['user_email']) ?>. Registe os seus dados diários para um melhor acompanhamento.</p>

        <?php if (!empty($success)): ?>
            <p class="message success-message"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <p class="message error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <div style="margin: 20px 0; padding: 15px; border: 1px solid #28a745; border-radius: 6px; background-color: #e6ffed;">
            <h2>Registo de Saúde Diário</h2>
            <p>Informe sobre os seus sintomas e hábitos para hoje (<?= date('d/m/Y') ?>).</p>

            <a href="<?= BASE_URL ?>utente/registo-diario" class="btn primary" style="background-color: #28a745; margin-top: 10px;">
                Registar Dados de Hoje
            </a>
        </div>

        <h3>Histórico de Monitoramento</h3>
        <p>Nenhum gráfico disponível ainda. Preencha o seu primeiro formulário diário.</p>
    </div>
</body>

</html>