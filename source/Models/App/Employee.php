<?php

namespace Source\Models\App;

use Source\Core\Model;

class Employee extends Model
{
    public function __construct()
    {
        // employee table uses person_id as primary key (verify schema)
        parent::__construct("employee", ["person_id"], ["person_id", "role", "hire_date"]);
    }

    /**
     * Retorna a pessoa vinculada ao funcionário
     */
    public function person(): ?\Source\Models\Person
    {
        return (new \Source\Models\Person())->findById($this->person_id);
    }

    public function photo(): string
    {
        $account = (new \Source\Models\Account())
            ->find("person_id = :pid", "pid={$this->person_id}")
            ->fetch();
        if ($account && method_exists($account, 'photo')) {
            return $account->photo();
        }
        return url("/shared/assets/images/avatar.jpg");
    }


    /**
     * Retorna um label e uma cor para o papel (cargo)
     */
    public function roleLabel(): array
    {
        $roles = [
            "admin"      => ["Administrador", "danger"],
            "support"    => ["Atendimento", "info"],
            "technician" => ["Técnico", "primary"],
            "finance"    => ["Financeiro", "success"]
        ];

        return $roles[$this->role] ?? ["Desconhecido", "secondary"];
    }

    /**
     * Retorna um label e uma cor para o status
     */
    public function statusLabel(): array
    {
        $statuses = [
            "active"     => ["Ativo", "success"],
            "terminated" => ["Desligado", "danger"]
        ];

        return $statuses[$this->status] ?? ["Desconhecido", "secondary"];
    }
}
