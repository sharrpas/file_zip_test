<?php

namespace App\Jobs;

use App\Services\ArchiveService;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SaveFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     */

    protected $mainFile;

    public function __construct($mainFile)
    {
        $this->mainFile = $mainFile;
//        $this->onQueue('saving');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        sleep(2); // for testing ...
        app()->make(ArchiveService::class)->store($this->mainFile); //binding

        // we can use this without binding
        //(new ArchiveService())->store($request);
    }
}
