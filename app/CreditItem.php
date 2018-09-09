<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditItem extends Model
{
    protected $fillable = ['itemId', 'markup', 'multiplier', 'value', 'weight'];

    public function credit_item()
    {
        return $this->belongsTo(CreditItem::class);
    }

    public function Goldcredit()
    {
        return $this->belongsTo('App\Goldcredit');
    }
}
