<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomSheet extends Model
{

    protected $fillable = ['note', 'name'];

    public function customSheet()
    {
        return $this->belongsTo(CustomSheet::class);
    }

    public function employee()
    {
        return $this->belongsTo('App\Employee');
    }

    public function estimates() 
    {
        return $this->hasMany('App\Estimate');
    }

    public function estValues()
    {
        return $this->hasManyThrough('App\EstValue', 'App\Estimate');
    }

    public function estimatesWithValues()
    {
        return $this->estimates()->with('EstValues');
    }
}
