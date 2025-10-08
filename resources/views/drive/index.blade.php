@extends('layouts.app')

@section('auth')
    <div class="min-height-300 bg-primary position-absolute w-100"></div>
    @include('layouts.navbars.auth.sidebar')
    
    <main class="main-content position-relative border-radius-lg">
        @include('layouts.navbars.auth.nav', ['title' => 'My Drive'])
        
        <div class="container-fluid py-4">

            {{-- Kartu Statistik Penggunaan --}}
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">Storage Usage</p>
                                        <h5 class="font-weight-bolder mb-0">
                                            {{ \App\Http\Controllers\Driver\DriveController::formatBytes(Auth::user()->storage_used) }}
                                            <span class="text-secondary text-sm font-weight-bolder">
                                                / {{ \App\Http\Controllers\Driver\DriveController::formatBytes(Auth::user()->storage_quota) }}
                                            </span>
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                        <i class="ni ni-archive-2 text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                 {{-- Breadcrumbs Navigation --}}
                                 <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                                        @foreach ($breadcrumbs as $breadcrumb)
                                            @if ($loop->last)
                                                <li class="breadcrumb-item text-sm text-dark active" aria-current="page">{{ $breadcrumb['name'] }}</li>
                                            @else
                                                <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="{{ $breadcrumb['path'] }}">{{ $breadcrumb['name'] }}</a></li>
                                            @endif
                                        @endforeach
                                    </ol>
                                </nav>
                            </div>
                            <div>
                                <button type="button" class="btn btn-primary btn-sm mb-0" data-bs-toggle="modal" data-bs-target="#newFolderModal">
                                    <i class="fas fa-folder-plus me-1"></i> New Folder
                                </button>
                                <button type="button" class="btn btn-success btn-sm mb-0" data-bs-toggle="modal" data-bs-target="#uploadFileModal">
                                    <i class="fas fa-upload me-1"></i> Upload File
                                </button>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            @if (session('success'))
                                <div class="alert alert-success text-white mx-4" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if ($errors->any())
                                <div class="alert alert-danger text-white mx-4" role="alert">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Size</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Last Modified</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- PERUBAHAN DI SINI: Kita loop dari variabel $files yang baru --}}
                                        @forelse ($files as $file)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1 align-items-center">
                                                        @php
                                                            $extension = pathinfo($file->name, PATHINFO_EXTENSION);
                                                            $icon = 'fa-file';
                                                            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) $icon = 'fa-file-image';
                                                            else if ($extension == 'pdf') $icon = 'fa-file-pdf';
                                                            else if (in_array($extension, ['doc', 'docx'])) $icon = 'fa-file-word';
                                                        @endphp
                                                        <i class="fas {{ $icon }} me-3 text-lg"></i>
                                                        <span class="mb-0 text-sm">{{ $file->name }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ \App\Http\Controllers\Driver\DriveController::formatBytes($file->size) }}
                                                    </p>
                                                </td>
                                                <td>
                                                    <p class="text-xs text-secondary mb-0">{{ $file->updated_at->format('d M Y, H:i') }}</p>
                                                </td>
                                                <td class="align-middle text-center text-sm">
                                                    {{-- PERUBAHAN DI SINI: Gunakan ID file, bukan path --}}
                                                    <a href="{{ route('drive.download', $file->id) }}" class="btn btn-link text-dark p-0 m-0 me-3"><i class="fas fa-download"></i> Download</a>
                                                    <form action="{{ route('drive.destroy', $file->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Pindahkan file ini ke tong sampah?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-link text-danger p-0 m-0"><i class="fas fa-trash"></i> Trash</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-5">
                                                    <p class="text-secondary">Tidak ada file. Coba upload beberapa file.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.footers.auth.footer')
        </div>
        
        <div class="modal fade" id="newFolderModal" tabindex="-1" aria-labelledby="newFolderModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newFolderModalLabel">Create New Folder</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('drive.folder.create') }}" method="POST">
                        @csrf
                        {{-- Untuk sementara, current_path kita hardcode ke root --}}
                        <input type="hidden" name="current_path" value="{{ 'user_files/' . auth()->id() }}">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="folder_name" class="form-label">Folder Name</label>
                                <input type="text" class="form-control" id="folder_name" name="folder_name" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create Folder</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="uploadFileModal" tabindex="-1" aria-labelledby="uploadFileModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadFileModalLabel">Upload File</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('drive.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{-- Untuk sementara, current_path kita hardcode ke root --}}
                        <input type="hidden" name="current_path" value="{{ 'user_files/' . auth()->id() }}">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="file" class="form-label">Choose file to upload</label>
                                <input class="form-control" type="file" id="file" name="file" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection