<?php

namespace App\Observers;

use App\Events\EventUserCreated;
use App\Models\User;

class ObserveUser
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        broadcast(new EventUserCreated($user));
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
