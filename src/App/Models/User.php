<?php

namespace App\Models;

use Framework\AbstractModel;

class User extends AbstractModel
{
    protected $fillable = ['name', 'email', 'password'];
    protected $logTimestamp = FALSE;
    protected $table = 'users';
}