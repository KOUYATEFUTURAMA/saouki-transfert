<?php

namespace App\Models\Operation;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;


class WithdrawalMoney extends Model
{
    protected $table = 'withdrawal_money';

    protected $fillable = [
        'send_money_id',
        'id_card_recipient',
        'id_recipient',
        'amount',
        'created_by',
    ];

    protected $dates = ['withdrawal_date'];

    public function created_by(){
        return $this->belongsTo(User::class,'created_by');
    }
}
