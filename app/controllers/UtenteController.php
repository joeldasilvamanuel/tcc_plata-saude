<?php
// tcc_plata-saude/app/controllers/UtenteController.php

class UtenteController
{
    private $utenteModel;
    private $profissionalModel; // Pode ser necessário para a lógica de Alertas

    public function __construct()
    {
        // Middleware de Autenticação: Garante que apenas utentes logados acedam
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'utente') {
            // Redireciona para o login se não for utente
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        $this->utenteModel = new UtenteModel();
        // Inicialize aqui o ProfissionalModel/AlertModel se necessário
        // $this->profissionalModel = new ProfissionalModel();
    }

    public function showDashboard()
    {
        // Lógica para buscar dados do utente aqui (ex: nome, alertas pendentes)
        $userId = $_SESSION['user_id'];
        $dados_utente = $this->utenteModel->getUserDetails($userId); // Assumindo este método existe

        $success = $_SESSION['success'] ?? null;
        unset($_SESSION['success']);
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);

        require_once '../views/utente/dashboard.php';
    }

    /**
     * Processa o envio do formulário de Registo Diário (FASE 3).
     */
    public function submitRegistoDiario()
    {
        // Garante que o método é POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'utente/dashboard');
            exit;
        }

        $userId = $_SESSION['user_id'];

        // --- Lógica de Processamento Placeholder ---

        // 1. Coletar dados do $_POST (ex: $temperatura, $sintomas, etc.)
        // $temperatura = $_POST['temperatura'] ?? 0;

        // 2. Chamar o Model para salvar
        // $this->utenteModel->saveDailyRecord($userId, $dados);

        // 3. Verificar Alertas
        // $alerta_disparado = $this->profissionalModel->checkForAlerts($userId, $dados);

        // --- Fim da Lógica Placeholder ---

        $_SESSION['success'] = "Registo Diário submetido com sucesso! O profissional será notificado se necessário.";
        header('Location: ' . BASE_URL . 'utente/dashboard');
        exit;
    }
}
