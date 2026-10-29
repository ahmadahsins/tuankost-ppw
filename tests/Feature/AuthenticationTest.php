<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoomSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_open_public_pages_and_authentication_forms(): void
    {
        foreach (['/', '/about', '/login', '/register'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_guest_cannot_read_or_modify_room_management_data(): void
    {
        $this->seed(RoomSeeder::class);

        foreach (['/dashboard', '/rooms', '/rooms/create', '/rooms/search?search=A1', '/rooms/1/edit', '/rooms/kamar-a1'] as $url) {
            $this->get($url)->assertRedirect('/login');
        }

        $this->post('/rooms', [])->assertRedirect('/login');
        $this->put('/rooms/1', ['room_number' => 'X99'])->assertRedirect('/login');
        $this->delete('/rooms/1')->assertRedirect('/login');
        $this->assertDatabaseCount('rooms', 20);
        $this->assertDatabaseHas('rooms', ['id' => 1, 'room_number' => 'A1']);
    }

    public function test_registration_creates_a_hashed_password_and_logs_in(): void
    {
        $this->post('/register', [
            'name' => 'Pengguna Uji',
            'email' => 'BARU@example.test',
            'password' => 'password-pengujian',
            'password_confirmation' => 'password-pengujian',
        ])->assertRedirect('/dashboard');

        $user = User::where('email', 'baru@example.test')->firstOrFail();
        $this->assertAuthenticatedAs($user);
        $this->assertTrue(Hash::check('password-pengujian', $user->password));
        $this->get('/dashboard')->assertOk()->assertSee('Pengguna Uji');
    }

    public function test_registration_rejects_duplicate_email_and_password_mismatch(): void
    {
        $this->createUser();

        $this->post('/register', [
            'name' => 'Pengguna Lain',
            'email' => 'pengguna@example.test',
            'password' => 'password-pengujian',
            'password_confirmation' => 'konfirmasi-berbeda',
        ])->assertSessionHasErrors(['email', 'password']);

        $this->assertGuest();
        $this->assertDatabaseCount('users', 1);
    }

    public function test_login_rejects_invalid_credentials_with_an_indonesian_message(): void
    {
        $this->createUser();

        $this->from('/login')->post('/login', [
            'email' => 'pengguna@example.test',
            'password' => 'salah',
        ])->assertRedirect('/login')
            ->assertSessionHasErrors(['email' => 'Email atau kata sandi tidak sesuai.']);

        $this->assertGuest();
    }

    public function test_successful_login_returns_to_the_intended_page(): void
    {
        $user = $this->createUser();
        $this->get('/rooms')->assertRedirect('/login');

        $this->post('/login', [
            'email' => 'pengguna@example.test',
            'password' => 'password-pengujian',
        ])->assertRedirect('/rooms');

        $this->assertAuthenticatedAs($user);
        $this->get('/rooms')->assertOk();
        $this->get('/login')->assertRedirect('/dashboard');
        $this->get('/register')->assertRedirect('/dashboard');
    }

    public function test_logout_requires_post_and_revokes_authenticated_access(): void
    {
        $this->actingAs($this->createUser());
        $this->get('/logout')->assertStatus(405);
        $this->post('/logout')->assertRedirect('/');
        $this->assertGuest();
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_login_is_limited_to_five_attempts_per_minute(): void
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post('/login', [
                'email' => 'limit@example.test',
                'password' => 'salah',
            ])->assertStatus(302);
        }

        $this->post('/login', [
            'email' => 'limit@example.test',
            'password' => 'salah',
        ])->assertStatus(429);
    }

    private function createUser(): User
    {
        return User::create([
            'name' => 'Pengguna Uji',
            'email' => 'pengguna@example.test',
            'password' => 'password-pengujian',
        ]);
    }
}
