<?php
namespace App\Services;


use App\Models\Job;

class JobService
{
    public function deleteJob($id) {
        // $jobId = $id+10;
        $job = Job::findOrFail($id);

     /*    if($job){
            $job->delete();
    }
         else {

            throw new \Exception('Job not found');
        } */
        $job->delete();
    
    }
}