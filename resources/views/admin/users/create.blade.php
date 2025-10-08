@extends('layouts.app')

@section('auth')
    <div class="min-height-300 bg-primary position-absolute w-100"></div>
    @include('layouts.navbars.auth.sidebar')
    
    <main class="main-content position-relative border-radius-lg">
        @include('layouts.navbars.auth.nav', ['title' => 'Create New User'])
        
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h6>New User Form</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.users.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="form-control-label">Name</label>
                                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name') }}" required>
                                            @error('name') <div class="text-danger text-xs">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="form-control-label">Email address</label>
                                            <input class="form-control" type="email" name="email" id="email" value="{{ old('email') }}" required>
                                            @error('email') <div class="text-danger text-xs">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password" class="form-control-label">Password</label>
                                            <input class="form-control" type="password" name="password" id="password" required>
                                            @error('password') <div class="text-danger text-xs">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password_confirmation" class="form-control-label">Confirm Password</label>
                                            <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="role">Role</label>
                                            <select class="form-control" name="role" id="role" required>
                                                <option value="karyawan">Karyawan</option>
                                                <option value="admin">Admin</option>
                                            </select>
                                            @error('role') <div class="text-danger text-xs">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="storage_quota_gb">Storage Quota (GB)</label>
                                            <input class="form-control" type="number" step="0.1" min="0" name="storage_quota_gb" id="storage_quota_gb" value="{{ old('storage_quota_gb', 1) }}" required>
                                            @error('storage_quota_gb') <div class="text-danger text-xs">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary mt-4">Cancel</a>
                                    <button type="submit" class="btn btn-primary mt-4">Create User</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.footers.auth.footer')
        </div>
    </main>
@endsection