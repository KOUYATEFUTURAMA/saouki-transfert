<?php

namespace App\Models\Operation;

use App\Models\User;
use App\Models\Parametre\Country;
use App\Models\Parametre\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SendMoney extends Model
{
    use SoftDeletes;

    protected $table = 'send_money';

    protected $fillable = [
        'secret_code',
        'state',
        'sender_id',
        'recipient_id',
        'sending_country_id',
        'destination_country_id',
        'amount',
        'shipping_cost',
        'discount_on_shipping_costs',
        'shipping_costs_included',
        'to_delete',
        'deleted_by',
        'updated_by',
        'created_by',
    ];

    protected $dates = ['send_date','deleted_at'];

    public function sender(){
        return $this->belongsTo(Customer::class,'sender_id');
    }

    public function recipient(){
        return $this->belongsTo(Customer::class,'recipient_id');
    }

    public function sending_country(){
        return $this->belongsTo(Country::class,'sending_country_id');
    }

    public function destination_country(){
        return $this->belongsTo(Country::class,'destination_country_id');
    }

    public function deleted_by(){
        return $this->belongsTo(User::class,'deleted_by');
    }

    public function updated_by(){
        return $this->belongsTo(User::class,'updated_by');
    }

    public function created_by(){
        return $this->belongsTo(User::class,'created_by');
    }
}
