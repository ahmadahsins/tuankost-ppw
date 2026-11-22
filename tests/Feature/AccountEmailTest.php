<?php

namespace Tests\Feature;

use App\Mail\AccountActivityMail;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AccountEmailTest extends TestCase
{
    use DatabaseMigrations;

    public function test_registration_queues_only_one_welcome_email(): void
    {
        Mail::fake();

        $this->post('/register', [
            'name' => 'Akun Baru',
            'email' => 'baru@example.test',
            'password' => 'password-pengujian',
            'password_confirmation' => 'password-pengujian',
        ])->assertRedirect('/dashboard');

        Mail::assertQueued(AccountActivityMail::class, fn ($mail) => $mail->registered
            && $mail->hasTo('baru@example.test'));
        Mail::assertQueuedCount(1);
    }

    public function test_login_queues_email_only_when_credentials_are_valid(): void
    {
        Mail::fake();
        User::create([
            'name' => 'Pengguna Uji',
            'email' => 'uji@example.test',
            'password' => 'password-pengujian',
        ]);

        $this->post('/login', [
            'email' => 'uji@example.test',
            'password' => 'salah',
        ])->assertSessionHasErrors('email');
        Mail::assertNothingOutgoing();

        $this->post('/login', [
            'email' => 'uji@example.test',
            'password' => 'password-pengujian',
        ])->assertRedirect('/dashboard');

        Mail::assertQueued(AccountActivityMail::class, fn ($mail) => ! $mail->registered
            && $mail->hasTo('uji@example.test'));
        Mail::assertQueuedCount(1);
    }

    public function test_email_is_processed_from_database_queue(): void
    {
        config(['queue.default' => 'database', 'mail.default' => 'array']);

        Mail::to('uji@example.test')->queue(
            new AccountActivityMail('<Akun Uji>', false, '19-11-2026 10:00:00 WIB')
        );

        $this->assertDatabaseCount('jobs', 1);
        $this->artisan('queue:work', [
            '--once' => true,
            '--tries' => 1,
        ])->assertExitCode(0);
        $this->assertDatabaseCount('jobs', 0);
        $this->assertDatabaseCount('failed_jobs', 0);
    }

    public function test_templates_escape_names_and_display_correct_content(): void
    {
        $welcome = new AccountActivityMail('<script>uji</script>', true, '19-11-2026 10:00:00 WIB');
        $welcome->assertSeeInHtml('&lt;script&gt;uji&lt;/script&gt;', false);
        $welcome->assertSeeInHtml('berhasil didaftarkan');

        $login = new AccountActivityMail('Akun Uji', false, '19-11-2026 10:00:00 WIB');
        $login->assertSeeInHtml('berhasil login');
    }
}
