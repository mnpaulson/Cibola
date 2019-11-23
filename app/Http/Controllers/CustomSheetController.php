<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomSheet;
use App\Estimate;
use App\EstValue;
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

    // public function recentCustomSheetsList()
    // {
    //     $customSheet = \App\CustomSheet::with('CustomSheet_images')
    //         ->with('customer')
    //         ->orderBy('updated_at', 'desc')
    //         ->take(13)
    //         ->get();    

   

    //     return response()->json($customSheet);
    // }

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
                    'pricePer' => $val['pricePer'],
                    'priceType' => $val['priceType']
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
        return response()->json($customSheet);
    }

    public function update(Request $request) 
    {
        // $customSheet = new CustomSheet;

        // $customSheet = \App\CustomSheet::where('id', $request->id)->first();

        // if ($request->customer_id == 0) {
        //     trigger_error("Customer cannot be blank");
        // }

        // $customSheet->customer_id = $request->customer_id;
        // $customSheet->employee_id = $request->employee_id;
        // if ($request->estimate) $customSheet->estimate = str_replace(',', '', $request->estimate);
        // else $customSheet->estimate = 0;
        // $customSheet->deposit = $request->deposit;
        // $customSheet->est_note = $request->est_note;
        // $customSheet->note = $request->note;
        // $customSheet->appraisal = $request->appraisal;        
        // $customSheet->due_date = $request->due_date;
        // $customSheet->completed_at = $request->completed_at;
        // $customSheet->vital_date = $request->vital_date;        


        // $customSheet->save();

        // $images = $request->CustomSheet_images;
        // $image_ids = array();
        
        // // Save images
        // if (sizeof($images))
        // {
        //     $nextId = DB::table('CustomSheet_images')->max('id') + 1;                
        //     foreach ($images as $key => $image) 
        //     {

        //         if (!is_null($image["id"])) 
        //         {
        //             $customSheet_image = new CustomSheet_image;
        //             $customSheet_image = CustomSheet_image::where('id', $image["id"])->first();
        //             if (is_null($image["note"])) $image["note"] = "";
        //             $customSheet_image->note = $image["note"];
        
        //         } 
        //         else 
        //         {

        //             //Get proper file stream
        //             $image["image"] = substr($image["image"], strpos($image["image"], ",")+1);
                    
        //             //set filename
        //             $filename = "public/CustomSheet" . $customSheet->id . "-" . $nextId++ . ".png";

        //             while (file_exists(public_path() . $filename)) {
        //                 $filename = "public/CustomSheet" . $customSheet->id . "-" . $nextId++ . ".png";                    
        //             }

        //             //Write image to disk
        //             Storage::disk('local')->put($filename, base64_decode($image["image"]));

        //             //Get file url
        //             $url = Storage::url($filename);

        //             //Prepare CustomSheet_image object
        //             $customSheet_image = new CustomSheet_image(['image' => $url, 'note' => $image["note"]]);

        //         }

        //         //Save CustomSheet_image to DB
        //         $customSheet->CustomSheet_images()->save($customSheet_image);
        //         array_push($image_ids, $customSheet_image->id);

        //     }




        // }        

        // return response()->json(['id' => $customSheet->id, 'image_ids' => $image_ids]);
    }

    public function delete(Request $request) 
    {
        // $customSheet = \App\CustomSheet::where('id', $request->id)->first();
        
        // $images = $customSheet->CustomSheet_images;
        
        // // Save images
        // if (sizeof($images))
        // {
        //     $nextId = DB::table('CustomSheet_images')->max('id') + 1;                
        //     foreach ($images as $key => $image) 
        //     {
        //      if (file_exists(public_path() . $image->image)) unlink(public_path() . $image->image);
        //     }
        // }
        // \App\CustomSheet_image::where('CustomSheet_id', $request->id)->delete();       
        // $customSheet->delete();        
    }

    public function show(Request $request)
    {
        $customSheet = \App\CustomSheet::where('id', $request->id)->first();
        // $customSheet->estimates;
        $customSheet->estimatesWithValues;        
        return response()->json($customSheet);
    }

    public function customerCustomSheets(Request $request)
    {
        $customSheets = \App\CustomSheet::where('customer_id', $request->id)->get();
        return response()->json($customSheets);
    }


}
