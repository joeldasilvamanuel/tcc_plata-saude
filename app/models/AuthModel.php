<?php
// tcc_plata-saude/app/models/AuthModel.php - CÓDIGO FINAL E SEGURO (HASH ATIVO)

class AuthModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Tenta fazer o login de um utilizador (com verificação de hash).
     * @param string $email
     * @param string $senha
     * @return array|bool Dados do utilizador ou false em caso de falha.
     */
    public function login($email, $senha) {
        // 1. Busca o utilizador pelo email
        $stmt = $this->db->prepare("SELECT id_usuario, senha_hash_usuario, tipo_usuario FROM usuario WHERE email_usuario = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $stmt->close();
            return false;
        }

        $usuario = $result->fetch_assoc();
        $stmt->close();

        // =========================================================
        // VERIFICAÇÃO DE HASH ATIVA (SEGURANÇA PADRÃO)
        // =========================================================
        if (password_verify($senha, $usuario['senha_hash_usuario'])) { 
            
            // Login bem-sucedido
            return [
                'id' => $usuario['id_usuario'],
                'email' => $email,
                'type' => $usuario['tipo_usuario']
            ];
        } else {
            // Senha incorreta
            return false;
        }
    }
    
    /**
     * Regista um novo Utente no sistema.
     */
    public function registerUtente($email, $senha_hash, $nome, $data_nascimento, $genero) {
        $this->db->begin_transaction();
        
        try {
            // 1. Inserir na tabela USUARIO (tipo 'utente')
            $stmt1 = $this->db->prepare("INSERT INTO usuario (email_usuario, senha_hash_usuario, tipo_usuario) VALUES (?, ?, 'utente')");
            $stmt1->bind_param("ss", $email, $senha_hash); 
            
            if (!$stmt1->execute()) {
                throw new Exception("Erro ao criar utilizador: " . $stmt1->error);
            }
            $id_usuario = $this->db->insert_id;
            $stmt1->close();

            // 2. Inserir na tabela UTENTE (Usando o ID inserido)
            $stmt2 = $this->db->prepare("INSERT INTO utente (id_usuario_utente, nome_completo_utente, data_nascimento_utente, genero_utente) VALUES (?, ?, ?, ?)");
            $stmt2->bind_param("isss", $id_usuario, $nome, $data_nascimento, $genero);
            
            if (!$stmt2->execute()) {
                throw new Exception("Erro ao criar perfil de utente: " . $stmt2->error);
            }
            $stmt2->close();

            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollback();
            return false;
        }
    }

    /**
     * Verifica se um email já está registado.
     */
    public function emailExists($email) {
        $stmt = $this->db->prepare("SELECT id_usuario FROM usuario WHERE email_usuario = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $exists = $result->num_rows > 0;
        $stmt->close();
        return $exists;
    }
}