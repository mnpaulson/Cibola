<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job;
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
        $images = json_decode($images, true);

        foreach ($images as $key => $image) 
        {
            return $image;
            $image = json_decode($image, true);
            // Storage::disk('local')->put($job->id . "-" . $key . ".jpg", $jobImage->image);
            return $image;
        }        

        return response()->json($job->id);
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
        return response()->json($job->id);
    }

    public function delete(Request $request) 
    {
        \App\Job::destroy($request->id);
        echo response()->json($request->id);
    }

}
