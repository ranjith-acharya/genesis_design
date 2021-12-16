<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemDesignType extends Model
{
   public function prices(){
       return $this->hasMany('App\SystemDesignPrice');
   }

    public function latestPrice() {
        return $this->hasOne('App\SystemDesignPrice')->latest();
    }
}
