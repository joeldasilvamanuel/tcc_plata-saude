<?php
// tcc_plata-saude/app/controllers/ProfissionalController.php

class ProfissionalController {
    private $profissionalModel;

    public function __construct() {
        // Middleware de Autenticação: Garante que apenas profissionais logados acedam
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'profissional') {
            // Redireciona para o login se não for profissional
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        $this->profissionalModel = new ProfissionalModel();
    }

    public function showDashboard() {
        $profissionalId = $_SESSION['user_id'];
        
        // Lógica para buscar alertas para este profissional
        // $alertas = $this->profissionalModel->getPendingAlerts($profissionalId); 
        $alertas = []; // Placeholder

        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['success']);
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);
        
        require_once '../views/profissional/dashboard.php';
    }

    /**
     * Processa a resolução de um alerta (FASE 3).
     */
    public function resolveAlerta() {
        // Garante que o método é POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'profissional/dashboard');
            exit;
        }

        $alertaId = $_POST['alerta_id'] ?? null;
        $profissionalId = $_SESSION['user_id'];
        
        if (!$alertaId) {
            $_SESSION['error'] = "ID do alerta em falta.";
            header('Location: ' . BASE_URL . 'profissional/dashboard');
            exit;
        }
        
        // --- Lógica de Resolução Placeholder ---
        
        // 1. Chamar o Model para atualizar o estado
        // $sucesso = $this->profissionalModel->markAlertAsResolved($alertaId, $profissionalId);
        $sucesso = true; // Placeholder
        
        // --- Fim da Lógica Placeholder ---
        
        if ($sucesso) {
            $_SESSION['success'] = "Alerta #{$alertaId} marcado como resolvido com sucesso.";
        } else {
            $_SESSION['error'] = "Falha ao resolver o Alerta #{$alertaId}.";
        }

        header('Location: ' . BASE_URL . 'profissional/dashboard');
        exit;
    }
}