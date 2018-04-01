<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job_image;
use Storage;

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
        $image = \App\Job_image::where('id', $request->id)->first();
        if (file_exists(public_path() . $image->image)) unlink(public_path() . $image->image);        
        $image->delete();
        echo response()->json(public_path() . "/app" . $image->image);
    }

}
