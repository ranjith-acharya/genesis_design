<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use PHPUnit\Util\Json;

class ProjectType extends Model
{
    public function fileCategories(){
        return $this->belongsToMany('App\FileType')->withPivot('is_required');
    }
}
