<?php

namespace App\Models;

use Framework\AbstractModel;

class User extends AbstractModel
{
    protected $fillable = ['name', 'email', 'password'];
    public $logTimestamp = FALSE;
    protected $table = 'users';

    public static function getRules(int $id = null): array
    {
        $rules = [
            'name'      => 'min:1',
            'email'     => 'email',
            'password'  => 'min:6'
        ];

        if (is_null($id)) {
            $rules['name'] .= '|required';
            $rules['email'] .= '|required|unique:users,email';
            $rules['password'] .= '|required';
        } else {
            $rules['email'] .= "|unique:users,email,id,$id";
        }

        return  $rules;
    }
}
