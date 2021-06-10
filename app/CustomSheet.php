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

    public function customer()
    {
        return $this->belongsTo('App\Customer');
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

    public function custom_images() 
    {
        return $this->hasMany('App\Custom_image');
    }
}
