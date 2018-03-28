<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = ['name', 'active'];
    
    public function Employee() 
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function jobs()
    {
        return $this->hasMany('App\Job');
    }
}
