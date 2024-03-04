<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class JobHelper
{
    public function getFailedJob()
    {
        #Fetch all the failed jobs
        $jobs = DB::table('failed_jobs')->select()->get();

        #Loop through all the failed jobs and format them for json printing
        foreach ($jobs as $value) {
            $payload = json_decode($value);
            $command = unserialize($payload->data->command);
            $payload->data->command = $command->getAllVars();
        }

        return ($jobs);
    }
}
