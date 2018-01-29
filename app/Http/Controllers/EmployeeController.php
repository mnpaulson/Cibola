<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{

    public function index()
    {
        $Employee = Employee::all();
        return response()->json($Employee);
    }

    public function create(Request $request) 
    {

    }

    public function update(Request $request) 
    {

    }

    public function delete(Request $request) 
    {

    }

    public function active(Request $request) 
    {

    }

}
