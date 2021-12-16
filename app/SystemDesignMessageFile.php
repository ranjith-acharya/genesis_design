<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SystemDesignMessageFile extends Model
{
    protected $fillable = ['content_type', 'system_design_message_id', 'path'];
}
