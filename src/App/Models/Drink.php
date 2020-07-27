<?php

namespace App\Models;

use Framework\AbstractModel;

class Drink extends AbstractModel
{
    protected $fillable = ['drink_ml', 'user_id'];
    public $logTimestamp = TRUE;
    protected $table = 'drinks';

    public static function getRules(int $id = null): array
    {
        $rules = [
            'drink_ml' => 'numeric'
        ];

        if (is_null($id)) {
            $rules['drink_ml'] .= '|required';
        }

        return $rules;
    }
}
