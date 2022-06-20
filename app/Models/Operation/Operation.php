<?php

namespace App\Models\Operation;

use App\Models\Parametre\Bank;
use App\Models\Parametre\City;
use App\Models\Parametre\Partenair;
use App\Models\Operation\CaisseOuverte;
use App\Models\Parametre\Agency;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $fillable = [
        'reference',
        'operation_type',
        'amount',
        'state',
        'partenair_id',
        'receptionist',
        'id_card_receptionist',
        'bank_id',
        'city_id',
        'other_caisse_id',
        'agency_id',
        'operation_id',
        'caisse_ouverte_id',
        'send_money_id',
        'withdrawal_money_id',
        'user_id',
        'authorized_by',
        'observation',
        'pattern',
        'file_to_upload'
    ];

    protected $dates = ['date','authorization_date'];

    public function partenair(){
        return $this->belongsTo(Partenair::class,'partenair_id');
    }

    public function bank(){
        return $this->belongsTo(Bank::class,'bank_id');
    }

    public function city(){
        return $this->belongsTo(City::class,'city_id');
    }

    public function agency(){
        return $this->belongsTo(Agency::class,'agency_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function authorized_by(){
        return $this->belongsTo(User::class,'user_id');
    }
}
