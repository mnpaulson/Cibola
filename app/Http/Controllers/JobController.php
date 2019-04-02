<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job;
use App\Job_image;
use Storage;
use DB;
use Carbon\Carbon;
use stdClass;
use DateTime;

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
            ->take(13)
            ->get();    

   

        return response()->json($job);
    }

    public function create(Request $request) 
    {
        $job = new Job;

        if ($request->customer_id == 0) {
            trigger_error("Customer cannot be blank");
        }

        $job->customer_id = $request->customer_id;
        $job->employee_id = $request->employee_id;
        if ($request->estimate) $job->estimate = str_replace(',', '', $request->estimate);
        else $job->estimate = 0;
        $job->deposit = $request->deposit;
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

        if ($request->customer_id == 0) {
            trigger_error("Customer cannot be blank");
        }

        $job->customer_id = $request->customer_id;
        $job->employee_id = $request->employee_id;
        if ($request->estimate) $job->estimate = str_replace(',', '', $request->estimate);
        else $job->estimate = 0;
        $job->deposit = $request->deposit;
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
        ->with('employee')
        ->orderBy($request->sortBy, $desc)        
        ->paginate($request->rowsPerPage);   

        return response()->json($job);  
    }

    public function complete(Request $request)
    {
        $job = \App\Job::where('id', $request->id)->first();
        $job->completed_at = date("Y-m-d");

        $job->save();

        return response()->json($job->id);
    }

    public function uncomplete(Request $request)
    {
        $job = \App\Job::where('id', $request->id)->first();
        $job->completed_at = null;

        $job->save();

        return response()->json($job->id);
    }

    public function stats()
    {
        // $jobs = \App\Job::whereMonth('created_at', Carbon::now()->format('m'))
        // ->select('estimate', 'created_at')
        // ->get();

        $stats = new stdClass();
        $date = getdate();
        $dateString = $date['year'] . '-' . $date['mon']  . '-' .  $date['mday'];
        $dateObj = new DateTime($dateString);

        $stats->monthTotals = array();
        $stats->monthNames = array();
        $stats->monthJobs = array();


        for ($i = 0; $i < 12; $i++)
        {

            $total = \App\Job::whereMonth('created_at', $dateObj->format('m'))
                ->whereYear('created_at', $dateObj->format('Y'))
                ->sum('estimate');

            $count = \App\Job::whereMonth('created_at', $dateObj->format('m'))
                ->whereYear('created_at', $dateObj->format('Y'))
                ->count();
            
            array_push($stats->monthTotals, $total);
            array_push($stats->monthNames, $dateObj->format('F'));
            array_push($stats->monthJobs, $count);
            // array_push($stats->monthNames, $dateObj->format('Y-M'));


            $dateObj = $dateObj->modify('first day of last month');
        }


        /*
        Aug, Sept, Oct,
        Nov, Dec, Jan,
        Feb, Mar, Apr, 
        May, Jun, Jul, 
        */


        $stats->monthTotals = array_reverse($stats->monthTotals);
        $stats->monthNames = array_reverse($stats->monthNames);
        $stats->monthJobs = array_reverse($stats->monthJobs);


        return response()->json($stats);

    }

}
