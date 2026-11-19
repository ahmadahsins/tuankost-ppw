@extends('layouts.app')

@section('title', 'Pengguna')

@section('content')
    <div class="container page">
        <h1>Daftar Pengguna</h1>
        <p><a class="btn btn-primary" href="{{ route('users.create') }}">Tambah Pengguna</a></p>

        @if (session('status'))
            <p class="alert alert-success" role="status">{{ session('status') }}</p>
        @endif

        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Level</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->isAdmin() ? 'Admin' : 'User' }}</td>
                            <td>
                                <a href="{{ route('users.edit', $user) }}">Edit</a>
                                @unless (auth()->user()->is($user))
                                    <form class="d-inline" method="POST" action="{{ route('users.destroy', $user) }}"
                                        onsubmit="return confirm('Hapus pengguna ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                    </form>
                                @endunless
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $users->links() }}
    </div>
@endsection
