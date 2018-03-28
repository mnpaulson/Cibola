<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job_image;

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
        \App\Job_image::destroy($request->id);
        echo response()->json($request->id);
    }

}
