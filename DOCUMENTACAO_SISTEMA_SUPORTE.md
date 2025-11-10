# 📋 Documentação - Sistema de Suporte (Tickets)

> Documentação completa do sistema de gerenciamento de chamados de suporte implementado no projeto SkyWave Fibra.

---

## 📑 Índice

1. [Visão Geral](#visão-geral)
2. [Arquitetura](#arquitetura)
3. [Funcionalidades](#funcionalidades)
4. [Backend - Código](#backend---código)
5. [Frontend - Views](#frontend---views)
6. [Frontend - JavaScript](#frontend---javascript)
7. [Fluxo de Dados](#fluxo-de-dados)
8. [Recursos de Segurança](#recursos-de-segurança)
9. [Exemplos de Uso](#exemplos-de-uso)

---

## 🎯 Visão Geral

O sistema de suporte foi desenvolvido para gerenciar tickets/chamados de atendimento ao cliente, com suporte para:

- ✅ **Separação de Views**: Admin vs Cliente
- ✅ **Comentários**: Públicos e Internos
- ✅ **Anexos**: Upload e download de arquivos
- ✅ **Histórico**: Rastreamento de mudanças
- ✅ **Diferenciação Visual**: Cores para funcionário (azul) vs cliente (verde)
- ✅ **Validações**: Funcionário obrigatório para alterar status

---

## 🏗️ Arquitetura

### Estrutura de Diretórios

```
skywavefibra/
├── source/App/App.php                    # Controlador principal
├── themes/app/tickets/
│   ├── form.php                          # Visualização/edição admin
│   ├── main.php                          # Lista de chamados em aberto
│   ├── history.php                       # Histórico de chamados resolvidos
│   ├── view-ticket.php                   # Visualização do cliente
│   └── create-ticket.php                 # Criação de novo chamado
├── source/Models/Support/
│   ├── SupportTicket.php                 # Modelo de ticket
│   ├── TicketComment.php                 # Modelo de comentário
│   └── TicketAttachment.php              # Modelo de anexo
└── source/Models/App/
    ├── Employee.php                      # Modelo de funcionário
    └── Customer.php                      # Modelo de cliente
```

### Tabelas do Banco de Dados

```sql
-- Tabela Principal
support_ticket (
  id, customer_id, employee_id, title, category, priority, 
  description, status, opened_at, created_at, closed_at
)

-- Comentários (com suporte a contexto)
ticket_comment (
  id, ticket_id, user_id, comment, is_internal, 
  created_at, updated_at, context  -- 'admin' ou 'client'
)

-- Anexos (com suporte a contexto)
ticket_attachment (
  id, ticket_id, user_id, filename, original_name, 
  file_path, file_size, mime_type, uploaded_at, context
)

-- Histórico de Mudanças
ticket_history (
  id, ticket_id, action_type, user_id, old_value, 
  new_value, description, created_at
)
```

---

## 🚀 Funcionalidades

### 1. Listagem de Chamados Em Aberto

**Rota**: `/app/chamados`

- Mostra apenas chamados com status `open` ou `in-progress`
- Filtros por: Status, Categoria, Prioridade
- Busca por: ID, descrição, nome do cliente
- Paginação configurável (10, 50, 100 por página)
- Modal para alteração de status com validação de funcionário

### 2. Histórico de Chamados

**Rota**: `/app/chamados/historico`

- Mostra apenas chamados com status `resolved` ou `canceled`
- Coluna "Finalizado em" com data de resolução
- Opção para reabrir chamado resolvido
- Mesmas funcionalidades de filtro e busca

### 3. Visualização/Edição Admin

**Rota**: `/app/chamado/{id}`

- Editor completo com abas:
  - **Detalhes**: Informações gerais do chamado
  - **Comentários**: Com diferenciação visual (azul/verde)
  - **Anexos**: Upload/download com diferenciação visual
  - **Histórico**: Rastreamento de mudanças

### 4. Visualização Cliente

**Rota**: `/app/meu-chamado/{id}`

- Visualização limitada do próprio chamado
- Comentários públicos apenas (sem internos)
- Upload de anexos
- Interface simplificada

### 5. Dashboard

**Rota**: `/app/chamados/dashboard`

- Estatísticas gerais
- Gráficos de distribuição por categoria e prioridade
- Tempo médio de atendimento
- Alertas de chamados críticos

---

## 💻 Backend - Código

### Método Principal: `tickets()` - Listagem Em Aberto

```php
public function tickets(?array $data): void
{
    $session = new \Source\Core\Session();

    // POST: salva busca e redireciona
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $search = trim($data["search"] ?? "");

        if ($search !== "") {
            $session->set("ticket_search", $search);
        } else {
            $session->unset("ticket_search");
        }

        echo json_encode(["redirect" => url("/app/chamados")]);
        return;
    }

    // Limpa busca
    if (!empty($_GET["clear"])) {
        $session->unset("ticket_search");
    }

    // Busca persistente
    $search = $session->has("ticket_search") ? $session->ticket_search : "";

    // Filtros
    $filterStatus = $data["status"] ?? null;
    $filterCategory = $data["category"] ?? null;
    $filterPriority = $data["priority"] ?? null;

    // Paginação
    $page  = (int)($data["page"] ?? 1);
    $limit = (int)($data["limit"] ?? 10);

    // Query - APENAS CHAMADOS EM ABERTO
    $ticketModel = new SupportTicket();
    $conditions = ["status NOT IN ('resolved', 'canceled')"];
    $params = [];

    if (!empty($search)) {
        $conditions[] = "(id = :search OR description LIKE CONCAT('%', :search2, '%'))";
        $params["search"] = $search;
        $params["search2"] = $search;
    }

    if ($filterStatus) {
        $conditions[] = "status = :status";
        $params["status"] = $filterStatus;
    }

    if ($filterCategory) {
        $conditions[] = "category = :category";
        $params["category"] = $filterCategory;
    }

    if ($filterPriority) {
        $conditions[] = "priority = :priority";
        $params["priority"] = $filterPriority;
    }

    $where = implode(" AND ", $conditions);
    $query = $ticketModel->find($where, http_build_query($params));

    $total = $query->count();
    $tickets = $query->order("opened_at DESC")
                     ->limit($limit)
                     ->offset(($page - 1) * $limit)
                     ->fetch(true);
    $pages = ceil($total / $limit);

    $this->renderPage("tickets/main", [
        "title"          => "Chamados Em Aberto",
        "tickets"        => $tickets,
        "search"         => $search,
        "filterStatus"   => $filterStatus,
        "filterCategory" => $filterCategory,
        "filterPriority" => $filterPriority,
        "page"           => $page,
        "pages"          => $pages,
        "limit"          => $limit,
        "total"          => $total,
        "activeMenu"     => "support"
    ]);
}
```

### Método: `ticketsHistory()` - Listagem Histórico

```php
public function ticketsHistory(?array $data): void
{
    $session = new \Source\Core\Session();

    // POST: salva busca
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $search = trim($data["search"] ?? "");

        if ($search !== "") {
            $session->set("ticket_history_search", $search);
        } else {
            $session->unset("ticket_history_search");
        }

        echo json_encode(["redirect" => url("/app/chamados/historico")]);
        return;
    }

    // Limpa busca
    if (!empty($_GET["clear"])) {
        $session->unset("ticket_history_search");
    }

    $search = $session->has("ticket_history_search") 
              ? $session->ticket_history_search : "";

    $filterStatus = $data["status"] ?? null;
    $filterCategory = $data["category"] ?? null;
    $filterPriority = $data["priority"] ?? null;

    $page  = (int)($data["page"] ?? 1);
    $limit = (int)($data["limit"] ?? 10);

    // Query - APENAS CHAMADOS RESOLVIDOS/CANCELADOS
    $ticketModel = new SupportTicket();
    $conditions = ["status IN ('resolved', 'canceled')"];
    $params = [];

    if (!empty($search)) {
        $conditions[] = "(id = :search OR description LIKE CONCAT('%', :search2, '%'))";
        $params["search"] = $search;
        $params["search2"] = $search;
    }

    if ($filterStatus) {
        $conditions[] = "status = :status";
        $params["status"] = $filterStatus;
    }

    if ($filterCategory) {
        $conditions[] = "category = :category";
        $params["category"] = $filterCategory;
    }

    if ($filterPriority) {
        $conditions[] = "priority = :priority";
        $params["priority"] = $filterPriority;
    }

    $where = implode(" AND ", $conditions);
    $query = $ticketModel->find($where, http_build_query($params));

    $total = $query->count();
    $tickets = $query->order("closed_at DESC, opened_at DESC")
                     ->limit($limit)
                     ->offset(($page - 1) * $limit)
                     ->fetch(true);
    $pages = ceil($total / $limit);

    $this->renderPage("tickets/history", [
        "title"          => "Histórico de Chamados",
        "tickets"        => $tickets,
        "search"         => $search,
        "filterStatus"   => $filterStatus,
        "filterCategory" => $filterCategory,
        "filterPriority" => $filterPriority,
        "page"           => $page,
        "pages"          => $pages,
        "limit"          => $limit,
        "total"          => $total,
        "activeMenu"     => "support"
    ]);
}
```

### Método: `ticket()` - Visualização Individual

```php
public function ticket(?array $data): void
{
    $isEdit = false;
    $ticket = new SupportTicket();

    // Edição
    if (!empty($data["id"])) {
        $ticket = (new SupportTicket())->findById((int)$data["id"]);
        if (!$ticket) {
            (new \Source\Support\Message())->error("Chamado não encontrado.")->flash();
            redirect("/app/chamados");
            return;
        }
        $isEdit = true;
    }

    // Busca clientes e funcionários
    $customers = (new \Source\Models\App\Customer())
        ->find(null, null, "person_id")
        ->fetch(true);

    $employees = (new \Source\Models\App\Employee())
        ->find("status = 'active'", null, "person_id")
        ->fetch(true);

    // Detecta de onde veio (histórico ou chamados em aberto)
    $backUrl = url("/app/chamados"); // Padrão
    if (isset($_SERVER['HTTP_REFERER'])) {
        $referer = $_SERVER['HTTP_REFERER'];
        if (strpos($referer, '/chamados/historico') !== false) {
            $backUrl = url("/app/chamados/historico");
        }
    }

    $this->renderPage("tickets/form", [
        "title"       => $isEdit ? "Editar Chamado" : "Novo Chamado",
        "subtitle"    => $isEdit ? "Atualize as informações" : "Registre um novo",
        "ticket"      => $ticket,
        "customers"   => $customers,
        "employees"   => $employees,
        "isEdit"      => $isEdit,
        "activeMenu"  => "support",
        "backUrl"     => $backUrl  // Importante para retornar corretamente
    ]);
}
```

### Método: `addTicketComment()` - Adicionar Comentário

```php
public function addTicketComment(?array $data): void
{
    // Limpa output e define JSON
    if (ob_get_level()) {
        ob_clean();
    }
    header('Content-Type: application/json');
    
    $json = ["success" => false];
    $account = Auth::account();
    
    if (!$account) {
        echo json_encode(["success" => false, "error" => "Não autenticado"]);
        exit;
    }

    $ticketId = (int)($data["ticket_id"] ?? 0);
    $comment = trim($data["comment"] ?? "");
    $isInternal = (int)($data["is_internal"] ?? 0);
    
    // Extrai contexto (admin ou client)
    $context = $data["context"] ?? $_POST["context"] ?? "client";

    if (!$ticketId || !$comment) {
        echo json_encode(["success" => false, "error" => "Dados inválidos"]);
        exit;
    }

    // Cria e salva comentário
    $ticketComment = new TicketComment();
    $ticketComment->ticket_id = $ticketId;
    $ticketComment->user_id = $account->id;
    $ticketComment->comment = $comment;
    $ticketComment->is_internal = $isInternal;
    $ticketComment->context = $context;  // 'admin' ou 'client'

    if (!$ticketComment->save()) {
        echo json_encode(["success" => false, "error" => "Erro ao salvar"]);
        exit;
    }

    // Registra no histórico
    TicketHistory::log(
        $ticketId,
        "comment_added",
        $account->id,
        null,
        null,
        null,
        $isInternal ? "Comentário interno adicionado" : "Comentário adicionado"
    );

    echo json_encode(["success" => true, "message" => "Comentário adicionado!"]);
    exit;
}
```

### Método: `getTicketComments()` - Buscar Comentários

```php
public function getTicketComments(?array $data): void
{
    // Verifica autenticação
    $account = Auth::account();
    
    if (!$account) {
        echo json_encode(["success" => false, "error" => "Não autenticado"]);
        return;
    }

    $ticketId = (int)($data["id"] ?? 0);

    if (!$ticketId) {
        echo json_encode(["success" => false, "error" => "ID inválido"]);
        return;
    }

    try {
        // Verifica se o usuário atual é um funcionário
        $currentUserIsEmployee = (new \Source\Models\App\Employee())
            ->find("user_id = :uid", "uid={$account->id}")
            ->fetch();
        $isClientView = !$currentUserIsEmployee;

        $comments = (new TicketComment())
            ->find("ticket_id = :tid", "tid={$ticketId}")
            ->order("created_at ASC")
            ->fetch(true);

        $result = [];
        if ($comments) {
            foreach ($comments as $comment) {
                // Clientes não podem ver comentários internos
                if ($isClientView && $comment->is_internal == 1) {
                    continue;
                }
                
                $user = $comment->user();
                $person = $user ? $user->person() : null;
                
                // Verifica se é funcionário pelo contexto
                $isEmployee = false;
                if (isset($comment->context) && $comment->context === 'admin') {
                    $isEmployee = true;
                } elseif (!isset($comment->context) && $user) {
                    $employee = (new \Source\Models\App\Employee())
                        ->find("user_id = :uid", "uid={$user->id}")
                        ->fetch();
                    $isEmployee = $employee ? true : false;
                }

                $result[] = [
                    "id" => $comment->id,
                    "comment" => nl2br(htmlspecialchars($comment->comment ?? '')),
                    "is_internal" => (int)($comment->is_internal ?? 0),
                    "created_at" => $comment->created_at 
                        ? date("d/m/Y H:i", strtotime($comment->created_at)) 
                        : '',
                    "user_name" => $person ? $person->full_name : "Usuário",
                    "user_avatar" => $user && method_exists($user, 'photo') 
                        ? $user->photo() : null,
                    "is_employee" => $isEmployee
                ];
            }
        }

        echo json_encode(["success" => true, "comments" => $result]);
    } catch (\Exception $e) {
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
}
```

### Método: `getTicketAttachments()` - Buscar Anexos

```php
public function getTicketAttachments(?array $data): void
{
    $account = Auth::account();
    
    if (!$account) {
        echo json_encode(["success" => false, "error" => "Não autenticado"]);
        return;
    }

    $ticketId = (int)($data["id"] ?? 0);

    if (!$ticketId) {
        echo json_encode(["success" => false, "error" => "ID inválido"]);
        return;
    }

    try {
        $attachments = (new TicketAttachment())
            ->find("ticket_id = :tid", "tid={$ticketId}")
            ->order("uploaded_at DESC")
            ->fetch(true);

        $result = [];
        if ($attachments) {
            foreach ($attachments as $attachment) {
                $user = $attachment->user();
                $person = $user ? $user->person() : null;
                
                // Verifica contexto (admin panel = funcionário, client portal = cliente)
                $isEmployee = false;
                if (isset($attachment->context) && $attachment->context === 'admin') {
                    $isEmployee = true;
                } elseif (!isset($attachment->context) && $user) {
                    $employee = (new \Source\Models\App\Employee())
                        ->find("user_id = :uid", "uid={$user->id}")
                        ->fetch();
                    $isEmployee = $employee ? true : false;
                }

                $result[] = [
                    "id" => $attachment->id,
                    "filename" => $attachment->original_name ?? $attachment->filename,
                    "formatted_size" => method_exists($attachment, 'formattedSize') 
                        ? $attachment->formattedSize() : '',
                    "url" => method_exists($attachment, 'url') 
                        ? $attachment->url() : '',
                    "is_image" => method_exists($attachment, 'isImage') 
                        ? $attachment->isImage() : false,
                    "icon" => method_exists($attachment, 'fileIcon') 
                        ? $attachment->fileIcon() : 'ki-file',
                    "uploaded_at" => $attachment->uploaded_at 
                        ? date("d/m/Y H:i", strtotime($attachment->uploaded_at)) 
                        : '',
                    "user_name" => $person ? $person->full_name : "Usuário",
                    "is_employee" => $isEmployee
                ];
            }
        }

        echo json_encode(["success" => true, "attachments" => $result]);
    } catch (\Exception $e) {
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
}
```

### Método: `updateTicketStatus()` - Alterar Status

```php
public function updateTicketStatus(?array $data): void
{
    $json = [];

    $ticketId = (int)($data["id"] ?? 0);
    $status = $data["status"] ?? null;

    if (!$ticketId || !$status) {
        echo json_encode(["message" => "Dados inválidos"]);
        return;
    }

    $ticket = (new SupportTicket())->findById($ticketId);
    if (!$ticket) {
        echo json_encode(["message" => "Chamado não encontrado"]);
        return;
    }

    // Validar se há funcionário atribuído
    if (empty($ticket->employee_id)) {
        $json["message"] = (new \Source\Support\Message())
            ->warning("É necessário atribuir um funcionário ao chamado antes de alterar o status.")
            ->toast()
            ->render();
        echo json_encode($json);
        return;
    }

    if (!$ticket->updateStatus($status)) {
        echo json_encode(["message" => $ticket->message()->toast()->render()]);
        return;
    }

    $json["message"] = (new \Source\Support\Message())
        ->success("Status do chamado atualizado!")
        ->toast()
        ->render();

    $json["redirect"] = url("/app/chamados");
    echo json_encode($json);
}
```

---

## 🎨 Frontend - Views

### Seção: Formulário Admin (form.php)

#### Componente: Seletor de Funcionário Responsável

```php
<!--begin::Funcionário Responsável-->
<div class="col-md-12">
    <label class="form-label required">Funcionário Responsável</label>
    <select name="employee_id" class="form-select">
        <option value="">Não atribuído</option>
        <?php if (!empty($employees)): ?>
            <?php foreach ($employees as $employee): 
                $employeePerson = $employee->person();
            ?>
                <option value="<?= $employee->person_id; ?>" 
                    <?= ($isEdit && (int)$ticket->employee_id === (int)$employee->person_id) ? 'selected' : ''; ?>>
                <?= $employeePerson->full_name ?? 'Funcionário'; ?> - <?= $employee->role_name ?? $employee->role; ?>
                </option>
            <?php endforeach; ?>
        <?php endif; ?>
    </select>
    <small class="text-muted">
        <i class="ki-outline ki-information-2 fs-6"></i>
        Obrigatório atribuir um funcionário antes de alterar o status do chamado
    </small>
</div>
<!--end::Funcionário Responsável-->
```

#### Componente: Renderização de Comentários (Admin - Color Coded)

```php
// JavaScript no form.php que renderiza comentários
list.forEach(function(comment) {
    // Define cor e ícone baseado no tipo de usuário
    const borderColor = comment.is_employee ? 'border-primary' : 'border-success';
    const badgeColor = comment.is_employee ? 'badge-light-primary' : 'badge-light-success';
    const userType = comment.is_employee ? 'Funcionário' : 'Cliente';
    const userIcon = comment.is_employee ? 'ki-shield-tick' : 'ki-profile-user';
    
    html += `
        <div class="card ${borderColor} border border-2 mb-5 shadow-sm">
            <div class="card-body p-5">
                <div class="d-flex align-items-start">
                    <div class="symbol symbol-45px me-4">
                        <div class="symbol-label bg-light-${comment.is_employee ? 'primary' : 'success'}">
                            <i class="ki-outline ${userIcon} fs-2 text-${comment.is_employee ? 'primary' : 'success'}"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <span class="fw-bold text-gray-800 fs-5">${comment.user_name}</span>
                                <span class="${badgeColor} badge ms-2">${userType}</span>
                            </div>
                            <span class="text-muted fs-7">${comment.created_at}</span>
                        </div>
                        <div class="text-gray-700 fs-6 mb-2">${comment.comment}</div>
                        ${comment.is_internal ? '<span class="badge badge-light-warning"><i class="ki-outline ki-lock fs-7 me-1"></i>Interno</span>' : ''}
                    </div>
                </div>
            </div>
        </div>
    `;
});
```

#### Componente: Renderização de Anexos (Admin - Color Coded)

```php
// JavaScript que renderiza anexos com diferenciação
list.forEach(function(att) {
    const bgColor = att.is_employee ? 'bg-light-primary' : 'bg-light-success';
    const iconColor = att.is_employee ? 'text-primary' : 'text-success';
    const badgeColor = att.is_employee ? 'badge-light-primary' : 'badge-light-success';
    const userType = att.is_employee ? 'Funcionário' : 'Cliente';
    
    html += `
        <div class="card mb-4 border border-2 ${att.is_employee ? 'border-primary' : 'border-success'} shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center flex-grow-1">
                        <div class="symbol symbol-50px ${bgColor} me-4">
                            <i class="ki-outline ${att.icon || 'ki-file'} fs-2x ${iconColor}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <a href="${att.url}" target="_blank" class="fw-bold text-gray-800 text-hover-primary fs-6 d-block">
                                ${att.filename}
                            </a>
                            <div class="text-muted fs-7 mt-1">
                                <span class="fw-semibold">${att.formatted_size || att.size || ''}</span>
                                <span class="mx-2">•</span>
                                <span>${att.uploaded_at}</span>
                            </div>
                            <div class="mt-2">
                                <span class="${badgeColor} badge">${userType}: ${att.user_name}</span>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-sm btn-icon btn-light-danger" onclick="deleteAttachment(${att.id})">
                        <i class="ki-outline ki-trash fs-4"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
});
```

---

## 📱 Frontend - JavaScript

### Função: Alteração de Status com Modal

```javascript
function updateStatus(id, status) {
    const messages = {
        'in-progress': 'Deseja marcar este chamado como "Em Andamento"?',
        'resolved': 'Deseja marcar este chamado como "Resolvido"?',
        'canceled': 'Deseja cancelar este chamado?'
    };

    const titles = {
        'in-progress': 'Marcar como Em Andamento',
        'resolved': 'Resolver Chamado',
        'canceled': 'Cancelar Chamado'
    };

    const icons = {
        'in-progress': 'info',
        'resolved': 'success',
        'canceled': 'warning'
    };

    Swal.fire({
        title: titles[status] || 'Confirmar alteração',
        text: messages[status] || 'Confirmar alteração de status?',
        icon: icons[status] || 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim, confirmar',
        cancelButtonText: 'Cancelar',
        buttonsStyling: false,
        customClass: {
            confirmButton: 'btn btn-primary',
            cancelButton: 'btn btn-light'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const params = new URLSearchParams();
            params.append('id', id);
            params.append('status', status);
            const csrfEl = document.querySelector("input[name='csrf']");
            if (csrfEl) params.append('csrf', csrfEl.value);
            
            fetch("<?= url('/app/chamado/status'); ?>", {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: params.toString()
            })
            .then(response => response.json())
            .then(data => {
                if (data.redirect) {
                    Swal.fire({
                        title: 'Sucesso!',
                        text: 'Status atualizado com sucesso!',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = data.redirect;
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Erro!',
                    text: 'Erro ao atualizar status',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'btn btn-danger'
                    }
                });
            });
        }
    });
}
```

### Função: Carregamento de Comentários (Admin)

```javascript
function loadComments() {
    fetch("<?= url('/app/chamado/comentarios/' . $ticket->id); ?>", {
        method: 'GET',
        credentials: 'include',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => {
        return response.text();
    })
    .then(responseText => {
        let response;
        try {
            response = JSON.parse(responseText);
        } catch (e) {
            $('#commentsList').html('<div class="text-center text-danger py-10">Erro: Resposta inválida.</div>');
            return;
        }
        
        if (!response.success && response.error) {
            $('#commentsList').html('<div class="text-center text-danger py-10">Erro: ' + response.error + '</div>');
            return;
        }
        
        const list = (response && response.comments) ? response.comments : (Array.isArray(response) ? response : []);
        
        let html = '';
        if (list.length === 0) {
            html = '<div class="text-center text-muted py-10">Nenhum comentário ainda.</div>';
        } else {
            list.forEach(function(comment) {
                const borderColor = comment.is_employee ? 'border-primary' : 'border-success';
                const badgeColor = comment.is_employee ? 'badge-light-primary' : 'badge-light-success';
                const userType = comment.is_employee ? 'Funcionário' : 'Cliente';
                const userIcon = comment.is_employee ? 'ki-shield-tick' : 'ki-profile-user';
                
                html += `
                    <div class="card ${borderColor} border border-2 mb-5 shadow-sm">
                        <div class="card-body p-5">
                            <div class="d-flex align-items-start">
                                <div class="symbol symbol-45px me-4">
                                    <div class="symbol-label bg-light-${comment.is_employee ? 'primary' : 'success'}">
                                        <i class="ki-outline ${userIcon} fs-2 text-${comment.is_employee ? 'primary' : 'success'}"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <span class="fw-bold text-gray-800 fs-5">${comment.user_name}</span>
                                            <span class="${badgeColor} badge ms-2">${userType}</span>
                                        </div>
                                        <span class="text-muted fs-7">${comment.created_at}</span>
                                    </div>
                                    <div class="text-gray-700 fs-6 mb-2">${comment.comment}</div>
                                    ${comment.is_internal ? '<span class="badge badge-light-warning"><i class="ki-outline ki-lock fs-7 me-1"></i>Interno</span>' : ''}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
        }
        
        $('#commentsList').html(html);
    });
}
```

### Função: Carregamento de Comentários (Cliente)

```javascript
function loadComments() {
    const url = '<?= url("app/chamado/comentarios") ?>/' + ticketId;

    fetch(url, {
        credentials: 'include',
        headers: {
            'Accept': 'application/json'
        }
    })
        .then(response => {
            return response.json();
        })
        .then(data => {
            // Handle both formats
            const comments = Array.isArray(data) ? data : (data.comments || []);
            
            // Filtrar comentários internos - clientes não podem ver
            const visibleComments = comments.filter(comment => comment.is_internal != 1);
            
            document.getElementById('commentsCount').textContent = visibleComments.length;
            
            if (visibleComments.length === 0) {
                document.getElementById('commentsContainer').innerHTML = `
                    <div class="text-center py-10">
                        <i class="ki-outline ki-message-text fs-3x text-muted mb-3"></i>
                        <p class="text-muted">Nenhum comentário ainda</p>
                    </div>
                `;
            } else {
                let html = '';
                visibleComments.forEach(function(comment) {
                    const borderColor = comment.is_employee ? 'border-primary' : 'border-success';
                    const badgeColor = comment.is_employee ? 'badge-light-primary' : 'badge-light-success';
                    const userType = comment.is_employee ? 'Funcionário' : 'Cliente';
                    const userIcon = comment.is_employee ? 'ki-shield-tick' : 'ki-profile-user';
                    
                    html += `
                        <div class="card ${borderColor} border border-2 mb-5 shadow-sm">
                            <div class="card-body p-5">
                                <div class="d-flex align-items-start">
                                    <div class="symbol symbol-45px me-4">
                                        <div class="symbol-label bg-light-${comment.is_employee ? 'primary' : 'success'}">
                                            <i class="ki-outline ${userIcon} fs-2 text-${comment.is_employee ? 'primary' : 'success'}"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div>
                                                <span class="fw-bold text-gray-800 fs-5">${comment.user_name || 'Usuário'}</span>
                                                <span class="${badgeColor} badge ms-2">${userType}</span>
                                            </div>
                                            <span class="text-muted fs-7">${comment.created_at || ''}</span>
                                        </div>
                                        <div class="text-gray-700 fs-6">${comment.comment || ''}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                document.getElementById('commentsContainer').innerHTML = html;
            }
        })
        .catch(error => {
            document.getElementById('commentsContainer').innerHTML = `
                <div class="text-center py-10">
                    <i class="ki-outline ki-message-text fs-3x text-danger mb-3"></i>
                    <p class="text-danger">Erro ao carregar comentários</p>
                </div>
            `;
        });
}
```

---

## 🔄 Fluxo de Dados

### Fluxo: Criar Novo Comentário

```
┌─────────────────────────────┐
│  Frontend - Formulário      │
│  (form.php ou view-ticket.php)│
└──────────────┬──────────────┘
               │ (AJAX POST)
               ▼
┌─────────────────────────────────────┐
│  Backend - addTicketComment()        │
│  - Valida autenticação              │
│  - Extrai contexto (admin/client)   │
│  - Salva comentário no BD           │
│  - Registra no histórico            │
└──────────────┬──────────────────────┘
               │ (JSON Response)
               ▼
┌─────────────────────────────┐
│  Frontend - Callback        │
│  - Mostra toast de sucesso  │
│  - Recarrega comentários    │
└─────────────────────────────┘
```

### Fluxo: Alterar Status

```
┌─────────────────────────────┐
│  Frontend - Botão Ações     │
│  (main.php ou history.php)  │
└──────────────┬──────────────┘
               │ (Clique)
               ▼
┌─────────────────────────────┐
│  SweetAlert Modal            │
│  (Confirmação com mensagem) │
└──────────────┬──────────────┘
               │ (Confirmado)
               ▼
┌──────────────────────────────────────┐
│  Backend - updateTicketStatus()       │
│  - Valida funcionário atribuído      │
│  - Atualiza status no BD             │
│  - Registra no histórico             │
│  - Retorna URL de redirecionamento   │
└──────────────┬───────────────────────┘
               │ (JSON Response)
               ▼
┌─────────────────────────────┐
│  Frontend - Toast Sucesso   │
│  - Redireciona para página  │
└─────────────────────────────┘
```

### Fluxo: Reabrir Chamado (Histórico)

```
┌────────────────────────────────┐
│  Frontend - Botão Reabrir      │
│  (history.php)                 │
└──────────────┬─────────────────┘
               │ (Clique)
               ▼
┌────────────────────────────────┐
│  SweetAlert Modal               │
│  "Deseja reabrir este chamado?" │
└──────────────┬─────────────────┘
               │ (Confirmado)
               ▼
┌──────────────────────────────────────┐
│  Backend - updateTicketStatus()       │
│  - status = 'open'                   │
│  - Registra no histórico             │
└──────────────┬───────────────────────┘
               │ (JSON Response)
               ▼
┌────────────────────────────────┐
│  Frontend - Toast Sucesso      │
│  - Redireciona para /chamados  │
└────────────────────────────────┘
```

---

## 🔐 Recursos de Segurança

### 1. Autenticação

```php
$account = Auth::account();
if (!$account) {
    echo json_encode(["success" => false, "error" => "Não autenticado"]);
    exit;
}
```

### 2. Validação de Funcionário Obrigatório

```php
// Antes de alterar status, valida se há funcionário
if (empty($ticket->employee_id)) {
    $json["message"] = (new \Source\Support\Message())
        ->warning("É necessário atribuir um funcionário ao chamado antes de alterar o status.")
        ->toast()
        ->render();
    echo json_encode($json);
    return;
}
```

### 3. Filtragem de Comentários Internos para Clientes

```php
// Verifica se é cliente
$isClientView = !$currentUserIsEmployee;

// Pula comentários internos para clientes
if ($isClientView && $comment->is_internal == 1) {
    continue;
}
```

### 4. CSRF Protection

```php
<?= csrf_input(); ?>  // Gera token CSRF
```

### 5. Sanitização de Entrada

```php
// Sanitização de comentário
$comment = trim($data["comment"] ?? "");

// Sanitização na saída
"comment" => nl2br(htmlspecialchars($comment ?? '')),
```

### 6. Detecção de Contexto (Admin vs Cliente)

```php
// Extrai contexto do formulário oculto
$context = $data["context"] ?? $_POST["context"] ?? "client";

// 'admin' para requisições do painel admin
// 'client' para requisições do portal cliente
```

---

## 📚 Exemplos de Uso

### Exemplo 1: Listar Chamados Em Aberto

**Rota**: GET `/app/chamados/1/10`

**Resposta** (renderiza view com):
- Lista de 10 chamados por página
- Status: 'open' ou 'in-progress' apenas
- Ordenados por data de abertura (DESC)

### Exemplo 2: Buscar Comentários de um Chamado

**Rota**: GET `/app/chamado/comentarios/123`

**Resposta JSON**:
```json
{
  "success": true,
  "comments": [
    {
      "id": 1,
      "comment": "<p>Problema resolvido</p>",
      "is_internal": 0,
      "created_at": "09/11/2025 14:30",
      "user_name": "João Silva",
      "is_employee": true
    },
    {
      "id": 2,
      "comment": "<p>Obrigado pela ajuda!</p>",
      "is_internal": 0,
      "created_at": "09/11/2025 15:00",
      "user_name": "Maria Cliente",
      "is_employee": false
    }
  ]
}
```

### Exemplo 3: Adicionar Comentário Admin

**Rota**: POST `/app/chamado/comentario/adicionar`

**Dados**:
```json
{
  "ticket_id": 123,
  "comment": "Aguardando informações do cliente",
  "is_internal": 1,
  "context": "admin",
  "csrf": "token_csrf_aqui"
}
```

**Resposta**:
```json
{
  "success": true,
  "message": "Comentário adicionado!"
}
```

### Exemplo 4: Alterar Status do Chamado

**Rota**: POST `/app/chamado/status`

**Dados**:
```json
{
  "id": 123,
  "status": "resolved",
  "csrf": "token_csrf_aqui"
}
```

**Resposta (Sucesso)**:
```json
{
  "message": "<div class='toast-container'>...</div>",
  "redirect": "http://localhost/skywavefibra/app/chamados"
}
```

**Resposta (Erro - Sem Funcionário)**:
```json
{
  "message": "<div class='alert alert-warning'>É necessário atribuir um funcionário...</div>"
}
```

### Exemplo 5: Upload de Anexo

**Rota**: POST `/app/chamado/123/anexo`

**Dados (FormData)**:
```
file: [File objeto]
context: "admin"
csrf: "token_csrf_aqui"
```

**Resposta (Sucesso)**:
```json
{
  "success": true,
  "message": "Arquivo enviado com sucesso!",
  "attachment": {
    "id": 456,
    "filename": "documento.pdf",
    "formatted_size": "2.5 MB",
    "url": "http://localhost/storage/attachments/documento.pdf",
    "icon": "ki-file-pdf",
    "is_employee": true
  }
}
```

---

## 🎯 Resumo das Funcionalidades

| Funcionalidade | Admin | Cliente |
|---|---|---|
| **Visualizar Chamados** | ✅ Abertos e Resolvidos | ✅ Apenas Próprio |
| **Editar Chamado** | ✅ Completo |
| **Adicionar Comentário** | ✅ Público + Interno | ✅ Público Apenas |
| **Ver Comentário Interno** | ✅ Sim | 
| **Upload Anexo** | ✅ Sim | ✅ Sim |
| **Excluir Anexo** | ✅ Sim | 
| **Alterar Status** | ✅ Sim | 
| **Atribuir Funcionário** | ✅ Sim | 
| **Visualizar Dashboard** | ✅ Sim | 
