<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Credit_image;
use Storage;

class Goldcredit_imageController extends Controller
{

    public function index()
    {

    }

    public function create(Request $request) 
    {

    }

    public function update(Request $request) 
    {

    }

    public function delete(Request $request) 
    {
        $image = \App\Credit_image::where('id', $request->id)->first();
        if (file_exists(public_path() . $image->image)) unlink(public_path() . $image->image);        
        $image->delete();
        echo response()->json(public_path() . "/app" . $image->image);
    }

    public static function boot()
    {
        parent::boot();

        Credit_image::deleting(function ($item) {
            if (file_exists(public_path() . $item->image)) unlink(public_path() . $item->image);
            Log::debug('deleting fired');      
        });
    }

}
