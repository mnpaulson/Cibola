<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Goldcredit;
use App\CreditItem;
use App\Credit_image;
// use Illuminate\Support\Facades\DB;
use Storage;
use DB;

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

        $credit->save();
        
        //Save Items
        $creditItems = $request->credit_items;
        $creditItem_ids = array();
        
        if (sizeof($creditItems))
        {     
            foreach ($creditItems as $key =>$creditItem) 
            {
                //Prepare CreditItem object
                $CreditItem = new CreditItem([
                    'markup' => $creditItem["markup"],
                    'itemId' => $creditItem["item"],
                    'multiplier' => $creditItem["multiplier"],
                    'value' => $creditItem["value"],
                    'weight' => $creditItem["weight"]
                ]);

                //Save CreditItem to DB
                $credit->credit_items()->save($CreditItem);
                array_push($creditItem_ids, $CreditItem->id);            

            }
        }

        //Save Images
        $images = $request->credit_images;
        $image_ids = array();
        
        // Save images
        if (sizeof($images))
        {
            $nextId = DB::table('credit_images')->max('id') + 1;                
            foreach ($images as $key => $image) 
            {

                if (!is_null($image["id"])) 
                {
                    $credit_image = new Credit_image;
                    $credit_image = Credit_image::where('id', $image["id"])->first();
                    if (is_null($image["note"])) $image["note"] = "";
                    $credit_image->note = $image["note"];
        
                } 
                else 
                {

                    //Get proper file stream
                    $image["image"] = substr($image["image"], strpos($image["image"], ",")+1);
                    
                    //set filename
                    $filename = "public/credit" . $credit->id . "-" . $nextId++ . ".png";

                    while (file_exists(public_path() . $filename)) {
                        $filename = "public/credit" . $credit->id . "-" . $nextId++ . ".png";                    
                    }

                    //Write image to disk
                    Storage::disk('local')->put($filename, base64_decode($image["image"]));

                    //Get file url
                    $url = Storage::url($filename);

                    //Prepare Credit_image object
                    $credit_image = new Credit_image(['image' => $url, 'note' => $image["note"]]);

                }

                //Save Credit_image to DB
                $credit->credit_images()->save($credit_image);
                array_push($image_ids, $credit_image->id);

            }

        }  
        
        return response()->json(['id' => $credit->id, 'item_ids' => $creditItem_ids, 'image_ids' => $image_ids]);
        
    }

    public function update(Request $request) 
    {
        $credit = new Goldcredit;

        $credit = \App\Goldcredit::where('id', $request->id)->first();

        $credit->note = $request->note;
        $credit->used = $request->used;

        $credit->save();
        return response()->json($credit->id);
    }

    // Deletes Value and reassigns all jobs to Value 1
    public function delete(Request $request) 
    {
        $credit = \App\Goldcredit::where('id', $request->id)->first();
        
        $images = $credit->credit_images;
        
        // Save images
        if (sizeof($images))
        {
            foreach ($images as $key => $image) 
            {
             if (file_exists(public_path() . $image->image)) unlink(public_path() . $image->image);
            }
        }
        \App\credit_image::where('goldcredit_id', $request->id)->delete();
        \App\creditItem::where('goldcredit_id', $request->id)->delete();       
        $credit->delete(); 
    }

    public function show(Request $request)
    {
        $credit = \App\Goldcredit::where('id', $request->id)->first();
        $credit->credit_images;
        $credit->credit_items;        
        return response()->json($credit);
    }

    public function allCreditsDetails(Request $request)
    {
        $order = "";
        if ($request->descending) $desc = 'desc';
        else $desc = 'asc';
        $credit = \App\Goldcredit::with('customer')
        ->with('employee')
        ->orderBy($request->sortBy, $desc)        
        ->paginate($request->rowsPerPage);   

        return response()->json($credit);  
    }
    
    public function customerCredits(Request $request) 
    {
        $credits = \App\Goldcredit::where('customer_id', $request->id)->get();
        return response()->json($credits);
    }

}
