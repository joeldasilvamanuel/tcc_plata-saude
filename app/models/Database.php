<?php

class Database
{
    private static $instance = null;
    private $connection;

    // Construtor privado para evitar instâncias externas (Padrão Singleton)
    private function __construct()
    {
        // Usa a API MySQLi para conectar
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($this->connection->connect_error) {
            // Se a conexão falhar, interrompe e exibe o erro
            die('Erro de Conexão (' . $this->connection->connect_errno . ') '
                . $this->connection->connect_error);
        }
        // Define o charset para garantir que caracteres especiais funcionem corretamente
        $this->connection->set_charset('utf8');
    }

    // Retorna a única instância da classe de conexão
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // Método para obter o objeto de conexão MySQLi
    public function getConnection()
    {
        return $this->connection;
    }
}
