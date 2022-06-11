<?php

namespace App\Models\Parametre;

use App\Models\Parametre\City;
use App\Models\Parametre\Agency;
use App\Models\Parametre\Country;
use Illuminate\Database\Eloquent\Model;

class Caisse extends Model
{
    protected $fillable = [
                            'libelle_caisse',
                            'ouverte',
                            'country_id',
                            'city_id',
                            'agency_id',
                        ];

    public function country(){
        return $this->belongsTo(Country::class,'country_id');
    }
    public function city(){
        return $this->belongsTo(City::class,'city_id');
    }
    public function agency(){
        return $this->belongsTo(Agency::class,'agency_id');
    }
}
