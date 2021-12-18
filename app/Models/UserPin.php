<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPin extends Model
{
    protected $fillable = ['user_id', 'current_pins', 'pins_list', 'counter'];

    protected $casts = [
        'current_pins' => 'array',
        'pins_list' => 'array'
    ];

}

