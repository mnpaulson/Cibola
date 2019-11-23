<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estimate extends Model
{

    protected $fillable = ['note', 'name', 'isPrimary'];

    public function estimate()
    {
        return $this->belongsTo(Estimate::class);
    }
    
    public function customSheet()
    {
        return $this->belongsTo('App\CustomSheet');
    }

    public function estValues() 
    {
        return $this->hasMany('App\EstValue');
    }
}
