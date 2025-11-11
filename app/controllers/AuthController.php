<?php
// tcc_plata-saude/app/controllers/AuthController.php - COMPLETO E CORRIGIDO

class AuthController {
    private $authModel;

    public function __construct() {
        $this->authModel = new AuthModel();
    }

    public function showLogin() {
        // Redireciona se já estiver logado
        if (isset($_SESSION['user_type'])) {
            $this->redirectToDashboard($_SESSION['user_type']);
            exit;
        }
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);
        require_once '../views/auth/login.php';
    }

    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['password'] ?? '';

        // O método login() no AuthModel agora lida com a busca e a validação (ou ignorância no modo de teste)
        $loginResult = $this->authModel->login($email, $senha); 

        if ($loginResult) {
            // Login bem-sucedido
            $_SESSION['user_id'] = $loginResult['id'];
            $_SESSION['user_email'] = $loginResult['email'];
            $_SESSION['user_type'] = $loginResult['type'];

            $this->redirectToDashboard($loginResult['type']);
            exit;
        } else {
            // Login falhou (Credenciais Inválidas ou Conta Inativa)
            $_SESSION['error'] = "Credenciais Inválidas ou Conta Inativa.";
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }

    public function showRegister() {
        if (isset($_SESSION['user_type'])) {
            $this->redirectToDashboard($_SESSION['user_type']);
            exit;
        }
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);
        require_once '../views/auth/register.php';
    }

    public function processRegister() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'register');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['password'] ?? '';
        $nome = trim($_POST['nome'] ?? '');
        $data_nascimento = trim($_POST['data_nascimento'] ?? '');
        $genero = trim($_POST['genero'] ?? '');

        if (empty($email) || empty($senha) || empty($nome)) {
            $_SESSION['error'] = "Todos os campos obrigatórios devem ser preenchidos.";
            header('Location: ' . BASE_URL . 'register');
            exit;
        }

        if ($this->authModel->emailExists($email)) {
            $_SESSION['error'] = "Este e-mail já está registado. Tente fazer login.";
            header('Location: ' . BASE_URL . 'register');
            exit;
        }

        $senha_hash = password_hash($senha, PASSWORD_BCRYPT);

        if ($this->authModel->registerUtente($email, $senha_hash, $nome, $data_nascimento, $genero)) {
            // Sucesso no registo, faz login automático
            $loginResult = $this->authModel->login($email, $senha); // O login funciona porque a conta foi acabada de criar

            if ($loginResult) {
                 $_SESSION['user_id'] = $loginResult['id'];
                 $_SESSION['user_email'] = $loginResult['email'];
                 $_SESSION['user_type'] = $loginResult['type'];
                 $this->redirectToDashboard($loginResult['type']);
                 exit;
            } else {
                 $_SESSION['error'] = "Registo bem-sucedido, mas falha no login automático. Tente fazer login manualmente.";
                 header('Location: ' . BASE_URL . 'login');
                 exit;
            }
        } else {
            $_SESSION['error'] = "Ocorreu um erro interno ao criar a conta. Tente novamente.";
            header('Location: ' . BASE_URL . 'register');
            exit;
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . 'login');
        exit;
    }

    /**
     * Método auxiliar para redirecionamento.
     */
    private function redirectToDashboard($user_type) {
        switch ($user_type) {
            case 'utente':
                header('Location: ' . BASE_URL . 'utente/dashboard');
                break;
            case 'profissional':
                header('Location: ' . BASE_URL . 'profissional/dashboard');
                break;
            case 'admin':
                header('Location: ' . BASE_URL . 'admin/dashboard');
                break;
            default:
                header('Location: ' . BASE_URL . 'logout');
                break;
        }
    }
}