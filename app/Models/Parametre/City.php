<?php

namespace App\Models\Parametre;

use App\Models\Parametre\Country;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'cities';

    protected $fillable = ['libelle_city','country_id'];

    public function country(){
        return $this->belongsTo(Country::class,'country_id');
    }
}
