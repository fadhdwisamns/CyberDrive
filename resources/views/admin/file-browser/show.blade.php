@extends('layouts.app')

@section('auth')
    <div class="min-height-300 bg-primary position-absolute w-100"></div>
    @include('layouts.navbars.auth.sidebar')

    <main class="main-content position-relative border-radius-lg">
        {{-- Tampilkan nama user yang sedang dilihat --}}
        @include('layouts.navbars.auth.nav', ['title' => 'Files of ' . $user->name])

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex justify-content-between">
                            <h6>File List</h6>
                            <a href="{{ route('admin.file-browser.index') }}" class="btn btn-sm btn-secondary mb-0">Back to User List</a>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Size</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- List Directories --}}
                                        @foreach ($directories as $dir)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1 align-items-center">
                                                        <i class="fas fa-folder me-3 text-lg text-warning"></i>
                                                        <span class="mb-0 text-sm font-weight-bold">{{ basename($dir) }}</span>
                                                    </div>
                                                </td>
                                                <td><p class="text-xs font-weight-bold mb-0">-</p></td>
                                                <td class="align-middle text-center text-sm">-</td>
                                            </tr>
                                        @endforeach

                                        {{-- List Files --}}
                                        @foreach ($files as $file)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1 align-items-center">
                                                        <i class="fas fa-file me-3 text-lg"></i>
                                                        <span class="mb-0 text-sm">{{ basename($file) }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">{{ \App\Http\Controllers\Driver\DriveController::formatBytes(Storage::disk('public')->size($file)) }}</p>
                                                </td>
                                                <td class="align-middle text-center text-sm">
                                                    <a href="{{ route('drive.download', ['filePath' => urlencode($file)]) }}" class="btn btn-link text-dark p-0 m-0 me-3"><i class="fas fa-download"></i> Download</a>
                                                    {{-- Note: Delete functionality can be added here later --}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                 @if(empty($directories) && empty($files))
                                    <p class="text-center text-secondary my-5">This user has no files.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.footers.auth.footer')
        </div>
    </main>
@endsection