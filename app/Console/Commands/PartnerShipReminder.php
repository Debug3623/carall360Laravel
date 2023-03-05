<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class PartnerShipReminder extends Command
{
    use \App\Traits\DoctorSettlement;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'PartnerShipReminder:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SMS or Email to doctor about partnerShip Reminder';

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
        $start_time = date("Y-m-d", strtotime("$today + 1 day"));
        $list       = $this->patnerShipExpirReminder($start_time);
        
        $pToday     = Carbon::now()->toDateTimeString();
        $pStartTime = date("Y-m-d", strtotime("$pToday"));
        $plist      = $this->patnerShipExpired($pStartTime);
        
        $this->info($list);
    }
}
