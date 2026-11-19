<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_and_regular_users_cannot_manage_users(): void
    {
        $user = $this->createAccount();

        $routes = [
            ['GET', '/users'],
            ['GET', '/users/create'],
            ['POST', '/users'],
            ['GET', "/users/{$user->id}/edit"],
            ['PUT', "/users/{$user->id}"],
            ['DELETE', "/users/{$user->id}"],
            ['GET', '/users/999/edit'],
        ];

        foreach ($routes as [$method, $url]) {
            $this->call($method, $url)->assertRedirect('/login');
        }

        $this->actingAs($user)->get('/rooms')->assertOk()->assertDontSee(route('users.index'));

        foreach ($routes as [$method, $url]) {
            $this->call($method, $url)->assertForbidden();
        }

        $this->assertDatabaseCount('users', 1);
    }

    public function test_admin_can_manage_users(): void
    {
        $admin = $this->createAccount();
        $admin->level = User::LEVEL_ADMIN;
        $admin->save();

        $this->actingAs($admin)->get('/users')->assertOk()->assertSee(route('users.index'));
        $this->get('/users/create')->assertOk();
        $this->post('/users', [
            'name' => 'Pengguna Baru',
            'email' => 'BARU@example.test',
            'level' => 'user',
            'password' => 'password-pengujian',
            'password_confirmation' => 'password-pengujian',
        ])->assertRedirect('/users');

        $user = User::where('email', 'baru@example.test')->firstOrFail();
        $this->assertTrue(Hash::check('password-pengujian', $user->password));
        $this->get('/users')->assertSee('Pengguna Baru');
        $this->get("/users/{$user->id}/edit")->assertOk();
        $this->put("/users/{$user->id}", [
            'name' => 'Pengguna Diubah',
            'email' => $user->email,
            'level' => 'admin',
            'password' => '',
        ])->assertRedirect('/users');

        $this->assertSame($user->password, $user->fresh()->password);
        $this->assertTrue($user->fresh()->isAdmin());
        $this->delete("/users/{$user->id}")->assertRedirect('/users');
        $this->assertModelMissing($user);

        $this->put("/users/{$admin->id}", [
            'name' => $admin->name,
            'email' => $admin->email,
            'level' => 'user',
        ])->assertSessionHasErrors('level');
        $this->delete("/users/{$admin->id}")->assertForbidden();
        $this->assertTrue($admin->fresh()->isAdmin());
    }

    private function createAccount(): User
    {
        return User::create([
            'name' => 'Akun Praktikum',
            'email' => 'praktikum@example.test',
            'password' => 'password-pengujian',
        ]);
    }
}
