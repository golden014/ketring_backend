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

    public function adminOnly(User $user)
    {   
    return $user->role === 'Admin';
    }
}
