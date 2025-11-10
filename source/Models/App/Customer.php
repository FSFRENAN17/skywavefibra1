<?php

namespace Source\Models\App;

use Source\Core\Model;
use Source\Models\Person;

class Customer extends Model
{
    public function __construct()
    {
        // customer table uses person_id as primary key
        parent::__construct("customer", ["person_id"], ["person_id", "status"]);
    }

    /**
     * Retorna a pessoa relacionada ao cliente
     * 
     * @return Person|null
     */
    public function person(): ?Person
    {
        if (!empty($this->person_id)) {
            return (new Person())->findById((int)$this->person_id);
        }
        return null;
    }

    /**
     * Retorna a conta (account) relacionada ao cliente
     * 
     * @return \Source\Models\Account|null
     */
    public function account(): ?\Source\Models\Account
    {
        if (!empty($this->account_id)) {
            return (new \Source\Models\Account())->findById((int)$this->account_id);
        }
        return null;
    }
}