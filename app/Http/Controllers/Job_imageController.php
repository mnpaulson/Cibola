<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job_image;
use Storage;
use DB;

class Job_imageController extends Controller
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
        // trigger_error("actually here!");
        $image = \App\Job_image::where('id', $request->id)->first();
        if (file_exists(public_path() . $image->image)) {
            unlink(public_path() . $image->image);
            $image->delete();
            return "deleting " . $image->image;
        } else {
            return "Image not found";
        }
        echo response()->json(public_path() . "/app" . $image->image);
    }

    public static function boot()
    {
        parent::boot();

        Job_image::deleting(function ($item) {
            if (file_exists(public_path() . $item->image)) unlink(public_path() . $item->image);
            Log::debug('deleting fired');      
        });
    }

}
