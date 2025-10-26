<?php

namespace Source\Models\App;

use Source\Core\Model;

class Employee extends Model
{
    public function __construct()
    {
        parent::__construct("employee", ["id"], ["person_id", "role", "hire_date"]);
    }
}