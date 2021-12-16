<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DesignFile extends Model
{
    protected $fillable = ['path', 'system_design_id', 'content_type'];
}
