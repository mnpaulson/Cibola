<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use Illuminate\Support\Facades\DB;

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

    public function outstandingJobs(Request $request)
    {
        $employees = \App\Employee::with(['jobs' => function ($query) {
            $query->select('id', 'estimate', 'due_date', 'completed_at', 'employee_id', 'customer_id', 'vital_date')            
            ->whereNull('completed_at')
            ->with(['customer' => function ($query) {
                $query->select(DB::raw('id, CONCAT(fname, " ", lname) AS name'));
            }])            
            ->orderby('due_date', 'asc');
        }])
        ->select('employees.id', 'employees.name')
        ->where('active', 1)
        ->get();

        return response()->json($employees);  
    }

}
