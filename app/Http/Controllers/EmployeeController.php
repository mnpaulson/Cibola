<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;

class EmployeeController extends Controller
{

    public function index()
    {
        $employee = Employee::all();
        return response()->json($employee);
    }

    public function active()
    {
        $employee = Employee::where('active', 1)->get();
        return response()->json($employee);
    }

    public function create(Request $request) 
    {
        $employee = new Employee;

        $employee->name = $request->name;
        $employee->active = true;

        $employee->save();
        return response()->json($employee->id);
    }

    public function update(Request $request) 
    {
        $employee = new Employee;

        $employee = \App\Employee::where('id', $request->id)->first();

        $employee->name = $request->name;
        $employee->active = $request->active;

        $employee->save();
        return response()->json($employee->id);
    }

    public function delete(Request $request) 
    {
        \App\Employee::destroy($request->id);
        echo response()->json($request->id);
    }

}
