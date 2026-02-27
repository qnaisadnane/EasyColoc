<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingItem extends Model
{
    protected $fillable = ['colocation_id', 'user_id', 'name', 'quantity', 'is_bought'];

    public function colocation()
    {
        return $this->belongsTo(Colocation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
