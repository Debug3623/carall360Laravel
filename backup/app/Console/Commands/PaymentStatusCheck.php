<?php
  
namespace App\Console\Commands;

use Illuminate\Console\Command;

use DB;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

class PaymentStatusCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'PaymentStatusInquiry:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'All unpaid payments with *_otc and not expired will be checked if paid at payment gateway and if found paid then marked paid locally';

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

        LOG::info("******************* PAYMENT STATUS INQUIRY - START **************************");
        
        $sSql = "

            SELECT 
                a.id, a.reference_no, b.transaction_ref, a.payment_timelimit, b.payment_method  
            FROM 
                appointments a,
                appointment_payments b
            where
                a.id = b.appointment_id and
                a.paid_status = 'unpaid' and
                a.status in ('pending', 'awaiting_confirmation') and
                b.payment_method in ('jc_otc', 'ep_otc', 'jc_ma', 'jc_cc', 'ep_ma') and
                a.payment_timelimit >= ? and
                a.deleted_at is null
        ";

        $aBindings = array(date('Y-m-d H:i:s')); //array('2020-12-12 19:20:00');

        $oResult = DB::select(DB::raw($sSql), $aBindings);

        LOG::info("COUNT: ".count($oResult));


        //dd($oResult);

        foreach($oResult as $aRw){

            //dump($aRw->reference_no." ** ".$aRw->transaction_ref." *** ".$aRw->payment_method);
            LOG::info(print_r($aRw, true));

            $aArg = array(
                $aRw->id, $aRw->transaction_ref
            );

            //dump($aArg);
            $controller = app()->make('App\Http\Controllers\AppointmentController');
            app()->call([$controller, 'getTransactionStatus'], $aArg);

            //dd("end first call");
        }

        LOG::info("******************* PAYMENT STATUS INQUIRY - END **************************");

    }
}
