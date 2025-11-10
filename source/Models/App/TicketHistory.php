<?php

namespace Source\Models\App;

use Source\Core\Model;

/**
 * Class TicketHistory
 * @package Source\Models\App
 */
class TicketHistory extends Model
{
    /**
     * TicketHistory constructor.
     */
    public function __construct()
    {
        parent::__construct("ticket_history", ["id"], [
            "ticket_id",
            "action"
        ]);
    }

    /**
     * Cria um registro de histórico
     * 
     * @param int $ticketId ID do ticket
     * @param string $action Ação realizada
     * @param int|null $userId ID do usuário que fez a ação
     * @param string|null $fieldChanged Campo alterado
     * @param string|null $oldValue Valor antigo
     * @param string|null $newValue Valor novo
     * @param string|null $description Descrição adicional
     * @return bool
     */
    public static function log(
        int $ticketId,
        string $action,
        ?int $userId = null,
        ?string $fieldChanged = null,
        ?string $oldValue = null,
        ?string $newValue = null,
        ?string $description = null
    ): bool {
        $history = new self();
        $history->ticket_id = $ticketId;
        $history->user_id = $userId;
        $history->action = $action;
        $history->field_changed = $fieldChanged;
        $history->old_value = $oldValue;
        $history->new_value = $newValue;
        $history->description = $description;

        return $history->save();
    }

    /**
     * Retorna o usuário que fez a ação
     * 
     * @return \Source\Models\Account|null
     */
    public function user(): ?\Source\Models\Account
    {
        if (!empty($this->user_id)) {
            return (new \Source\Models\Account())->findById((int)$this->user_id);
        }
        return null;
    }

    /**
     * Retorna o ticket relacionado
     * 
     * @return SupportTicket|null
     */
    public function ticket(): ?SupportTicket
    {
        if (!empty($this->ticket_id)) {
            return (new SupportTicket())->findById((int)$this->ticket_id);
        }
        return null;
    }

    /**
     * Retorna a descrição da ação em português
     * 
     * @return string
     */
    public function actionLabel(): string
    {
        $labels = [
            "created" => "Criado",
            "updated" => "Atualizado",
            "status_changed" => "Status alterado",
            "assigned" => "Atribuído",
            "comment_added" => "Comentário adicionado",
            "attachment_added" => "Anexo adicionado"
        ];

        return $labels[$this->action ?? ""] ?? ($this->action ?? "");
    }

    /**
     * Retorna o ícone da ação
     * 
     * @return string
     */
    public function actionIcon(): string
    {
        $icons = [
            "created" => "ki-plus-circle",
            "updated" => "ki-pencil",
            "status_changed" => "ki-arrows-circle",
            "assigned" => "ki-user",
            "comment_added" => "ki-message-text-2",
            "attachment_added" => "ki-file"
        ];

        return $icons[$this->action ?? ""] ?? "ki-information-5";
    }

    /**
     * Retorna a cor da ação
     * 
     * @return string
     */
    public function actionColor(): string
    {
        $colors = [
            "created" => "success",
            "updated" => "info",
            "status_changed" => "warning",
            "assigned" => "primary",
            "comment_added" => "info",
            "attachment_added" => "secondary"
        ];

        return $colors[$this->action ?? ""] ?? "secondary";
    }
}
