<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job;
use App\Job_image;
use Storage;

class JobController extends Controller
{

    public function index()
    {
        $job = Job::all();
        return response()->json($job);
    }

    public function create(Request $request) 
    {
        $job = new Job;

        $job->customer_id = $request->customer_id;
        $job->employee_id = $request->employee_id;
        $job->estimate = $request->estimate;
        $job->est_note = $request->est_note;
        $job->appraisal = $request->appraisal;
        $job->due_date = $request->due_date;
        $job->completed_at = $request->completed_at;


        $job->save();

        $images = $request->job_images;
        $image_ids = array();
        
        // Save images
        foreach ($images as $key => $image) 
        {
            //Get proper file stream
            $image["image"] = substr($image["image"], strpos($image["image"], ",")+1);
            
            //set filename
            $filename = "public/job" . $job->id . "-" . $key . ".png";

            //Write image to disk
            Storage::disk('local')->put($filename, base64_decode($image["image"]));

            //Get file url
            $url = Storage::url($filename);

            //Prepare job_image object
            $job_image = new Job_image(['image' => $url, 'note' => $image["note"]]);

            //Save job_image to DB
            $job->job_image()->save($job_image);
            array_push($image_ids, $job_image->id);            

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
        $job->appraisal = $request->appraisal;
        $job->due_date = $request->due_date;
        $job->completed_at = $request->completed_at;


        $job->save();

        $images = $request->job_images;
        $image_ids = array();
        
        // Save images
        foreach ($images as $key => $image) 
        {

            if (!is_null($image["job_image_id"])) {
                $job_image = new Job_image;
                $job_image = Job_image::where('id', $image["job_image_id"])->first();
                $job_image->note = $image["note"];
    
            } else {

                //Get proper file stream
                $image["image"] = substr($image["image"], strpos($image["image"], ",")+1);
                
                //set filename
                $filename = "public/job" . $job->id . "-" . $key . ".png";

                //Write image to disk
                Storage::disk('local')->put($filename, base64_decode($image["image"]));

                //Get file url
                $url = Storage::url($filename);

                //Prepare job_image object
                $job_image = new Job_image(['image' => $url, 'note' => $image["note"]]);

            }


            //Save job_image to DB
            $job->job_image()->save($job_image);
            array_push($image_ids, $job_image->id);

        }        

        return response()->json(['id' => $job->id, 'image_ids' => $image_ids]);
    }

    public function delete(Request $request) 
    {
        \App\Job::destroy($request->id);
        echo response()->json($request->id);
    }

}
