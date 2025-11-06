# Plataforma Saúde Digital - Capolo II
## tcc_plata_saude

## Sobre o Projeto
Sistema web de monitoramento de saúde desenvolvido como TCC para a comunidade do Capolo II em Luanda. Permite acompanhamento contínuo de pacientes através de registros manuais com sistema de alertas automáticos e comunicação direta entre utentes e profissionais.

## Tecnologias
- Frontend: HTML5, CSS3, JavaScript
- Backend: PHP 8.0+
- Banco de Dados: MySQL 5.7+
- Arquitetura: MVC
- Servidor: Apache/XAMPP


## Instalação
1. Coloque `plataforma-saude` no `htdocs` do XAMPP
2. Crie BD `tcc_plata_saude` e execute `database/schema.sql`
3. Acesse `http://localhost/plataforma-saude/public/`
4. Login: admin@capolo.com / password

## Funcionalidades por Perfil

### Utente (Paciente)
- Registro diário de sintomas, hábitos e medicação
- Acompanhamento da evolução da saúde
- Chat com profissionais
- Sistema de alertas personalizados

### Profissional de Saúde
- Dashboard com pacientes vinculados
- Visualização de alertas prioritários
- Comunicação via chat
- Relatórios clínicos

### Administrador
- Gestão de usuários do sistema
- Configurações da plataforma

## Banco de Dados - 10 Tabelas

### Tabelas Principais
1. **usuario** - Login de todos (email, senha, tipo)
2. **utente** - Dados dos pacientes (nome, telefone, morada, contacto emergência)
3. **profissional** - Dados dos médicos (especialidade, registro, instituição)

### Tabelas de Registro
4. **registro_sintoma** - Sintomas diários (febre, dor, tosse, pressão, glicemia)
5. **registro_habito** - Hábitos de vida (sono, água, exercício, álcool, tabaco)
6. **registro_medicacao** - Medicação (nome, dosagem, horário, tomado)

### Tabelas de Relacionamento
7. **vinculo** - Liga pacientes a profissionais (ativo, tipo, datas)
8. **mensagem** - Sistema de chat (remetente, destinatário, texto, lida)
9. **alerta** - Alertas automáticos (tipo, título, descrição, resolvido)
10. **condicao_medica** - Condições médicas (alergias, doenças crônicas)

## Fluxo de Desenvolvimento

### Semana 1-2: Base do Sistema
- Configurar ambiente e estrutura MVC
- Implementar banco de dados (10 tabelas)
- Desenvolver sistema de autenticação
- Criar templates base

### Semana 3-4: Módulo Utente
- Dashboard do utente
- Formulários de registro diário
- Sistema de perfil pessoal
- Histórico de registros

### Semana 5-6: Módulo Profissional
- Dashboard do profissional
- Lista de pacientes vinculados
- Sistema de alertas
- Chat interno

### Semana 7-8: Polimento e Testes
- Testes de usabilidade
- Correção de bugs
- Documentação final
- Preparação da defesa

## Divisão de Tarefas Sugerida

### Programador Principal
- Arquitetura do sistema
- Banco de dados
- Lógica de negócio (controllers)
- Integração geral

### Co-Programador
- Interface do usuário (views)
- CSS e design responsivo
- JavaScript frontend
- Testes de usabilidade

## Métodos de Acesso

### Registro de Utente (Público)
- Formulário simples: Nome, Email, Senha
- Tipo automático: 'utente'

### Registro de Profissional (Controlado)
- Acesso por link especial
- Requer código de convite do administrador
- Preenche dados profissionais: registro, especialidade, instituição

### Vinculação Paciente-Profissional
- Profissional envia pedido via email do paciente
- Paciente aceita ou recusa no dashboard
- Após aceite: profissional vê dados, chat ativo, alertas funcionam

## Sistema de Alertas Automáticos

### Condições de Exemplo
- Febre > 38.5° por 2 dias consecutivos
- Pressão arterial fora dos limites
- Glicemia consistentemente alta/baixa
- Falta de registro por múltiplos dias

### Fluxo do Alerta
1. Sistema detecta padrão nos registros
2. Cria alerta na tabela `alerta`
3. Notifica profissional vinculado
4. Profissional marca como resolvido

## Desenvolvimento Local

```bash
# Estrutura de pastas
mkdir -p plataforma-saude/{app/{controllers,models,views/{auth,utente,profissional,admin,layouts},core},public/assets/{css,js,images},config,database}

# Arquivos essenciais
touch public/index.php public/.htaccess
touch app/core/Database.php app/controllers/AuthController.php
touch config/database.php database/schema.sql
