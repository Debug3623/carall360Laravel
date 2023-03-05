<?php

namespace App\Policies;

use App\BankAccount;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BankAccountPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any bank accounts.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function index(User $user)
    {
        //
        return isAllowed($user, 'bankaccount-index');
    }


    /**
     * Determine whether the user can view the bank account.
     *
     * @param  \App\User  $user
     * @param  \App\BankAccount  $bankAccount
     * @return mixed
     */
    public function show(User $user, BankAccount $bankAccount)
    {
         return isAllowed($user, 'bankaccount-show');   
    }

    /**
     * Determine whether the user can create bank accounts.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return isAllowed($user, 'bankaccount-create');
    }

    /**
     * Determine whether the user can update the bank account.
     *
     * @param  \App\User  $user
     * @param  \App\BankAccount  $bankAccount
     * @return mixed
     */
    public function update(User $user, BankAccount $bankAccount)
    {
        if($user->id == $bankAccount->update_by)
            return true;
        return isAllowed($user, 'bankaccount-update');
    }
    /**
     * Determine whether the user can create bankaccount.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function store(User $user)
    {
        return isAllowed($user, 'bankaccount-store');
    }
     /**
     * Determine whether the user can restore the bankrole.
     *
     * @param  \App\User  $user
     * @param  \App\BankAccount  $bankAccount
     * @return mixed
     */
    public function deleted(User $user)
    {

        return isAllowed($user, 'bankaccount-deleted');
    }

    /**
     * Determine whether the user can delete the bank account.
     *
     * @param  \App\User  $user
     * @param  \App\BankAccount  $bankAccount
     * @return mixed
     */
    public function delete(User $user, BankAccount $bankAccount)
    {
       return isAllowed($user, 'bankaccount-delete');
    }
    /**
     * Determine whether the user can delete the bankaccount.
     *
     * @param  \App\User  $user
     * @param  \App\BankAccount  $bankAccount
     * @return mixed
     */
    public function destroy(User $user, BankAccount $bankAccount)
    {
        return isAllowed($user, 'bankaccount-destroy');

    }

    /**
     * Determine whether the user can restore the bank account.
     *
     * @param  \App\User  $user
     * @param  \App\BankAccount  $bankAccount
     * @return mixed
     */
    public function restore(User $user, BankAccount $bankAccount)
    {
        return isAllowed($user, 'bankaccount-restore');
    }
}
