<?php

namespace App\Models\Parametre;

use Illuminate\Database\Eloquent\Model;

class TauxTransfert extends Model
{
    protected $fillable = [
                            'montant_minimum',
                            'montant_maximum',
                            'montant_fixe',
                            'taux',
                        ];
}
