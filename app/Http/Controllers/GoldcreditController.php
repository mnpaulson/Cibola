<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Goldcredit;
use App\CreditItem;
use Illuminate\Support\Facades\DB;

class GoldcreditController extends Controller
{

    public function index()
    {
        $credit = Goldcredit::all();
        return response()->json($credit);
    }

    public function create(Request $request) 
    {
        $credit = new Goldcredit;

        if ($request->customer_id == 0) {
            trigger_error("Customer cannot be blank");
        }

        $credit->customer_id = $request->customer_id;
        $credit->employee_id = $request->employee_id;
        $credit->gold_cad = $request->goldCAD;
        $credit->plat_cad = $request->platCAD;
        $credit->gold_date = $request->metalPriceDate;
        $credit->note = $request->note;
        $credit->used = $request->used;

        // $credit->markup = $request->markup;
        // $credit->itemId = $request->itemId;
        // $credit->multiplier = $request->multiplier;
        // $credit->value = $request->value;
        // $credit->weight = $request->weight;

        $credit->save();
        
        $creditItems = $request->creditItems;
        $creditItem_ids = array();
        
        // Save credit items WIP
        if (sizeof($creditItems))
        {     
            foreach ($creditItems as $key =>$creditItem) 
            {
                //Prepare CreditItem object
                $CreditItem = new CreditItem([
                    'markup' => $creditItem["markup"],
                    'itemId' => $creditItem["id"],
                    'multiplier' => $creditItem["multiplier"],
                    'value' => $creditItem["value"],
                    'weight' => $creditItem["weight"]
                ]);

                //Save CreditItem to DB
                $credit->credit_items()->save($CreditItem);
                array_push($creditItem_ids, $CreditItem->id);            

            }
        }
        
        return response()->json(['id' => $credit->id, 'item_ids' => $creditItem_ids]);
        
    }

    public function update(Request $request) 
    {
        $credit = new Goldcredit;

        $credit = \App\Goldcredit::where('id', $request->id)->first();

        $credit->name = $request->name;
        $credit->value1 = $request->value1;
        $credit->value2 = $request->value2;
        $credit->value3 = $request->value3;
        $credit->active = $request->active;

        $credit->save();
        return response()->json($credit->id);
    }

    // Deletes Value and reassigns all jobs to Value 1
    public function delete(Request $request) 
    {

        $emps = \App\Value::where('id', $request->id)->first();

        \App\Value::destroy($request->id);
        
        echo response()->json($request->id);
    }

}
