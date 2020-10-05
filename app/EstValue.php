<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstValue extends Model
{
    protected $fillable = ['name', 'type', 'priceType', 'amt', 'basePrice', 'markup', 'discount', 'priceModifier'];

    public function estValue()
    {
        return $this->belongsTo(EstValue::class);
    }

    public function estimate()
    {
        return $this->belongsTo('App\Estimate');
    }
}
