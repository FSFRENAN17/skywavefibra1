<?php

namespace Source\Models\App;

use Source\Core\Model;

/**
 * Class TicketComment
 * @package Source\Models\App
 */
class TicketComment extends Model
{
    /**
     * TicketComment constructor.
     */
    public function __construct()
    {
        parent::__construct("ticket_comment", ["id"], [
            "ticket_id",
            "user_id",
            "comment"
        ]);
    }

    /**
     * Retorna o usuário que fez o comentário
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
     * Verifica se o comentário é interno
     * 
     * @return bool
     */
    public function isInternal(): bool
    {
        return !empty($this->is_internal);
    }

    /**
     * Formata o comentário para exibição
     * 
     * @return string
     */
    public function formattedComment(): string
    {
        return nl2br(htmlspecialchars($this->comment ?? ""));
    }
}
