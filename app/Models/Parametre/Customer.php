<?php

namespace App\Models\Parametre;

use App\Models\Parametre\Country;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name','surname','contact', 'country_id', 'adress'];

    public function country(){
        return $this->belongsTo(Country::class,'country_id');
    }
}
