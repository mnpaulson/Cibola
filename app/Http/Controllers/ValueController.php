<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Value;
use Illuminate\Support\Facades\DB;

class ValueController extends Controller
{

    public function index()
    {
        $value = Value::all();
        return response()->json($value);
    }

    public function active()
    {
        $value = Value::where('active', 1)->get();
        return response()->json($value);
    }

    public function getType(Request $request)
    {
        $value = Value::where('type_id', $request->type_id)->get();
        return response()->json($value);
    }

    public function create(Request $request) 
    {
        $value = new Value;

        $value->name = $request->name;
        $value->name = $request->type_id;
        $value->name = $request->value;
        $value->active = true;

        $value->save();
        return response()->json($value->id);
    }

    public function update(Request $request) 
    {
        $value = new Value;

        $value = \App\Value::where('id', $request->id)->first();

        $value->name = $request->name;
        $value->value = $request->value;
        $value->active = $request->active;

        $value->save();
        return response()->json($value->id);
    }

    // Deletes Value and reassigns all jobs to Value 1
    public function delete(Request $request) 
    {

        $emps = \App\Value::where('id', $request->id)->first();

        \App\Value::destroy($request->id);
        
        echo response()->json($request->id);
    }

}
