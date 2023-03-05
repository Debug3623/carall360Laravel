<?php

namespace App\Policies;

use App\User;
use App\PaymentSummary;
use Illuminate\Auth\Access\HandlesAuthorization;



class PaymentSummaryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any roles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function index(User $user)
    {        
        return isAllowed($user, 'payment-summary-index');
    }

    public function show(User $user, PaymentSummary $PaymentSummary)
    {
        return isAllowed($user, 'payment-summary-show');   
    }

    public function create(User $user)
    {
        return isAllowed($user, 'payment-summary-create');
    }

    public function store(User $user)
    {
        return isAllowed($user, 'payment-summary-store');
    }

    public function update(User $user, PaymentSummary $PaymentSummary)
    {        
        if($user->id == $PaymentSummary->update_by)
            return true;
        
        return isAllowed($user, 'payment-summary-update');   
    }

    public function destroy(User $user, PaymentSummary $PaymentSummary)
    {
        return isAllowed($user, 'payment-summary-destroy');    
    }

    public function restore(User $user, PaymentSummary $PaymentSummary)
    {
        return isAllowed($user, 'payment-summary-restore');        
    }

    public function deleted(User $user)
    {
        return isAllowed($user, 'payment-summary-deleted');
    }

    public function delete(User $user, PaymentSummary $PaymentSummary)
    {
        return isAllowed($user, 'payment-summary-delete');
    }
}
