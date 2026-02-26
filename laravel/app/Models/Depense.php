<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Depense extends Model
{
   protected $fillable = ['titre','montant','date_depense'];
}
