# 📄 LOG DE ALTERAÇÕES: MÓDULO DE UPGRADE E TRADUÇÃO (SKY WAVE FIBRA)

Este documento resume todas as modificações no código-fonte, nos Models e nas Views (Frontend) realizadas para implementar a funcionalidade dinâmica de Upgrade de Planos e a tradução completa do sistema para o Português (pt-BR).

## 1. ⚙️ BACKEND & MODELAGEM (PHP)

| Arquivo/Método | Alteração | Finalidade |
| :--- | :--- | :--- |
| **`source/Models/App/Contract.php`** | **Criação do Model.** | Mapeamento da tabela `contract` para vincular o cliente ao plano ativo. Corrigido `__construct()` para passar 3 argumentos, resolvendo o erro `ArgumentCountError`. |
| **`source/App/App.php` (Método `home`)** | **Injeção de Dados Dinâmicos.** | Implementação da lógica de busca para obter o `$current_plan` (plano ativo) e o `$suggested_plan` (próximo plano mais caro) diretamente do banco de dados. Uso de FQN para resolver erros de namespace. |
| **`source/App/App.php` (Novo Método)** | **Criação do método `contact()`** | Lógica para carregar a View de "Solicitar Orçamento Personalizado", atendendo à nova rota. |

## 2. 🗺️ ROTEAMENTO (INDEX/ROUTES)

| Arquivo de Rotas | Rota Criada | Controller/Método | Finalidade |
| :--- | :--- | :--- | :--- |
| **`index.php` (ou `routes.php`)** | `/app/contato` | `App:contact` | Mapeamento da URL de contato, eliminando o erro de página não encontrada (`404`) ao clicar no botão do plano customizado. |

## 3. 🖥️ FRONTEND & VIEWS (TRADUÇÃO E FLUXO)

| Arquivo/Componente | Alteração Realizada | Detalhes |
| :--- | :--- | :--- |
| **`themes/app/home.php`** | **Banner de Upgrade Dinâmico.** | O widget roxo passou a exibir o nome e a velocidade do **Plano Atual** e do **Plano Sugerido** (`$current_plan` e `$suggested_plan`) do banco, substituindo valores estáticos. |
| **`themes/app/_theme.php`** | **Tradução do Modal `kt_modal_upgrade_plan`** | Todos os textos estáticos do modal (ex: "Monthly", "Annual", títulos, botões) foram traduzidos para o português. |
| **`themes/app/_theme.php`** | **Conteúdo do Plano Personalizado** | O conteúdo foi substituído por recursos de ISP ("Link Dedicado", "IP Fixo", etc.), fornecendo uma simulação de plano corporativo (o "migué"). |
| **`themes/app/_theme.php`** | **Correção do Botão "Contate-nos"** | O `href` foi corrigido para `<?= url("app/contato") ?>`, e a tag foi alterada de `<a>` para garantir o redirecionamento correto para a nova rota. |
| **`themes/app/contact/main.php`** | **Criação da View de Orçamento** | Implementação de uma página simples de "Solicitar Orçamento Personalizado" com links de WhatsApp e E-mail, simulando um contato de vendas. |
| **`assets/.../upgrade-plan.js`** | **Tradução do Alerta JS** | As strings do alerta `swal.fire` (alerta de confirmação de upgrade) foram traduzidas (ex: "Are you sure..." para **"Tem certeza que deseja mudar..."**). |