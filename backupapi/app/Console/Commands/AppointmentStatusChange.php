<?php

namespace App\Console\Commands;

use App\Appointment;
use App\Center;
use App\Doctor;
use App\Patient;
use App\TimeSlot;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Jobs\SendEmail;
use Illuminate\Support\Facades\Mail;
use App\Mail\GeneralAlert;

class AppointmentStatusChange extends Command
{
    use \App\Traits\AppointmentReminderTrait;
    use \App\Traits\DoctorSettlement;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AppointmentStatusChange:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change the appointment status if patient did not pay as payment after 1 hour of payment time limit';

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
        $end_time   = date("Y-m-d H:i:s", strtotime("$today - 1 hours"));
        $start_time = date("Y-m-d H:i:s", strtotime("$end_time - 5 minutes"));
        $this->autoCancelAppointmentStatus($start_time,$end_time);

        $nsToday     = Carbon::now()->toDateTimeString();
        $nsEndTime   = date("Y-m-d H:i:s", strtotime("$nsToday - 2 hours"));
        $nsStartTime = date("Y-m-d H:i:s", strtotime("$nsEndTime - 5 minutes"));
        $this->noShowAppointmentStatus($nsStartTime,$nsEndTime);

        $rToday     = Carbon::now()->toDateTimeString();
        $rStartTime = date("Y-m-d H:i:s", strtotime("$rToday + 30 minutes"));
        $rEndTime   = date("Y-m-d H:i:s", strtotime("$rToday + 35 minutes"));
        $this->AppointmentReminder($rStartTime,$rEndTime);
        
        $this->info('SMS Sent successfully');
    }
}
