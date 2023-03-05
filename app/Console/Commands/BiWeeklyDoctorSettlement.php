<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class BiWeeklyDoctorSettlement extends Command
{
    use \App\Traits\DoctorSettlement;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BiWeeklyDoctorSettlement:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Doctor biweekly payment settlement';

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
        $durastion  = 'biweek'; 
        $list       = $this->doctorPaymentSettlement($durastion);
        
        $this->info($list);
    }
}
