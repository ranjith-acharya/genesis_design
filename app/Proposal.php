<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    public function files(){
        return $this->hasMany('App\ProposalFile');
    }

    public function changeRequest(){
        return $this->hasOne('App\ChangeRequest');
    }
}
