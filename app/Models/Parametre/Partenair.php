<?php

namespace App\Models\Parametre;

use Illuminate\Database\Eloquent\Model;

class Partenair extends Model
{
    protected $fillable = [
                            'name',
                            'contact',
                            'country_id', 
                            'adress'
                        ];

    public function country(){
        return $this->belongsTo(Country::class,'country_id');
    }
}
