<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemDesignPrice extends Model
{
    protected $fillable = [
        'price'
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function design(){
        return $this->belongsTo('App\SystemDesignType', 'system_design_type_id');
    }
}
