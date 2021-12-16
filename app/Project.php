<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name', 'description', 'street_1', 'street_2', 'city', 'state', 'zip', 'country', 'project_type_id', 'customer_id', 'latitude', 'longitude', 'company_id'
    ];

    protected $casts = [
        'created_at' => 'datetime:F dS Y - h:i A',
    ];

    public function type()
    {
        return $this->belongsTo('App\ProjectType', 'project_type_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\User', 'customer_id');
    }

    public function engineer()
    {
        return $this->belongsTo('App\User', 'engineer_id');
    }

    public function designs()
    {
        return $this->hasMany('App\SystemDesign');
    }

    public function files()
    {
        return $this->hasMany('App\ProjectFile');
    }
}
