@extends('layouts.app')

@section('auth')
    {{-- div ini untuk header berwarna biru di bagian atas --}}
    <div class="min-height-300 bg-primary position-absolute top-0 start-0 w-100" style="z-index:-1;"></div>
    
    {{-- Baris ini memanggil sidebar --}}
    @include('layouts.navbars.auth.sidebar')
    
    {{-- Ini adalah area konten utama --}}
    <main class="main-content position-relative border-radius-lg">
        
        {{-- Di sini kita letakkan seluruh kode Anda yang lama --}}
        @include('layouts.navbars.auth.nav', ['title' => 'User Management'])
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Authors table</h6>
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
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action (Set Quota)</th>
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
                                                    {{ \App\Http\Controllers\Admin\UserController::formatBytes($user->storage_used) }} / {{ \App\Http\Controllers\Admin\UserController::formatBytes($user->storage_quota) }}
                                                </p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <form action="{{ route('admin.users.updateQuota', $user->id) }}" method="POST" class="d-flex justify-content-center">
                                                    @csrf
                                                    <div class="input-group" style="max-width: 200px;">
                                                        <input type="number" class="form-control" name="storage_quota_gb" value="{{ round($user->storage_quota / 1073741824, 2) }}" step="0.1" min="0" placeholder="Quota in GB" required>
                                                        <button class="btn btn-primary btn-sm mb-0" type="submit">Save</button>
                                                    </div>
                                                    @error('storage_quota_gb') 
                                                        <div class="text-danger text-xs">{{ $message }}</div> 
                                                    @enderror
                                                </form>
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
