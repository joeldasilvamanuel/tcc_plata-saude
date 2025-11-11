<?php $pageTitle = "Dashboard Profissional"; ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css"> 
    <style>
        .form-container { max-width: 1000px; }
        .alert-list { margin-top: 20px; text-align: left; }
        .alert-item {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            border-left: 5px solid;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .ALTO { border-left-color: #dc3545; background-color: #f8d7da; }
        .MÉDIO { border-left-color: #ffc107; background-color: #fff3cd; }
        .INATIVIDADE { border-left-color: #007bff; background-color: #e9f5ff; }
        .alert-details { flex-grow: 1; }
        .alert-details strong { display: block; font-size: 1.1em; }
        .alert-level { font-weight: bold; padding: 5px 10px; border-radius: 4px; color: white; }
        .ALTO .alert-level { background-color: #dc3545; }
        .MÉDIO .alert-level { background-color: #ffc107; color: #333; }
        .INATIVIDADE .alert-level { background-color: #007bff; }
    </style>
</head>
<body>
    <div class="form-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1>Dashboard do Profissional de Saúde</h1>
            <a href="<?= BASE_URL ?>logout" class="btn primary" style="width: auto; margin: 0; background-color: #6c757d;">Sair</a>
        </div>
        
        <h2>Alertas de Monitoramento (<?= date('d/m/Y') ?>)</h2>
        
        <p>Total de Utentes em Risco: <strong><?= $total_alertas ?></strong></p>

        <div class="alert-list">
            <?php if ($total_alertas > 0): ?>
                <?php foreach ($alertas as $alerta): ?>
                    <div class="alert-item <?= htmlspecialchars($alerta['nivel']) ?>">
                        <div class="alert-details">
                            <strong>Utente: <?= htmlspecialchars($alerta['nome_utente']) ?></strong>
                            <span>Data do Registo: <?= htmlspecialchars($alerta['data_registo']) ?></span>
                            <p style="margin-top: 5px; font-style: italic;">Motivo: <?= htmlspecialchars($alerta['motivo']) ?></p>
                        </div>
                        <div class="alert-level">
                            <?= htmlspecialchars($alerta['nivel']) ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert-item" style="border-left-color: #28a745; background-color: #e6ffed;">
                    Nenhum alerta de risco de saúde ou inatividade registado hoje. Tudo sob controlo.
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>