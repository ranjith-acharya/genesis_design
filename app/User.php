<?php

namespace App;

use App\Statics\Statics;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'company', 'phone', 'email', 'password', 'company_id', 'stripe_id', 'default_payment_method','role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    // public function getAllProjects()
    // {
    //     return $this->hasMany('App\Project');
    // }
    public function projects()
    {
        if ($this->hasRole(Statics::USER_TYPE_CUSTOMER))
            return $this->hasMany('App\Project', 'customer_id');
        else
            return $this->assignedProjects();
    }

    public function assignedProjects()
    {
        return $this->hasMany('App\Project', 'engineer_id');
    }

    public function designs()
    {
        if ($this->hasRole(Statics::USER_TYPE_CUSTOMER))
            return $this->hasManyThrough('App\SystemDesign', 'App\Project', 'customer_id');
        else
            return $this->assignedDesigns();
    }

    public function assignedDesigns()
    {
        return $this->hasManyThrough('App\SystemDesign', 'App\Project', 'engineer_id');
    }

    public function getDesigns(){
        return $this->hasMany('App\Project', 'App\SystemDesign');
    }
}
