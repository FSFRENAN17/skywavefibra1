<?php

namespace Source\Models\App;

use Source\Core\Model;

class Address extends Model
{
    public function __construct()
    {
        parent::__construct("address", ["id"], []);
    }
}