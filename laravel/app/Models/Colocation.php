<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colocation extends Model
{
    protected $fillable = ['name', 'status'];

    public function members()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('role', 'left_at')
            ->withTimestamps();
    }

    public function owner()
    {
        return $this->members()->wherePivot('role', 'owner');
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
