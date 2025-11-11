<?php
// tcc_plata-saude/app/models/ProfissionalModel.php

class ProfissionalModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Lógica Central do TCC: Avalia o risco de um utente com base no seu último registo.
     * Esta é a lógica de negócio principal do sistema de Alerta.
     * @param array $registro Dados brutos do último registo (sintomas e hábitos).
     * @return array Contém 'nivel' (Baixo, Médio, Alto) e 'motivo'.
     */
    private function avaliarRisco($registro) {
        $nivel = 'Baixo';
        $motivos = [];

        // --- 1. Regras para Risco ALTO (Exigem atenção imediata) ---
        if ($registro['febre_sintoma'] == 1 && $registro['temperatura_sintoma'] >= 38.0) {
            $nivel = 'ALTO';
            $motivos[] = "Febre alta ({$registro['temperatura_sintoma']}°C)";
        }
        if ($registro['intensidade_dor_sintoma'] >= 8) {
            $nivel = 'ALTO';
            $motivos[] = "Dor muito intensa (Nível {$registro['intensidade_dor_sintoma']}/10)";
        }

        // --- 2. Regras para Risco MÉDIO (Exigem monitoramento) ---
        if ($nivel !== 'ALTO') {
            if ($registro['fadiga_sintoma'] >= 4) {
                $nivel = 'MÉDIO';
                $motivos[] = "Fadiga/Cansaço Intenso (Nível {$registro['fadiga_sintoma']}/5)";
            }
            if ($registro['qualidade_sono_habito'] <= 2 && $registro['horas_sono_habito'] < 5) {
                $nivel = 'MÉDIO';
                $motivos[] = "Qualidade e Duração do Sono muito baixas";
            }
            if ($registro['alcool_habito'] == 1 || $registro['tabaco_habito'] == 1) {
                $nivel = 'MÉDIO';
                $motivos[] = "Consumo de substâncias (Álcool/Tabaco) hoje";
            }
        }
        
        // --- 3. Default e Agregação ---
        if (count($motivos) > 0 && $nivel === 'Baixo') {
             // Se houver motivos, mas nenhum crítico, sobe para MÉDIO
            $nivel = 'MÉDIO';
        }

        return [
            'nivel' => $nivel,
            'motivo' => count($motivos) > 0 ? implode("; ", $motivos) : 'Sem riscos significativos.'
        ];
    }

    /**
     * Obtém o último registo diário de todos os utentes e avalia o risco.
     * @return array Lista de alertas (apenas Médio e Alto).
     */
    public function getAlerts() {
        // SQL para obter o ID, Nome e o último REGISTO COMPLETO (Sintomas + Hábitos) de cada utente.
        // Utiliza LEFT JOINs para garantir que utentes sem registo não quebrem a query.
        $sql = "
            SELECT 
                u.id_utente, 
                u.nome_completo_utente, 
                rs.*, 
                rh.*
            FROM utente u
            LEFT JOIN registro_sintoma rs 
                ON rs.id_utente_registro = u.id_utente
                AND rs.data_registro_sintoma = (SELECT MAX(data_registro_sintoma) FROM registro_sintoma WHERE id_utente_registro = u.id_utente)
            LEFT JOIN registro_habito rh
                ON rh.id_utente_habito = u.id_utente
                AND rh.data_registro_habito = rs.data_registro_sintoma
            ORDER BY u.nome_completo_utente ASC
        ";

        $result = $this->db->query($sql);
        $alertas = [];

        while ($registro = $result->fetch_assoc()) {
            if ($registro['id_registro_sintoma'] === null) {
                // Utente nunca registou dados
                $alertas[] = [
                    'id_utente' => $registro['id_utente'],
                    'nome_utente' => $registro['nome_completo_utente'],
                    'data_registo' => 'N/A',
                    'nivel' => 'NENHUM REGISTO',
                    'motivo' => 'Utente não submeteu dados diários.',
                    'tipo' => 'INATIVIDADE'
                ];
                continue;
            }

            // Avaliar o risco usando o método privado
            $avaliacao = $this->avaliarRisco($registro);

            // Adiciona apenas alertas de risco MÉDIO e ALTO
            if (in_array($avaliacao['nivel'], ['MÉDIO', 'ALTO'])) {
                $alertas[] = [
                    'id_utente' => $registro['id_utente'],
                    'nome_utente' => $registro['nome_completo_utente'],
                    'data_registo' => $registro['data_registro_sintoma'],
                    'nivel' => $avaliacao['nivel'],
                    'motivo' => $avaliacao['motivo'],
                    'tipo' => 'RISCO_SAUDE'
                ];
            }
        }
        return $alertas;
    }
}