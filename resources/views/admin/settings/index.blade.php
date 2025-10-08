@extends('layouts.app')

@section('auth')
    <div class="min-height-300 bg-primary position-absolute w-100"></div>
    @include('layouts.navbars.auth.sidebar')

    <main class="main-content position-relative border-radius-lg">
        @include('layouts.navbars.auth.nav', ['title' => 'System Settings'])

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h6>Manage Application Settings</h6>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success text-white" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <form action="{{ route('admin.settings.update') }}" method="POST">
                                @csrf
                                <h6 class="text-sm">User Settings</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="default_quota_gb" class="form-control-label">Default New User Quota (GB)</label>
                                            <input class="form-control" type="number" step="1" min="0" name="default_quota_gb" id="default_quota_gb" 
                                                   value="{{ $settings['default_quota_gb']->value ?? 1 }}">
                                        </div>
                                    </div>
                                </div>

                                <hr class="horizontal dark">
                                <h6 class="text-sm">File Upload Settings</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="max_upload_size_mb" class="form-control-label">Max Upload Size (MB)</label>
                                            <input class="form-control" type="number" step="1" min="1" name="max_upload_size_mb" id="max_upload_size_mb" 
                                                   value="{{ $settings['max_upload_size_mb']->value ?? 100 }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="allowed_file_types" class="form-control-label">Allowed File Types (comma separated)</label>
                                            <input class="form-control" type="text" name="allowed_file_types" id="allowed_file_types" 
                                                   value="{{ $settings['allowed_file_types']->value ?? 'jpg,jpeg,png,pdf,docx,xlsx,zip' }}" placeholder="jpg,png,pdf...">
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary mt-4">Save Settings</button>
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