<?php

namespace App\Models\Parametre;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';

    protected $fillable = ['libelle_country'];
}
