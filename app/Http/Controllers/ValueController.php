<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Value;
use Illuminate\Support\Facades\DB;
use Goutte;

class ValueController extends Controller
{

    public function index()
    {
        // $value = Value::all();
        $value = Value::orderBy('name')->get();
        return response()->json($value);
    }

    public function active()
    {
        $value = Value::where('active', 1)->get();
        return response()->json($value);
    }

    public function getType(Request $request)
    {
        $order;
        if ($request->type_id == 4) $order = 'order';
        else $order = 'value2';
        $value = Value::where('type_id', $request->type_id)->where('active', 1)->orderBy($order, 'asc')->get();
        return response()->json($value);
    }

    public function create(Request $request) 
    {
        $value = new Value;

        $value->name = $request->name;
        $value->type_id = $request->type_id;
        $value->value1 = $request->value1;
        $value->value2 = $request->value2;
        $value->value3 = $request->value3;
        $value->discount = $request->discount;
        $value->markup = $request->markup;
        $value->default = $request->default;
        $value->order = $request->order;
        $value->active = $request->active;

        $value->save();
        return response()->json($value->id);
    }

    public function update(Request $request) 
    {
        $value = new Value;

        $value = \App\Value::where('id', $request->id)->first();

        $value->name = $request->name;
        $value->value1 = $request->value1;
        $value->value2 = $request->value2;
        $value->value3 = $request->value3;
        $value->discount = $request->discount;
        $value->markup = $request->markup;
        $value->default = $request->default;
        $value->order = $request->order;
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

    //Gets value of gold from third party service
    public function getGoldValue()
    {
        $r = file_get_contents("https://data-asg.goldprice.org/dbXRates/USD,CAD");

        $response = json_decode($r);
        $CAD = $response->items[0]->xauPrice;
        $USD = $response->items[1]->xauPrice;
        $exchange = $CAD / $USD;
        $CAD = $CAD / 31.1;
        $data = [$CAD, $exchange];

        $value = \App\Value::where('name', 'GoldCAD')->first();
        $value->value1 = $CAD;
        $value->save();

        echo json_encode($data);
        // echo $response->items[0]->xauPrice;

    }

    public function getPlatValue()
    {
        $text = [];
        //Uncomment to return to ampex source (gets USD Price)
        // $crawler = Goutte::request('GET', 'https://www.apmex.com/spotprices/platinum-price');
        // $text = $crawler->filter('.current')->each(function ($node, $text) {
        //     return $node->text();
        // });
        // $plat = substr($text[2], 1);
        // $plat = $plat / 31.1;
        $r = file_get_contents('https://www.goldbroker.com/api/spot-prices?metal=XPT&currency=CAD&boundaries=1');
        $response = json_decode($r);
        $length = count($response->_embedded->spot_prices);
        $plat = $response->_embedded->spot_prices[$length - 2]->value;
        $plat = $plat / 31.1;

        $value = \App\Value::where('name', 'PlatCAD')->first();
        $value->value1 = $plat;
        $value->save();

        echo $plat;
    }

}
