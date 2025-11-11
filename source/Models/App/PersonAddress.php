<?php

namespace Source\Models\App;

use Source\Core\Model;

class PersonAddress extends Model
{
    public function __construct()
    {
        parent::__construct("person_address", ["id"], ["person_id", "address_id"]);
    }
}