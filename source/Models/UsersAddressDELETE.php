<?php


namespace Source\Models;


use Source\Core\Model;

class UsersAddress extends Model
{
    public function __construct()
    {
        parent::__construct("users_address", ["id"], ["user"]);
    }
}
