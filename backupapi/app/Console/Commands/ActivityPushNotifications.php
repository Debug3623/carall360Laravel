<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ActivityPushNotifications extends Command
{
    use \App\Traits\FCM;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ActivityPushNotifications:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send push notification for sechedualing activity users.';

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
        //    
    }
}
