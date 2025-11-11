<?php
// tcc_plata-saude/app/controllers/AdminController.php

class AdminController {
    private $adminModel;

    public function __construct() {
        // Middleware de Autenticação: Garante que apenas admins logados acedam
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        $this->adminModel = new AdminModel();
    }

    public function showDashboard() {
        $utilizadores = $this->adminModel->getAllUsers();
        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['success']);
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);
        
        require_once '../views/admin/dashboard.php';
    }

    /**
     * Ação para Ativar/Desativar um utilizador (via POST).
     */
    public function toggleUser() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/dashboard');
            exit;
        }

        $userId = $_POST['user_id'] ?? null;
        $status = $_POST['status'] ?? null; // 1 para Ativar, 0 para Desativar
        
        if ($userId && ($status === '1' || $status === '0')) {
            // Evitar que o admin desative a própria conta
            if ($userId == $_SESSION['user_id']) {
                 $_SESSION['error'] = "Não pode alterar o estado da sua própria conta.";
            } elseif ($this->adminModel->toggleUserStatus($userId, (int)$status)) {
                 $_SESSION['success'] = "Estado do utilizador atualizado com sucesso.";
            } else {
                 $_SESSION['error'] = "Falha ao atualizar o estado do utilizador.";
            }
        } else {
            $_SESSION['error'] = "Dados inválidos para a operação.";
        }

        header('Location: ' . BASE_URL . 'admin/dashboard');
        exit;
    }

    /**
     * Ação para Redefinir a senha (via POST).
     */
    public function resetPassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/dashboard');
            exit;
        }

        $userId = $_POST['user_id'] ?? null;
        
        if ($userId) {
            // Evitar que o admin redefina a própria senha, forçando-o a usar o logout
            if ($userId == $_SESSION['user_id']) {
                 $_SESSION['error'] = "Use o logout para redefinir a sua senha.";
            } elseif ($this->adminModel->resetPassword($userId)) {
                 $_SESSION['success'] = "Senha redefinida com sucesso para '123456'.";
            } else {
                 $_SESSION['error'] = "Falha ao redefinir a senha.";
            }
        } else {
            $_SESSION['error'] = "ID de utilizador inválido.";
        }

        header('Location: ' . BASE_URL . 'admin/dashboard');
        exit;
    }
}