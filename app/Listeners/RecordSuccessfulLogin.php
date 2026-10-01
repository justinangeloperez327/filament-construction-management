<?php

namespace App\Listeners;

use App\Models\LoginActivity;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;

class RecordSuccessfulLogin
{
    public function __construct(private readonly Request $request)
    {
    }

    public function handle(Login $event): void
    {
        $event->user->forceFill([
            'last_login_at' => now(),
        ])->save();

        LoginActivity::query()->create([
            'user_id' => $event->user->getKey(),
            'ip_address' => $this->request->ip(),
            'user_agent' => $this->request->userAgent(),
            'logged_in_at' => now(),
        ]);
    }
}
