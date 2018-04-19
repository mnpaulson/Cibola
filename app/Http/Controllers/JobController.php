<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job;
use App\Job_image;
use Storage;
use DB;

class JobController extends Controller
{

    public function index()
    {
        $job = Job::all();
        return response()->json($job);
    }

    public function recentJobsList()
    {
        $job = \App\Job::with('job_images')
            ->with('customer')
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();    

   

        return response()->json($job);
    }

    public function create(Request $request) 
    {
        $job = new Job;

        $job->customer_id = $request->customer_id;
        $job->employee_id = $request->employee_id;
        $job->estimate = $request->estimate;
        $job->est_note = $request->est_note;
        $job->note = $request->note;
        $job->appraisal = $request->appraisal;        
        $job->due_date = $request->due_date;
        $job->completed_at = $request->completed_at;
        $job->vital_date = $request->vital_date;  


        $job->save();

        $images = $request->job_images;
        $image_ids = array();
        
        // Save images
        if (sizeof($images))
        {     
        $nextId = DB::table('job_images')->max('id') + 1;        
            foreach ($images as $key => $image) 
            {
                //Get proper file stream
                $image["image"] = substr($image["image"], strpos($image["image"], ",")+1);
                
                //set filename
                $filename = "public/job" . $job->id . "-" . $nextId++ . ".png";

                //Write image to disk
                Storage::disk('local')->put($filename, base64_decode($image["image"]));

                //Get file url
                $url = Storage::url($filename);

                //Prepare job_image object
                $job_image = new Job_image(['image' => $url, 'note' => $image["note"]]);

                //Save job_image to DB
                $job->job_images()->save($job_image);
                array_push($image_ids, $job_image->id);            

            }
        }      

        return response()->json(['id' => $job->id, 'image_ids' => $image_ids]);
    }

    public function update(Request $request) 
    {
        $job = new Job;

        $job = \App\Job::where('id', $request->id)->first();

        $job->customer_id = $request->customer_id;
        $job->employee_id = $request->employee_id;
        $job->estimate = $request->estimate;
        $job->est_note = $request->est_note;
        $job->note = $request->note;
        $job->appraisal = $request->appraisal;        
        $job->due_date = $request->due_date;
        $job->completed_at = $request->completed_at;
        $job->vital_date = $request->vital_date;        


        $job->save();

        $images = $request->job_images;
        $image_ids = array();
        
        // Save images
        if (sizeof($images))
        {
            $nextId = DB::table('job_images')->max('id') + 1;                
            foreach ($images as $key => $image) 
            {

                if (!is_null($image["id"])) 
                {
                    $job_image = new Job_image;
                    $job_image = Job_image::where('id', $image["id"])->first();
                    if (is_null($image["note"])) $image["note"] = "";
                    $job_image->note = $image["note"];
        
                } 
                else 
                {

                    //Get proper file stream
                    $image["image"] = substr($image["image"], strpos($image["image"], ",")+1);
                    
                    //set filename
                    $filename = "public/job" . $job->id . "-" . $nextId++ . ".png";

                    while (file_exists(public_path() . $filename)) {
                        $filename = "public/job" . $job->id . "-" . $nextId++ . ".png";                    
                    }

                    //Write image to disk
                    Storage::disk('local')->put($filename, base64_decode($image["image"]));

                    //Get file url
                    $url = Storage::url($filename);

                    //Prepare job_image object
                    $job_image = new Job_image(['image' => $url, 'note' => $image["note"]]);

                }

                //Save job_image to DB
                $job->job_images()->save($job_image);
                array_push($image_ids, $job_image->id);

            }




        }        

        return response()->json(['id' => $job->id, 'image_ids' => $image_ids]);
    }

    public function delete(Request $request) 
    {
        $job = \App\Job::where('id', $request->id)->first();
        
        $images = $job->job_images;
        
        // Save images
        if (sizeof($images))
        {
            $nextId = DB::table('job_images')->max('id') + 1;                
            foreach ($images as $key => $image) 
            {
             if (file_exists(public_path() . $image->image)) unlink(public_path() . $image->image);
            }
        }
        \App\Job_image::where('job_id', $request->id)->delete();       
        $job->delete();        
    }

    public function show(Request $request)
    {
        $job = \App\Job::where('id', $request->id)->first();
        $job->job_images;        
        return response()->json($job);
    }

    public function customerJobs(Request $request)
    {
        $jobs = \App\Job::where('customer_id', $request->id)->get();
        return response()->json($jobs);
    }

    public function allJobsDetails(Request $request)
    {
        $order = "";
        if ($request->descending) $desc = 'desc';
        else $desc = 'asc';
        $job = \App\Job::with('customer')
        ->orderBy($request->sortBy, $desc)        
        ->paginate($request->rowsPerPage);   

        return response()->json($job);  
    }

}
