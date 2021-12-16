<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectFile extends Model
{
    protected $fillable = ['file_type_id', 'path', 'project_id', 'content_type'];

    protected $casts = [
        'created_at' => 'datetime:F dS Y - h:i A',
    ];

    public function type(){
        return $this->belongsTo('App\FileType', 'file_type_id');
    }
}
