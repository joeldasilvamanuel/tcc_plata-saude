<?php
// tcc_plata-saude/app/models/AdminModel.php

class AdminModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtém todos os utilizadores (utente, profissional, admin) com detalhes.
     */
    public function getAllUsers() {
        // Query para obter dados básicos de todos os utilizadores
        $sql = "
            SELECT 
                u.id_usuario,
                u.email_usuario,
                u.tipo_usuario,
                u.is_active_usuario,
                CASE u.tipo_usuario
                    WHEN 'utente' THEN t.nome_completo_utente
                    WHEN 'profissional' THEN p.nome_completo_profissional
                    ELSE 'Administrador'
                END AS nome_completo
            FROM usuario u
            LEFT JOIN utente t ON u.id_usuario = t.id_usuario_utente AND u.tipo_usuario = 'utente'
            LEFT JOIN profissional p ON u.id_usuario = p.id_usuario_profissional AND u.tipo_usuario = 'profissional'
            ORDER BY u.tipo_usuario DESC, nome_completo ASC
        ";
        
        $result = $this->db->query($sql);
        $utilizadores = [];

        while ($row = $result->fetch_assoc()) {
            $utilizadores[] = $row;
        }
        return $utilizadores;
    }
    
    /**
     * Ativa/Desativa uma conta de utilizador.
     */
    public function toggleUserStatus($userId, $status) {
        $stmt = $this->db->prepare("UPDATE usuario SET is_active_usuario = ? WHERE id_usuario = ?");
        $stmt->bind_param("ii", $status, $userId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    /**
     * Gera um novo hash para redefinição de senha (ex: para '123456').
     */
    public function resetPassword($userId) {
        // Gera um hash para uma senha padrão segura (ex: '123456')
        $new_hash = password_hash('123456', PASSWORD_BCRYPT);
        
        $stmt = $this->db->prepare("UPDATE usuario SET senha_hash_usuario = ? WHERE id_usuario = ?");
        $stmt->bind_param("si", $new_hash, $userId);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    // O código para gerar códigos de convite será omitido por ser complexo demais para a fase final.
}