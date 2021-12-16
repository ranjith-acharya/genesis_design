<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemDesign extends Model
{
    protected $fillable = ['type', 'status', 'fields', 'project_id'];

    protected $casts = [
        'fields' => 'array',
        'created_at' => 'datetime:F dS Y - h:i A',
    ];

    public function type(){
        return $this->belongsTo('App\SystemDesignType', 'system_design_type_id');
    }

    public function project(){
        return $this->belongsTo('App\Project');
    }

    public function files(){
        return $this->hasMany('App\DesignFile');
    }

    public function proposals(){
        return $this->hasMany('App\Proposal');
    }

    public function changeRequests(){
        return $this->hasManyThrough('App\ChangeRequest', 'App\Proposal');
    }

    public function messages(){
        return $this->hasMany('App\SystemDesignMessage');
    }

    public function customer(){
        return $this->hasOneThrough('App\User', 'App\Project', 'project_id');
    }
}
