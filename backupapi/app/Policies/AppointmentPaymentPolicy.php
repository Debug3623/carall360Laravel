<?php

namespace App\Policies;

use App\User;
use App\AppointmentPayment;
use Illuminate\Auth\Access\HandlesAuthorization;



class AppointmentPaymentPolicy
{
    use HandlesAuthorization;

    public function index(User $user)
    {        
        return isAllowed($user, 'appointment-payment-index');
    }

    public function show(User $user, AppointmentPayment $AppointmentPayment)
    {
        return isAllowed($user, 'appointment-payment-show');   
    }
    public function render(User $user, AppointmentPayment $AppointmentPayment)
    {
        return isAllowed($user, 'appointment-payment-render');   
    }
    public function update(User $user, AppointmentPayment $AppointmentPayment)
    {        
        if($user->id == $AppointmentPayment->update_by)
            return true;
        
        return isAllowed($user, 'appointment-payment-update');   
    }
    
}
