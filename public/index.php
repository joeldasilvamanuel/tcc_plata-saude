<?php
// tcc_plata-saude/public/index.php - CÓDIGO FINAL DE ROTAS

// 1. Configuração e Inicialização
session_start();

// CÓDIGO TEMPORÁRIO PARA EXIBIR ERROS PHP (Retire quando o erro 500 desaparecer)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// FIM DO CÓDIGO TEMPORÁRIO


// 2. Inclusão de Arquivos Essenciais

// CORE e CONFIGURAÇÃO (DEVE VIR PRIMEIRO)
require_once '../app/core/Config.php';

// DATABASE (Onde está no seu projeto: app/models/)
require_once '../app/models/Database.php';

// MODELOS
require_once '../app/models/UtenteModel.php';
require_once '../app/models/ProfissionalModel.php';
require_once '../app/models/AuthModel.php';
require_once '../app/models/AdminModel.php';

// CONTROLADORES (DEVEM SER INCLUÍDOS ANTES DE SEREM USADOS NAS ROTAS)
require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/UtenteController.php';
require_once '../app/controllers/ProfissionalController.php';
require_once '../app/controllers/AdminController.php';


// 3. Roteamento Principal
$url = isset($_GET['url']) ? explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL)) : ['login'];

// Rotas da Autenticação
if ($url[0] === 'login') {
    $authController = new AuthController(); // <--- A CLASSE DEVE ESTAR DEFINIDA AQUI
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $authController->processLogin();
    } else {
        $authController->showLogin();
    }
    exit;
} elseif ($url[0] === 'register') {
    $authController = new AuthController();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $authController->processRegister();
    } else {
        $authController->showRegister();
    }
    exit;
} elseif ($url[0] === 'logout') {
    $authController = new AuthController();
    $authController->logout();
    exit;
}


// ROTAS DO ADMINISTRADOR (FASE 4)
if ($url[0] === 'admin') {
    $adminController = new AdminController();
    if (isset($url[1])) {
        switch ($url[1]) {
            case 'dashboard':
                $adminController->showDashboard();
                exit;
            case 'toggleUser':
                $adminController->toggleUser();
                exit;
            case 'resetPassword':
                $adminController->resetPassword();
                exit;
        }
    }
    $adminController->showDashboard();
    exit;
}


// Rotas do Utente
if ($url[0] === 'utente') {
    $utenteController = new UtenteController();
    if (isset($url[1])) {
        switch ($url[1]) {
            case 'dashboard':
                $utenteController->showDashboard();
                exit;
            case 'submitRegistoDiario':
                $utenteController->submitRegistoDiario();
                exit;
        }
    }
    $utenteController->showDashboard();
    exit;
}

// Rotas do Profissional
if ($url[0] === 'profissional') {
    $profissionalController = new ProfissionalController();
    if (isset($url[1])) {
        switch ($url[1]) {
            case 'dashboard':
                $profissionalController->showDashboard();
                exit;
            case 'resolveAlerta':
                $profissionalController->resolveAlerta();
                exit;
        }
    }
    $profissionalController->showDashboard();
    exit;
}


// Rota Padrão (Fallback)
header('Location: ' . BASE_URL . 'login');
exit;
