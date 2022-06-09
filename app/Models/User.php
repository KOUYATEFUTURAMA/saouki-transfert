<?php

namespace App\Models;

use App\Models\Parametre\Agency;
use App\Models\Parametre\City;
use App\Models\Parametre\Country;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
                            'name',
                            'email',
                            'contact',
                            'role',
                            'last_login_ip',
                            'last_login_at',
                            'confirmation_token',
                            'statut_compte',
                            'etat_user',
                            'password',
                            'agency_id',
                            'country_id',
                            'city_id',
                        ];

    public function agency() {
        return $this->belongsTo(Agency::class,'agency_id');
    }
    public function country() {
        return $this->belongsTo(Country::class,'country_id');
    }
    public function city() {
        return $this->belongsTo(City::class,'city_id');
    }

    protected $dates = [
        'last_login_at'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
