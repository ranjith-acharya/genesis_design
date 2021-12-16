<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChangeRequest extends Model
{
    public function files(){
        return $this->hasMany('App\ChangeRequestFile');
    }
}
