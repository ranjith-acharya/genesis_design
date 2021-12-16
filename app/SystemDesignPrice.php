<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemDesignPrice extends Model
{
    protected $casts = [
        'price' => 'decimal:2',
    ];
}
