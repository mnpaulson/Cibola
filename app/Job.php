<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = ['estimate', 'est_note', 'appraisal', 'due_date', 'completed_at', 'note', 'vital_date'];
    
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function employee()
    {
        return $this->belongsTo('App\Employee');
    }

    public function job_images() 
    {
        return $this->hasMany('App\Job_image');
    }
}
