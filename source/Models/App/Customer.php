<?php

namespace Source\Models\App;

use Source\Core\Model;

class Customer extends Model
{
    public function __construct()
    {
        parent::__construct("customer", ["id"], ["person_id", "status"]);
    }
}