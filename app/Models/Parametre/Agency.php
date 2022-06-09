<?php

namespace App\Models\Parametre;

use App\Models\Parametre\City;
use App\Models\Parametre\Country;
use App\Models\Parametre\Municipality;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    protected $table = 'agencies';

    protected $fillable = [
                            'libelle_agency',
                            'phone_agency',
                            'country_id',
                            'city_id',
                            'municipality_id',
                            'adress_agency',
                        ];

    public function country(){
        return $this->belongsTo(Country::class,'country_id');
    }
    public function city(){
        return $this->belongsTo(City::class,'city_id');
    }
    public function municipality(){
        return $this->belongsTo(Municipality::class,'municipality_id');
    }
}
