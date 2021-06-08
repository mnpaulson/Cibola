<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    protected $fillable = ['type_id', 'name', 'value1', 'value2', 'value3', 'discount', 'markup', 'order', 'default', 'active'];

}
