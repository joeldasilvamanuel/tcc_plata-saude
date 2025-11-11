<?php
// tcc_plata-saude/app/models/UtenteModel.php

class UtenteModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtém os detalhes completos do utente logado (para o Dashboard).
     * @param int $userId ID do utilizador (da tabela usuario)
     * @return array|null Detalhes do utente ou null se não for encontrado
     */
    public function getUserDetails($userId)
    {
        // Busca dados do utente (nome, email, data de nascimento, etc.)
        $stmt = $this->db->prepare("
            SELECT 
                u.email_usuario, 
                t.nome_completo_utente, 
                t.data_nascimento_utente,
                t.genero_utente
            FROM usuario u
            JOIN utente t ON u.id_usuario = t.id_usuario_utente
            WHERE u.id_usuario = ?
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $userDetails = $result->fetch_assoc();
        $stmt->close();
        
        return $userDetails;
    }


    /**
     * Obtém o ID do utente associado ao ID do utilizador (para segurança).
     * @param int $id_usuario
     * @return int|null ID do utente
     */
    public function getUtenteIdByUserId($id_usuario)
    {
        $stmt = $this->db->prepare("SELECT id_utente FROM utente WHERE id_usuario_utente = ?");
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $data['id_utente'] ?? null;
    }

    /**
     * Regista dados de Sintomas e Hábitos numa única transação (garantindo atomicidade).
     * @param array $data Contém todos os campos necessários.
     * @return bool Sucesso ou falha.
     */
    public function registerDailyData($data)
    {
        $id_utente = $data['id_utente'];
        $data_registro = date('Y-m-d');

        $this->db->begin_transaction();

        try {
            // 1. REGISTO DE SINTOMAS (Tabela registro_sintoma) - Usando REPLACE para atualizar se já existir
            $sql_sintoma = "REPLACE INTO registro_sintoma (id_utente_registro, data_registro_sintoma, febre_sintoma, temperatura_sintoma, local_dor_sintoma, intensidade_dor_sintoma, tosse_sintoma, fadiga_sintoma, pressao_arterial_sintoma, glicemia_sintoma, observacoes_sintoma) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt_sintoma = $this->db->prepare($sql_sintoma);
            $stmt_sintoma->bind_param(
                "isidsisidss",
                $id_utente,
                $data_registro,
                $data['febre_sintoma'],
                $data['temperatura_sintoma'],
                $data['local_dor_sintoma'],
                $data['intensidade_dor_sintoma'],
                $data['tosse_sintoma'],
                $data['fadiga_sintoma'],
                $data['pressao_arterial_sintoma'],
                $data['glicemia_sintoma'],
                $data['observacoes_sintoma']
            );

            if (!$stmt_sintoma->execute()) {
                throw new Exception("Erro ao registar sintomas: " . $stmt_sintoma->error);
            }
            $stmt_sintoma->close();


            // 2. REGISTO DE HÁBITOS (Tabela registro_habito) - Usando REPLACE para atualizar se já existir
            $sql_habito = "REPLACE INTO registro_habito (id_utente_habito, data_registro_habito, horas_sono_habito, qualidade_sono_habito, refeicoes_dia_habito, copos_agua_habito, minutos_exercicio_habito, alcool_habito, tabaco_habito, notas_habito) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt_habito = $this->db->prepare($sql_habito);
            $stmt_habito->bind_param(
                "isiiiiisss",
                $id_utente,
                $data_registro,
                $data['horas_sono_habito'],
                $data['qualidade_sono_habito'],
                $data['refeicoes_dia_habito'],
                $data['copos_agua_habito'],
                $data['minutos_exercicio_habito'],
                $data['alcool_habito'],
                $data['tabaco_habito'],
                $data['notas_habito']
            );

            if (!$stmt_habito->execute()) {
                throw new Exception("Erro ao registar hábitos: " . $stmt_habito->error);
            }
            $stmt_habito->close();

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollback();
            // Apenas para debug: error_log($e->getMessage());
            return false;
        }
    }
}