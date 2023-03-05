<?php

namespace App\Policies;

use App\User;
use App\PaymentRefund;
use Illuminate\Auth\Access\HandlesAuthorization;



class AppointmentRefundPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {        
        return isAllowed($user, 'appointment-refund-index');
    }
    public function show(User $user, PaymentRefund $PaymentRefund)
    {
        return isAllowed($user, 'appointment-refund-show');   
    }
    public function render(User $user, PaymentRefund $PaymentRefund)
    {
        return isAllowed($user, 'appointment-refund-render');   
    }
    public function update(User $user, PaymentRefund $PaymentRefund)
    {        
        if($user->id == $PaymentRefund->update_by)
            return true;
        
        return isAllowed($user, 'appointment-refund-update');   
    }
    
}
