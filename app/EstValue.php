<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstValue extends Model
{
    protected $fillable = ['name', 'type', 'priceType', 'amt', 'pricePer'];

    public function estValue()
    {
        return $this->belongsTo(EstValue::class);
    }

    public function estimate()
    {
        return $this->belongsTo('App\Estimate');
    }
}
