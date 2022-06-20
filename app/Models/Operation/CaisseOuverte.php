<?php

namespace App\Models\Operation;

use App\Models\Parametre\Caisse;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CaisseOuverte extends Model
{
    protected $fillable = [
        'montant_ouverture',
        'solde_fermeture',
        'total_entree',
        'total_sortie',
        'total_remise',
        'caisse_id',
        'user_id',
        'observation',
    ];

    protected $dates = ['date_ouverture','date_fermeture'];

    public function caisse(){
        return $this->belongsTo(Caisse::class,'caisse_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
