<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    //
    protected $fillable = [
        'provice',
        'city',
        'district',
        'address',
        'zip',
        'contract_name',
        'contract_phone',
        'last_used_at',
    ];
    protected $dates=['last_used_at'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getFullAddressAttribute(){
        return "{$this->province}{$this->city}{$this->district}{$this->address}";
    }
}
