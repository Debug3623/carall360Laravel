<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AppointmentPushNotifications extends Command {

    use \App\Traits\FCM;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'AppointmentPushNotifications:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for run job for look up up comming appointments and send push notification for concen user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        $todayDate = date('Y-m-d H:i:s');
        $currentTimeFromNext2Hrs = time() + (2 * 3600);
        $todayDateNew = date('Y-m-d H:i:s', $currentTimeFromNext2Hrs);
    }

}
