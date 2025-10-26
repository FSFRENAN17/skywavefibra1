<?php

namespace Source\Models\App;

use Source\Core\Model;

class CustomerEquipment extends Model
{
    public function __construct()
    {
        // chaves primárias compostas são suportadas por create/update dependendo do seu Model.
        parent::__construct("customer_equipment", ["customer_id", "equipment_id"], ["start_date", "end_date"]);
    }
}
