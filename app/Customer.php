<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['fname', 'lname', 'phone', 'email', 'addr_st', 'addr_city', 'addr_prov', 'addr_postal', 'addr_country'];
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function jobs()
    {
        return $this->hasMany('App\Job');
    }
}
