<?php

namespace Source\Models\App;

use Source\Core\Model;

/**
 * Class TicketAttachment
 * @package Source\Models\App
 */
class TicketAttachment extends Model
{
    /**
     * TicketAttachment constructor.
     */
    public function __construct()
    {
        parent::__construct("ticket_attachment", ["id"], [
            "ticket_id",
            "user_id",
            "filename",
            "original_name",
            "file_path"
        ]);
    }

    /**
     * Retorna o usuário que fez o upload
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
     * Retorna o caminho completo do arquivo
     * 
     * @return string
     */
    public function fullPath(): string
    {
        return __DIR__ . "/../../../storage/" . $this->file_path;
    }

    /**
     * Retorna a URL do arquivo
     * 
     * @return string
     */
    public function url(): string
    {
        return url("/storage/" . $this->file_path);
    }

    /**
     * Formata o tamanho do arquivo
     * 
     * @return string
     */
    public function formattedSize(): string
    {
        $size = $this->file_size ?? 0;
        
        if ($size < 1024) {
            return $size . ' B';
        } elseif ($size < 1048576) {
            return round($size / 1024, 2) . ' KB';
        } elseif ($size < 1073741824) {
            return round($size / 1048576, 2) . ' MB';
        } else {
            return round($size / 1073741824, 2) . ' GB';
        }
    }

    /**
     * Verifica se é uma imagem
     * 
     * @return bool
     */
    public function isImage(): bool
    {
        $imageTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        return in_array($this->mime_type ?? "", $imageTypes);
    }

    /**
     * Retorna o ícone do tipo de arquivo
     * 
     * @return string
     */
    public function fileIcon(): string
    {
        $mime = $this->mime_type ?? "";
        
        if (str_contains($mime, 'image')) {
            return 'ki-file-sheet';
        } elseif (str_contains($mime, 'pdf')) {
            return 'ki-file-down';
        } elseif (str_contains($mime, 'word') || str_contains($mime, 'document')) {
            return 'ki-document';
        } elseif (str_contains($mime, 'excel') || str_contains($mime, 'spreadsheet')) {
            return 'ki-file-up';
        } elseif (str_contains($mime, 'zip') || str_contains($mime, 'rar') || str_contains($mime, 'compressed')) {
            return 'ki-folder-down';
        }
        
        return 'ki-file';
    }

    /**
     * Exclui o arquivo físico e o registro do banco
     * 
     * @return bool
     */
    public function destroyWithFile(): bool
    {
        // Remove o arquivo físico
        $filePath = $this->fullPath();
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Remove o registro do banco
        return $this->destroy();
    }
}
