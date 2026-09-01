<?php

namespace App\Listeners;

use App\Mail\WelcomeEmail;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendWelcomeEmail
{
    /**
     * Handle the event.
     */
    public function handle(Verified $event): void
    {
        try {
            if ($event->user && $event->user->email) {
                Mail::to($event->user->email)->send(new WelcomeEmail($event->user));
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send WelcomeEmail on verification: ' . $e->getMessage());
        }
    }
}
