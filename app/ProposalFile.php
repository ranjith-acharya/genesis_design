<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProposalFile extends Model
{
    protected $fillable = ['type', 'path', 'proposal_id', 'content_type'];
}
