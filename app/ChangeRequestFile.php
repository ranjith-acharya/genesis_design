<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChangeRequestFile extends Model
{
    protected $fillable = ['path', 'change_request_id', 'content_type'];
}
