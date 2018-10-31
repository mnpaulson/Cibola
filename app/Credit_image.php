<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credit_image extends Model
{
    protected $fillable = ['image', 'note'];

    public function credit_image()
    {
        return $this->belongsTo(Credit_image::class);
    }

    public function credit()
    {
        return $this->belongsTo('App\Goldcredit');
    }
}
