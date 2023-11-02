<?php

namespace App\Policies;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    //policy supaya hanya bisa diakses oleh admin
    public function adminOnly(User $user)
    {   
    return $user->role === 'Admin';
    }
}
