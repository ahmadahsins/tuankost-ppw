<?php

namespace App\Listeners;

use App\Mail\AccountActivityMail;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;

class SendAccountActivityEmail
{
    public function handle(Login|Registered $event): void
    {
        // Fortify otomatis login setelah registrasi. Cukup kirim email selamat datang.
        if ($event instanceof Login && ($event->guard !== 'web' || $event->user->wasRecentlyCreated)) {
            return;
        }

        Mail::to($event->user->email)->queue(new AccountActivityMail(
            name: $event->user->name,
            registered: $event instanceof Registered,
            occurredAt: now()->timezone('Asia/Jakarta')->format('d-m-Y H:i:s').' WIB',
        ));
    }
}
