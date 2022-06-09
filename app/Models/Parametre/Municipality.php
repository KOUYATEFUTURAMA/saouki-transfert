<?php

namespace App\Models\Parametre;

use App\Models\Parametre\City;
use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    protected $table = 'municipalities';

    protected $fillable = ['libelle_municipality','city_id'];

    public function city(){
        return $this->belongsTo(City::class,'city_id');
    }
}
