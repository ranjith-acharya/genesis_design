<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemDesignMessage extends Model
{


    protected $casts = [
        'created_at' => 'datetime:F dS Y - h:i A',
    ];

    public function userType(){
        return $this->belongsTo('App\User', 'sender_id')->select('role', 'id');
    }

    public function files()
    {
        return $this->hasMany('App\SystemDesignMessageFile');
    }

    public function design(){
        return $this->belongsTo('App\SystemDesign');
    }
}
