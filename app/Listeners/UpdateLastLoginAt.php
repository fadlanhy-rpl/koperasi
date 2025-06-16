<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
// use Illuminate\Contracts\Queue\ShouldQueue; // Hapus jika listener tidak di-queue
// use Illuminate\Queue\InteractsWithQueue; // Hapus jika listener tidak di-queue
use Carbon\Carbon;
use App\Models\User; // Pastikan model User di-import jika akan type-hint

class UpdateLastLoginAt
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        // $event->user seharusnya adalah instance dari App\Models\User
        if ($event->user instanceof \App\Models\User) { // Pengecekan eksplisit (opsional tapi bisa bantu IDE)
            $user = $event->user;
            $user->last_login_at = Carbon::now();
            $user->save();
        }
    }
}