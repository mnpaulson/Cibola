<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Custom_image extends Model
{
    protected $fillable = ['image', 'note'];

    public function custom_image()
    {
        return $this->belongsTo(Custom_image::class);
    }

    public function custom()
    {
        return $this->belongsTo('App\CustomSheet');
    }
}
