@extends('layouts.app')

@section('auth')
    {{-- div ini untuk header berwarna biru di bagian atas --}}
    <div class="min-height-300 bg-primary position-absolute top-0 start-0 w-100" style="z-index:-1;"></div>
    
    {{-- Baris ini memanggil sidebar --}}
    @include('layouts.navbars.auth.sidebar')
    
    {{-- Ini adalah area konten utama --}}
    <main class="main-content position-relative border-radius-lg">
        
        {{-- Header dan judul halaman --}}
        @include('layouts.navbars.auth.nav', ['title' => 'User Management'])
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center pb-0">
                            <h6>Authors Table</h6>
                            <a href="{{ route('admin.users.create') }}" 
                               class="btn btn-sm btn-primary mb-0">
                                + Create User
                            </a>
                        </div>

                        <div class="card-body px-0 pt-0 pb-2">
                            @if (session('success'))
                                <div class="alert alert-success mx-4" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">User</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Storage Usage</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $user)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div>
                                                        <img src="../assets/img/team-2.jpg" class="avatar avatar-sm me-3" alt="user1">
                                                    </div>
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $user->name }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $user->email }}</p>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ \App\Http\Controllers\Admin\UserController::formatBytes($user->storage_used) }} / 
                                                    {{ \App\Http\Controllers\Admin\UserController::formatBytes($user->storage_quota) }}
                                                </p>
                                            </td>

                                            <td class="align-middle text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    {{-- Tombol Edit --}}
                                                    <a href="{{ route('admin.users.edit', $user->id) }}" 
                                                       class="btn btn-sm btn-warning mb-0">
                                                        Edit
                                                    </a>

                                                    {{-- Tombol Delete --}}
                                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure want to delete this user?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger mb-0">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-center mt-4">
                                {{ $users->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.footers.auth.footer')
        </div>
    </main>
@endsection
