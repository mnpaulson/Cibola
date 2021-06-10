<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomSheet;
use App\Estimate;
use App\EstValue;
use App\Custom_image;
use Storage;
use DB;
use Carbon\Carbon;
use stdClass;
use DateTime;

class CustomSheetController extends Controller
{

    public function index()
    {
        $customSheet = CustomSheet::all();
        return response()->json($customSheet);
    }

    public function create(Request $request) 
    {
        $customSheet = new CustomSheet;

        if ($request->customer_id == 0) {
            trigger_error("Customer cannot be blank");
        }

        $customSheet->customer_id = $request->customer_id;
        $customSheet->name = $request->name;
        $customSheet->note = $request->note;

        $customSheet->save();

        $est_vals = array();
        foreach ($request->estimates as $key => $estimate)
        {
            $est_vals = array();
            foreach($estimate['estValues'] as $k => $val)
            {
                $v = new EstValue([
                    'type' => $val['type'],
                    'name' => $val['name'],
                    'amt' => $val['amt'],
                    'basePrice' => $val['basePrice'],
                    'priceType' => $val['priceType'],
                    'markup' => $val['markup'],
                    'discount' => $val['discount'],
                    'priceModifier' => $val['priceModifier']
                ]);
                array_push($est_vals, $v);
            }


            $e = new Estimate([
                'name' => $estimate['name'],
                'note' => $estimate['note'],
                'isPrimary' => $estimate['isPrimary']
            ]);
            
            $customSheet->estimates()->save($e);
            $e->EstValues()->saveMany($est_vals);
        }

        $customSheet->estimatesWithValues;

        $images = $request->custom_images;
        $image_ids = array();
        
        // Save images
        if (sizeof($images))
        {     
        $nextId = DB::table('custom_images')->max('id') + 1;        
            foreach ($images as $key => $image) 
            {
                //Get proper file stream
                $image["image"] = substr($image["image"], strpos($image["image"], ",")+1);
                
                //set filename
                $filename = "public/custom" . $customSheet->id . "-" . $nextId++ . ".png";

                //Write image to disk
                Storage::disk('local')->put($filename, base64_decode($image["image"]));

                //Get file url
                $url = Storage::url($filename);

                //Prepare custom_image object
                $custom_image = new Custom_image(['image' => $url, 'note' => $image["note"]]);

                //Save custom_image to DB
                $customSheet->custom_images()->save($custom_image);
                array_push($image_ids, $custom_image->id);            

            }
        }      

        $customSheet->estimatesWithValues;
        $customSheet->custom_images;        
        return response()->json($customSheet);
        // return response()->json(['customSheet' => $customSheet, 'image_ids' => $image_ids]);
    }

    public function update(Request $request) 
    {
        $customSheet = new CustomSheet;

        //check customer id
        if ($request->customer_id == 0) {
            trigger_error("Customer cannot be blank");
        }

        //update custom sheet
        if (isset($request->customSheet_id) && $request->customSheet_id !== 0)
        {
            $customSheet = CustomSheet::where('id', $request->customSheet_id)->first();
            $customSheet->name = $request->name;
            $customSheet->note = $request->note;
            $customSheet->save();
        } 
        else
        {
            return "No Custom sheet found to update";
        }

        foreach ($request->estimatesToDelete as $delEst)
        {
            $e = Estimate::where('id', $delEst)->first()->delete();
            $v = EstValue::where('estimate_id', $delEst)->delete();

        }

        $newEstimates = array();

        foreach ($request->estimates as $key => $estimate)
        {
            $newEstVals = array();
            //Update Estimates
            if (isset($estimate['id']))
            {

                $estimateToUpdate = estimate::where('id', $estimate['id'])->first();

                $estimateToUpdate->name = $estimate['name'];
                $estimateToUpdate->note = $estimate['note'];
                $estimateToUpdate->isPrimary = $estimate['isPrimary'];
                
                foreach ($estimate['estValuesToDelete'] as $es)
                {
                    $v = EstValue::where('id', $es)->delete();
                }

                //Est values loop
                foreach ($estimate['estValues'] as $k => $val)
                {
                    //Update est values
                    if (isset($val['id']))
                    {


                        DB::table('est_values')
                        ->where('id', $val['id'])
                        ->update([
                            'type' => $val['type'],
                            'name' => $val['name'],
                            'amt' => $val['amt'],
                            'basePrice' => $val['basePrice'],
                            'priceType' => $val['priceType'],
                            'markup' => $val['markup'],
                            'discount' => $val['discount'],
                            'priceModifier' => $val['priceModifier']
                        ]);


                    }
                    //new Est values
                    else 
                    {
                        $v = new EstValue([
                            'type' => $val['type'],
                            'name' => $val['name'],
                            'amt' => $val['amt'],
                            'basePrice' => $val['basePrice'],
                            'priceType' => $val['priceType'],
                            'markup' => $val['markup'],
                            'discount' => $val['discount'],
                            'priceModifier' => $val['priceModifier']
                        ]);

                        array_push($newEstVals, $v);
                    }

                    foreach ($estimate['estValuesToDelete'] as $k => $val)
                    {
                        DB::table('est_values')
                        ->where('id', $val)
                        ->delete();
                    }

                    $estimateToUpdate->EstValues()->saveMany($newEstVals);
                    $estimateToUpdate->save();
                }
                $estimateToUpdate->EstValues()->saveMany($newEstVals);
                $estimateToUpdate->save();
            }
            //New Estimate
            else
            {
                $newEstVals = array();

                $e = new Estimate([
                    'name' => $estimate['name'],
                    'note' => $estimate['note'],
                    'isPrimary' => $estimate['isPrimary']
                ]);

                foreach($estimate['estValues'] as $k => $val)
                {
                    $v = new EstValue([
                        'type' => $val['type'],
                        'name' => $val['name'],
                        'amt' => $val['amt'],
                        'basePrice' => $val['basePrice'],
                        'priceType' => $val['priceType'],
                        'markup' => $val['markup'],
                        'discount' => $val['discount'],
                        'priceModifier' => $val['priceModifier']
                    ]);
                    array_push($newEstVals, $v);
                }

                array_push($newEstimates, $newEstVals);

                $customSheet->estimates()->save($e);
                $e->EstValues()->saveMany($newEstVals);
            }
        }


        foreach ($request->estimatesToDelete as $etd => $val)
        {
            DB::table('estimates')
            ->where('id', $val)
            ->delete();
        }

        $images = $request->custom_images;
        $image_ids = array();
        
        // Save images
        if (sizeof($images))
        {
            $nextId = DB::table('custom_images')->max('id') + 1;                
            foreach ($images as $key => $image) 
            {

                if (!is_null($image["id"])) 
                {
                    $custom_image = new Custom_image;
                    $custom_image = Custom_image::where('id', $image["id"])->first();
                    if (is_null($image["note"])) $image["note"] = "";
                    $custom_image->note = $image["note"];
        
                } 
                else 
                {

                    //Get proper file stream
                    $image["image"] = substr($image["image"], strpos($image["image"], ",")+1);
                    
                    //set filename
                    $filename = "public/custom" . $customSheet->id . "-" . $nextId++ . ".png";

                    while (file_exists(public_path() . $filename)) {
                        $filename = "public/custom" . $customSheet->id . "-" . $nextId++ . ".png";                    
                    }

                    //Write image to disk
                    Storage::disk('local')->put($filename, base64_decode($image["image"]));

                    //Get file url
                    $url = Storage::url($filename);

                    //Prepare Custom_image object
                    $custom_image = new Custom_image(['image' => $url, 'note' => $image["note"]]);

                }

                //Save Custom_image to DB
                $customSheet->custom_images()->save($custom_image);
                array_push($image_ids, $custom_image->id);

            }
        }       

        $customSheet->estimatesWithValues;
        $customSheet->custom_images;        
        return response()->json($customSheet);
    }

    public function delete(Request $request) 
    {
        $customSheet = \App\CustomSheet::where('id', $request->id)->first();
        
        $customSheet->estValues()->delete();
        $customSheet->estimates()->delete();
        $images = $customSheet->custom_images;
        
        // Save images
        if (sizeof($images))
        {
            $nextId = DB::table('custom_images')->max('id') + 1;                
            foreach ($images as $key => $image) 
            {
             if (file_exists(public_path() . $image->image)) unlink(public_path() . $image->image);
            }
        }
        \App\Custom_image::where('custom_sheet_id', $request->id)->delete();     
            
        $customSheet->delete();
    }

    public function show(Request $request)
    {
        $customSheet = \App\CustomSheet::where('id', $request->id)->first();
        $customSheet->estimatesWithValues;
        $customSheet->custom_images;        
        return response()->json($customSheet);
    }

    public function customerCustomSheets(Request $request)
    {
        $customSheets = \App\CustomSheet::where('customer_id', $request->id)->get();
        return response()->json($customSheets);
    }

    public function allCustomSheetDetails(Request $request)
    {
        $order = "";
        if ($request->descending) $desc = 'desc';
        else $desc = 'asc';
        $customSheet = \App\CustomSheet::with('customer')
        ->orderBy($request->sortBy, $desc)        
        ->paginate($request->rowsPerPage);   
        // $customSheet->estimatesWithValues;

        return response()->json($customSheet);  
    }

}
