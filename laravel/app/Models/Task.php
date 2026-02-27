<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['colocation_id', 'user_id', 'name', 'frequency', 'points', 'last_done_at'];

    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
