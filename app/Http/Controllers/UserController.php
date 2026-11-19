<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('users.index', ['users' => User::orderBy('name')->orderBy('id')->paginate(10)]);
    }

    public function create(): View
    {
        return view('users.form', ['user' => new User]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = new User;
        $this->saveUser($request, $user);

        return to_route('users.index')->with('status', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('users.form', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        DB::transaction(function () use ($request, $user) {
            $this->saveUser($request, $this->lockUsers($request, $user));
        }, 3);

        return to_route('users.index')->with('status', 'Pengguna berhasil diubah.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        abort_if($request->user()->is($user), 403, 'Akun sendiri tidak dapat dihapus.');

        DB::transaction(function () use ($request, $user) {
            $this->lockUsers($request, $user)->delete();
        }, 3);

        return to_route('users.index')->with('status', 'Pengguna berhasil dihapus.');
    }

    private function saveUser(Request $request, User $user): void
    {
        $editingSelf = $request->user()->is($user);
        $uniqueEmail = Rule::unique('users');

        if ($user->exists) {
            $uniqueEmail->ignore($user);
        }

        if (is_string($request->input('email'))) {
            $request->merge(['email' => $request->string('email')->lower()->toString()]);
        }
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', $uniqueEmail],
            'password' => [$user->exists ? 'nullable' : 'required', 'string', Password::min(8), 'confirmed'],
            'level' => $editingSelf
                ? ['prohibited']
                : ['required', Rule::in([User::LEVEL_USER, User::LEVEL_ADMIN])],
        ]);

        $user->fill(['name' => $data['name'], 'email' => $data['email']]);

        if (! empty($data['password'])) {
            $user->password = $data['password'];
            $user->remember_token = null;
        }

        if (! $editingSelf) {
            $user->level = $data['level'];
        }

        $user->save();
    }

    private function lockUsers(Request $request, User $target): User
    {
        $actorId = $request->user()->getKey();
        $users = User::whereKey([$actorId, $target->getKey()])
            ->orderBy('id')->lockForUpdate()->get()->keyBy('id');

        abort_unless($users->get($actorId)?->isAdmin(), 403);

        return $users->get($target->getKey()) ?? abort(404);
    }
}
