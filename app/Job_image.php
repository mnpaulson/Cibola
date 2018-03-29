<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job_image extends Model
{
    protected $fillable = ['image', 'note'];

    public function job_image()
    {
        return $this->belongsTo(Job_image::class);
    }

    public function job()
    {
        return $this->belongsTo('App\Job');
    }
}
