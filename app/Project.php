<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = [];
    
    public function scopeOwned($query)
    {
        return $query->whereUserId(auth()->id());
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
