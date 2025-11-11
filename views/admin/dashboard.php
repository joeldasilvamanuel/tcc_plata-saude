<?php $pageTitle = "Dashboard Admin"; ?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <style>
        .form-container {
            max-width: 1200px;
        }

        .user-list {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .user-list th,
        .user-list td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        .user-list th {
            background-color: #f2f2f2;
            color: #333;
        }

        .status-active {
            color: #28a745;
            font-weight: bold;
        }

        .status-inactive {
            color: #dc3545;
            font-weight: bold;
        }

        .action-form {
            display: inline;
            margin-right: 5px;
        }

        .action-button {
            padding: 5px 10px;
            font-size: 0.8em;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
            border: none;
        }

        .btn-success {
            background-color: #28a745;
            color: white;
            border: none;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #333;
            border: none;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h1>Painel de Administração</h1>
            <a href="<?= BASE_URL ?>logout" class="btn primary" style="width: auto; margin: 0; background-color: #6c757d;">Sair</a>
        </div>

        <h2>Gestão de Utilizadores</h2>

        <?php if ($success): ?>
            <div class="message success-message"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="message error-message"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <table class="user-list">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome Completo</th>
                    <th>Email</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($utilizadores as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id_usuario']) ?></td>
                        <td><?= htmlspecialchars($user['nome_completo']) ?></td>
                        <td><?= htmlspecialchars($user['email_usuario']) ?></td>
                        <td><?= ucfirst(htmlspecialchars($user['tipo_usuario'])) ?></td>
                        <td>
                            <?php if ($user['is_active_usuario'] == 1): ?>
                                <span class="status-active">Ativo</span>
                            <?php else: ?>
                                <span class="status-inactive">Inativo</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($user['id_usuario'] != $_SESSION['user_id']): // Não permite alterar o próprio admin 
                            ?>
                                <form action="<?= BASE_URL ?>admin/resetPassword" method="POST" class="action-form">
                                    <input type="hidden" name="user_id" value="<?= $user['id_usuario'] ?>">
                                    <button type="submit" class="action-button btn-warning" onclick="return confirm('Tem certeza que deseja redefinir a senha para 123456?')" title="Redefine a senha para '123456'">Redefinir</button>
                                </form>

                                <?php if ($user['is_active_usuario'] == 1): ?>
                                    <form action="<?= BASE_URL ?>admin/toggleUser" method="POST" class="action-form">
                                        <input type="hidden" name="user_id" value="<?= $user['id_usuario'] ?>">
                                        <input type="hidden" name="status" value="0">
                                        <button type="submit" class="action-button btn-danger" onclick="return confirm('Tem certeza que deseja desativar este utilizador?')">Desativar</button>
                                    </form>
                                <?php else: ?>
                                    <form action="<?= BASE_URL ?>admin/toggleUser" method="POST" class="action-form">
                                        <input type="hidden" name="user_id" value="<?= $user['id_usuario'] ?>">
                                        <input type="hidden" name="status" value="1">
                                        <button type="submit" class="action-button btn-success">Ativar</button>
                                    </form>
                                <?php endif; ?>
                            <?php else: ?>
                                (Conta Atual)
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>