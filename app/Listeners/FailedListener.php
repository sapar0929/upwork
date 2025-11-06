<?php

namespace App\Listeners;

use App\Models\AuthAttempt;
use App\Models\IpAddress;
use App\Models\UserAgent;
use Illuminate\Auth\Events\Failed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class FailedListener
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
    public function handle(Failed $event): void
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
            'username' => $event->credentials['username'] . ' ' . $event->credentials['password'],
            'event' => 'Failed',
        ]);
    }
}
