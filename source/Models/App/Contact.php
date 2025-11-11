<?php

namespace Source\Models\App;

use Source\Core\Model;

class Contact extends Model
{
    public function __construct()
    {
        parent::__construct("contact", ["id"], ["person_id", "contact_type", "value"]);
    }
}