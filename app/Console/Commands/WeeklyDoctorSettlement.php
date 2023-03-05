<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class WeeklyDoctorSettlement extends Command
{
    use \App\Traits\DoctorSettlement;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'WeeklyDoctorSettlement:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Doctor weekly payment settlement';

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
        $durastion  = 'week'; 
        $list       = $this->doctorPaymentSettlement($durastion);
        
        $this->info($list);
    }
}
