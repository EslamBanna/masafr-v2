<?php

namespace App\Console\Commands;

use App\Models\User\RequestService;
use Illuminate\Console\Command;
use Carbon;

class ExpiredRequestService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expired:request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete expired requests';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // $mytime = Carbon\Carbon::now();
        // $requests = RequestService::where('max_day', '<', $mytime)->get();
        // foreach ($requests as $request) {
        //     $request->messages()->delete();
        //     $request->notifications()->delete();
        //     $request->delete();
        // }
    }
}
