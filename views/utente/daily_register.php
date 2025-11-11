<?php $pageTitle = "Registo Diário"; ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> - <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <style>
        /* Estilos específicos para este formulário complexo */
        .form-section { border: 1px solid #007bff; padding: 20px; border-radius: 8px; margin-bottom: 20px; background-color: #e9f5ff; }
        .form-section h2 { color: #007bff; border-bottom: 1px solid #007bff; padding-bottom: 10px; margin-top: 0; }
        .checkbox-group { display: flex; align-items: center; margin-bottom: 15px; }
        .checkbox-group input { margin-right: 10px; width: auto; }
        .form-group input[type="range"] { margin-top: 10px; }
        output { display: inline-block; margin-left: 10px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="form-container" style="max-width: 800px;">
        <h1>Registo Diário de Saúde</h1>
        <p>Por favor, preencha os dados de hoje (<?= date('d/m/Y') ?>).</p>

        <?php if (!empty($error)): ?>
            <p class="message error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>utente/registo-diario/process" method="POST">
            
            <div class="form-section">
                <h2>1. Sintomas e Condições Atuais</h2>

                <div class="checkbox-group">
                    <input type="checkbox" id="febre_sintoma" name="febre_sintoma" value="1">
                    <label for="febre_sintoma">Sente **Febre**?</label>
                </div>
                
                <div class="form-group">
                    <label for="temperatura_sintoma">Temperatura Corporal (°C) <small>(Opcional)</small></label>
                    <input type="number" step="0.01" min="30" max="45" id="temperatura_sintoma" name="temperatura_sintoma" placeholder="Ex: 36.5">
                </div>
                
                <div class="form-group">
                    <label for="local_dor_sintoma">Local da Dor Principal <small>(Opcional)</small></label>
                    <input type="text" id="local_dor_sintoma" name="local_dor_sintoma" placeholder="Ex: Cabeça, Abdominal, Articulações">
                </div>
                
                <div class="form-group">
                    <label for="intensidade_dor_sintoma">Intensidade da Dor (Escala 0-10)</label>
                    <input type="range" id="intensidade_dor_sintoma" name="intensidade_dor_sintoma" min="0" max="10" value="0" oninput="this.nextElementSibling.value=this.value">
                    <output>0</output>
                </div>

                <div class="form-group">
                    <label for="tosse_sintoma">Tipo de Tosse</label>
                    <select id="tosse_sintoma" name="tosse_sintoma">
                        <option value="Nenhuma">Nenhuma</option>
                        <option value="Seca">Seca</option>
                        <option value="Com catarro">Com catarro</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="fadiga_sintoma">Nível de Fadiga/Cansaço (Escala 1-5)</label>
                    <select id="fadiga_sintoma" name="fadiga_sintoma">
                        <option value="1">1 (Nenhum)</option>
                        <option value="2">2 (Leve)</option>
                        <option value="3">3 (Moderado)</option>
                        <option value="4">4 (Intenso)</option>
                        <option value="5">5 (Exaustivo)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="pressao_arterial_sintoma">Pressão Arterial (sistólica/diastólica) <small>(Opcional)</small></label>
                    <input type="text" id="pressao_arterial_sintoma" name="pressao_arterial_sintoma" placeholder="Ex: 120/80">
                </div>
                
                <div class="form-group">
                    <label for="glicemia_sintoma">Nível de Glicemia (mg/dL) <small>(Opcional)</small></label>
                    <input type="number" step="0.1" id="glicemia_sintoma" name="glicemia_sintoma" placeholder="Ex: 95.5">
                </div>

                <div class="form-group">
                    <label for="observacoes_sintoma">Outras Observações de Sintomas</label>
                    <textarea id="observacoes_sintoma" name="observacoes_sintoma" rows="3"></textarea>
                </div>
            </div>

            <div class="form-section">
                <h2>2. Hábitos de Vida</h2>

                <div class="form-group">
                    <label for="horas_sono_habito">Horas de Sono na Noite Anterior</label>
                    <input type="number" id="horas_sono_habito" name="horas_sono_habito" min="0" max="15" value="0">
                </div>

                <div class="form-group">
                    <label for="qualidade_sono_habito">Qualidade do Sono (Escala 1-5, 5=Ótimo)</label>
                    <input type="range" id="qualidade_sono_habito" name="qualidade_sono_habito" min="1" max="5" value="3" oninput="this.nextElementSibling.value=this.value">
                    <output>3</output>
                </div>

                <div class="form-group">
                    <label for="refeicoes_dia_habito">Número de Refeições no Dia</label>
                    <input type="number" id="refeicoes_dia_habito" name="refeicoes_dia_habito" min="0" value="0">
                </div>

                <div class="form-group">
                    <label for="copos_agua_habito">Copos de Água (200ml) Consumidos</label>
                    <input type="number" id="copos_agua_habito" name="copos_agua_habito" min="0" value="0">
                </div>

                <div class="form-group">
                    <label for="minutos_exercicio_habito">Minutos de Exercício Físico (Hoje)</label>
                    <input type="number" id="minutos_exercicio_habito" name="minutos_exercicio_habito" min="0" value="0">
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="alcool_habito" name="alcool_habito" value="1">
                    <label for="alcool_habito">Consumiu **Álcool** hoje?</label>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="tabaco_habito" name="tabaco_habito" value="1">
                    <label for="tabaco_habito">Consumiu **Tabaco/Fumo** hoje?</label>
                </div>
                
                <div class="form-group">
                    <label for="notas_habito">Notas sobre Hábitos</label>
                    <textarea id="notas_habito" name="notas_habito" rows="3"></textarea>
                </div>
            </div>

            <button type="submit" class="btn primary">Guardar Registo Diário</button>
            <a href="<?= BASE_URL ?>utente/dashboard" class="btn primary" style="background-color: #6c757d; margin-top: 10px;">Voltar ao Dashboard</a>
        </form>
    </div>
</body>
</html>