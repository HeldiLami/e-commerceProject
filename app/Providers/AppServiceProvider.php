<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        }

    public function boot(): void
    {
        Gate::define('is-verified', function (User $user) {
            return $user->email_verified_at !== null;
        });
        
        Event::listen(Failed::class, function (Failed $event) {
            $email = $event->credentials['email'] ?? 'unknown';

            $existing = DB::table('login_logs')->where('email', $email)->first();

        if ($existing) {
            DB::table('login_logs')->where('email', $email)->update([
                'attempts' => $existing->attempts + 1,
                'event_type' => 'failed',
                'ip_address' => request()->ip(),
                'updated_at' => now(),
            ]);
        } else {
            DB::table('login_logs')->insert([
                'email' => $email,
                'attempts' => 1,
                'event_type' => 'failed',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    });

        Event::listen(Login::class, function (Login $event) {
            DB::table('login_logs')->where('email', $event->user->email)->update([
                'attempts' => 0,
                'event_type' => 'success',
                'updated_at' => now(),
            ]);
        });
    }
}
