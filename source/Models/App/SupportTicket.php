<?php

namespace Source\Models\App;

use Source\Core\Model;

/**
 * Class SupportTicket
 * @package Source\Models\App
 */
class SupportTicket extends Model
{
    /**
     * SupportTicket constructor.
     */
    public function __construct()
    {
        parent::__construct("support_ticket", ["id"], [
            "customer_id",
            "category",
            "priority",
            "status"
        ]);
    }

    /**
     * Bootstrap inicial para criar/atualizar ticket
     * 
     * @param int $customer_id ID do cliente
     * @param string $category Categoria do chamado
     * @param string $priority Prioridade do chamado
     * @param string $description Descrição do problema
     * @param int|null $employee_id ID do funcionário responsável (opcional)
     * @param string $status Status do chamado
     * @return SupportTicket
     */
    public function bootstrap(
        int $customer_id,
        string $category,
        string $priority,
        string $description,
        ?int $employee_id = null,
        string $status = "open"
    ): SupportTicket {
    // customer_id and employee_id must use person_id (PK of related tables)
    $this->customer_id = $customer_id; // expects customer.person_id
    $this->employee_id = $employee_id; // expects employee.person_id
    $this->category = $category;
    $this->priority = $priority;
    $this->description = $description;
    $this->status = $status;

        return $this;
    }

    /**
     * Retorna o cliente relacionado ao ticket
     * 
     * @return Customer|null
     */
    public function customer(): ?Customer
    {
        if (!empty($this->customer_id)) {
            return (new Customer())->find("person_id = :pid","pid={$this->customer_id}")->fetch();
        }
        return null;
    }

    /**
     * Retorna o funcionário responsável pelo ticket
     * 
     * @return Employee|null
     */
    public function employee(): ?Employee
    {
        if (!empty($this->employee_id)) {
            return (new Employee())->find("person_id = :pid","pid={$this->employee_id}")->fetch();
        }
        return null;
    }

    /**
     * Atribui um funcionário ao ticket
     * 
     * @param int $employee_id
     * @return bool
     */
    public function assign(int $employee_id): bool
    {
        $oldEmployeeId = $this->employee_id;
        $this->employee_id = $employee_id;
        
        // Se estava "open", muda para "in-progress"
        if ($this->status === "open") {
            $this->status = "in-progress";
        }
        
        $saved = $this->save();
        
        // Registra no histórico
        if ($saved && $this->id && class_exists('\Source\Models\App\TicketHistory')) {
            \Source\Models\App\TicketHistory::log(
                (int)$this->id,
                "assigned",
                null,
                "employee_id",
                $oldEmployeeId,
                $employee_id,
                "Funcionário atribuído ao chamado"
            );
        }
        
        return $saved;
    }

    /**
     * Atualiza o status do ticket
     * 
     * @param string $status Novo status (open, in-progress, resolved, canceled)
     * @return bool
     */
    public function updateStatus(string $status): bool
    {
        $oldStatus = $this->status;
        $this->status = $status;
        
        // Se foi resolvido ou cancelado, registra a data de fechamento
        if (in_array($status, ["resolved", "canceled"])) {
            $this->closed_at = date("Y-m-d H:i:s");
        }
        
        $saved = $this->save();
        
        // Registra no histórico
        if ($saved && $this->id && class_exists('\Source\Models\App\TicketHistory')) {
            \Source\Models\App\TicketHistory::log(
                (int)$this->id,
                "status_changed",
                null,
                "status",
                $oldStatus,
                $status,
                "Status alterado de {$this->statusLabelFromValue($oldStatus)} para {$this->statusLabelFromValue($status)}"
            );
        }
        
        return $saved;
    }

    /**
     * Helper para obter label de status por valor
     */
    private function statusLabelFromValue(string $status): string
    {
        $labels = [
            "open" => "Em Aberto",
            "in-progress" => "Em Andamento",
            "resolved" => "Resolvido",
            "canceled" => "Cancelado"
        ];
        return $labels[$status] ?? $status;
    }

    /**
     * Retorna a descrição da categoria
     * 
     * @return string
     */
    public function categoryLabel(): string
    {
        $labels = [
            "installation" => "Instalação",
            "maintenance" => "Manutenção",
            "billing" => "Cobrança",
            "cancellation" => "Cancelamento",
            "technical" => "Técnico"
        ];

        return $labels[$this->category ?? ""] ?? ($this->category ?? "");
    }

    /**
     * Retorna a descrição da prioridade
     * 
     * @return string
     */
    public function priorityLabel(): string
    {
        $labels = [
            "low" => "Baixa",
            "medium" => "Média",
            "high" => "Alta",
            "critical" => "Crítica"
        ];

        return $labels[$this->priority ?? ""] ?? ($this->priority ?? "");
    }

    /**
     * Retorna a descrição do status
     * 
     * @return string
     */
    public function statusLabel(): string
    {
        $labels = [
            "open" => "Em Aberto",
            "in-progress" => "Em Andamento",
            "resolved" => "Resolvido",
            "canceled" => "Cancelado"
        ];

        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Retorna a classe de badge do Bootstrap baseada na prioridade
     * 
     * @return string
     */
    public function priorityBadge(): string
    {
        $badges = [
            "low" => "badge badge-light-info",
            "medium" => "badge badge-light-warning",
            "high" => "badge badge-light-danger",
            "critical" => "badge badge-danger"
        ];

        $class = $badges[$this->priority ?? ""] ?? "badge badge-secondary";
        $label = htmlspecialchars($this->priorityLabel(), ENT_QUOTES, 'UTF-8');
        return "<span class=\"{$class}\">{$label}</span>";
    }

    /**
     * Retorna a classe de badge do Bootstrap baseada no status
     * 
     * @return string
     */
    public function statusBadge(): string
    {
        $badges = [
            "open" => "badge badge-light-primary",
            "in-progress" => "badge badge-light-warning",
            "resolved" => "badge badge-light-success",
            "canceled" => "badge badge-light-secondary"
        ];

        $class = $badges[$this->status] ?? "badge badge-secondary";
        $label = htmlspecialchars($this->statusLabel(), ENT_QUOTES, 'UTF-8');
        return "<span class=\"{$class}\">{$label}</span>";
    }
}
