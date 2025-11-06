<?php

namespace App\Listeners;

use App\Models\AuthAttempt;
use App\Models\IpAddress;
use App\Models\UserAgent;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LoginListener
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
        $ipAddress = IpAddress::where('ip_address', request()->ip())
            ->orderBy('id', 'desc')
            ->firstOrFail();

        $userAgent = UserAgent::where('user_agent', request()->userAgent())
            ->orderBy('id', 'desc')
            ->firstOrFail();

        AuthAttempt::create([
            'ip_address_id' => $ipAddress->id,
            'user_agent_id' => $userAgent->id,
            'username' => $event->user['username'],
            'event' => 'Login',
        ]);
    }
}
