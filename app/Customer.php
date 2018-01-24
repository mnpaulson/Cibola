<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['fname', 'lname', 'phone', 'email', 'address'];
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
