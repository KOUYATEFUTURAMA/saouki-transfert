<?php

namespace App\Models\Parametre;

use App\Models\Parametre\Country;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{

    protected $fillable = [
                            'libelle_bank',
                            'contact',
                            'country_id',
                            'email',
                            'adress'
                        ];

    public function country(){
        return $this->belongsTo(Country::class,'country_id');
    }
}
