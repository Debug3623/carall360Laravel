<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class AppointmentReminder extends Command
{
    use \App\Traits\AppointmentReminderTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AppointmentReminder:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SMS or Email to patient and doctor about upcoming appointment';

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
     * @return mixed
     */
    public function handle()
    {
        $today      = Carbon::now()->toDateTimeString();
        $start_time = date("Y-m-d H:i:s", strtotime("$today + 3 hours"));
        $end_time   = date("Y-m-d H:i:s", strtotime("$today + 4 hours"));
        
        $list       = $this->AppointmentReminder($start_time, $end_time);
        
        $this->info($list);
    }
}
