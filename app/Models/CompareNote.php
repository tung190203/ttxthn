<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompareNote extends Model
{
    protected $fillable = ['session_id', 'project_id', 'criterion_key', 'note'];

    public function session()
    {
        return $this->belongsTo(CompareSession::class, 'session_id');
    }
}
