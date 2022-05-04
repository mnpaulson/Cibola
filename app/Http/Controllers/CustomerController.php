<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Customer::all();
        return response()->json($customers);
    }

    public function searchList()
    {
        $customers = Customer::all(['fname', 'lname', 'id', 'phone']);
        return response()->json($customers);
    }

    public function recentCustomerList()
    {
        $customer = \App\Customer::orderBy('updated_at', 'desc')
            ->take(10)
            ->get();
        
        return response()->json($customer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $customer = new Customer;

        $customer->fname = $request->fname;
        $customer->lname = $request->lname;
        $customer->phone = $request->phone;
        $customer->email = $request->email;
        $customer->addr_st = $request->addr_st;
        $customer->addr_city = $request->addr_city;
        $customer->addr_prov = $request->addr_prov;
        $customer->addr_postal = $request->addr_postal;
        $customer->addr_country = $request->addr_country;
        $customer->note = $request->note;
        $customer->noteVisibility = $request->noteVisibility;

        $customer->save();
        return response()->json($customer->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $customer = \App\Customer::where('id', $request->id)->get();
        return response()->json($customer);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $customer = new Customer;

        $customer = \App\Customer::where('id', $request->id)->first();

        $customer->fname = $request->fname;
        $customer->lname = $request->lname;
        $customer->phone = $request->phone;
        $customer->email = $request->email;
        $customer->addr_st = $request->addr_st;
        $customer->addr_city = $request->addr_city;
        $customer->addr_prov = $request->addr_prov;
        $customer->addr_postal = $request->addr_postal;
        $customer->addr_country = $request->addr_country;
        $customer->note = $request->note;
        $customer->noteVisibility = $request->noteVisibility;

        $customer->save();
        return response()->json($customer->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {

        $cus = \App\Customer::where('id', $request->id)->first();
        
        $cus->jobs()
        ->delete();
        
        $cus->destroy($request->id);
        // \App\customer::destroy($request->id);
        echo response()->json($request->id);
    }
}
