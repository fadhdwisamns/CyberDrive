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
                            <div class="d-flex align-items-center">
                                <div class="btn-group btn-group-sm me-3" role="group" aria-label="View toggle">
                                    <button type="button" class="btn btn-outline-primary active" id="btn-list-view" title="List View"><i class="fas fa-list"></i></button>
                                    <button type="button" class="btn btn-outline-primary" id="btn-grid-view" title="Grid View"><i class="fas fa-th-large"></i></button>
                                </div>
                                <button type="button" class="btn btn-primary btn-sm mb-0" data-bs-toggle="modal" data-bs-target="#newFolderModal">
                                    <i class="fas fa-folder-plus me-1"></i> New Folder
                                </button>
                                <button type="button" class="btn btn-success btn-sm mb-0 ms-2" data-bs-toggle="modal" data-bs-target="#uploadFileModal">
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

                            {{-- TAMPILAN DAFTAR (LIST VIEW) --}}
                            <div id="list-view">
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
                                            @foreach ($folders as $folder)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('drive.index', $folder->id) }}" class="d-flex px-2 py-1 align-items-center">
                                                            <i class="fas fa-folder me-3 text-lg text-warning"></i>
                                                            <span class="mb-0 text-sm font-weight-bold">{{ $folder->name }}</span>
                                                        </a>
                                                    </td>
                                                    <td><p class="text-xs font-weight-bold mb-0">-</p></td>
                                                    <td><p class="text-xs text-secondary mb-0">{{ $folder->updated_at->format('d M Y, H:i') }}</p></td>
                                                    <td class="align-middle text-center text-sm"></td>
                                                </tr>
                                            @endforeach

                                            @foreach ($files as $file)
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
                                                    <td><p class="text-xs font-weight-bold mb-0">{{ \App\Http\Controllers\Driver\DriveController::formatBytes($file->size) }}</p></td>
                                                    <td><p class="text-xs text-secondary mb-0">{{ $file->updated_at->format('d M Y, H:i') }}</p></td>
                                                    <td class="align-middle text-center text-sm">
                                                        {{-- [FIXED] Ikon diubah menjadi teks --}}
                                                        <div class="d-flex justify-content-center">
                                                            <form action="{{ route('drive.files.toggle-star', $file->id) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-link text-secondary px-2 mb-0" title="Toggle Star">
                                                                    @if($file->is_starred)Unstar @else Star @endif
                                                                </button>
                                                            </form>
                                                            <a href="{{ route('drive.preview', $file->id) }}" target="_blank" class="btn btn-link text-secondary px-2 mb-0" title="Preview">Preview</a>
                                                            <a href="{{ route('drive.download', $file->id) }}" class="btn btn-link text-secondary px-2 mb-0" title="Download">Download</a>
                                                            <form action="{{ route('drive.destroy', $file->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Pindahkan file ini ke tong sampah?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-link text-danger px-2 mb-0" title="Trash">Trash</button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- TAMPILAN GRID (GRID VIEW) - Awalnya disembunyikan --}}
                            <div id="grid-view" class="p-3" style="display: none;">
                                <div class="row" id="items-container">
                                    @foreach ($folders as $folder)
                                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-4" data-id="{{ $folder->id }}" data-type="folder">
                                            <a href="{{ route('drive.index', $folder->id) }}">
                                                <div class="card text-center h-100">
                                                    <div class="card-body d-flex flex-column justify-content-center">
                                                        <i class="fas fa-folder fa-3x text-warning mb-3"></i>
                                                        <p class="text-sm font-weight-bold mb-0 text-truncate">{{ $folder->name }}</p>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach

                                    @foreach ($files as $file)
                                        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-4" data-id="{{ $file->id }}" data-type="file">
                                            {{-- [FIXED] Seluruh kartu sekarang menjadi link ke halaman preview/download --}}
                                            <a href="{{ route('drive.preview', $file->id) }}" target="_blank" class="text-decoration-none">
                                                <div class="card h-100">
                                                    @php
                                                        // Mengambil ekstensi file dan mengubahnya ke huruf kecil
                                                        $extension = strtolower(pathinfo($file->name, PATHINFO_EXTENSION));
                                                        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
                                                    @endphp

                                                    {{-- [FIXED] Cek apakah file adalah gambar dan benar-benar ada di storage --}}
                                                    @if (in_array($extension, $imageExtensions) && Storage::disk('public')->exists($file->path))
                                                        {{-- JIKA GAMBAR: Tampilkan preview gambar --}}
                                                        <div class="card-header p-0 mx-3 mt-3 position-relative z-index-1">
                                                            <div class="d-block">
                                                                {{-- Gunakan Storage::url() untuk mendapatkan link publik ke file --}}
                                                                <img src="{{ Storage::url($file->path) }}" class="img-fluid border-radius-lg" style="width: 100%; height: 120px; object-fit: cover;" alt="{{ $file->name }}">
                                                            </div>
                                                        </div>
                                                        <div class="card-body pt-2 text-center">
                                                            <h6 class="text-sm font-weight-bold mb-0 text-truncate" title="{{ $file->name }}">{{ $file->name }}</h6>
                                                            <p class="text-xs text-secondary mt-1 mb-0">{{ \App\Http\Controllers\Driver\DriveController::formatBytes($file->size) }}</p>
                                                        </div>
                                                    @else
                                                        {{-- JIKA BUKAN GAMBAR: Tampilkan ikon seperti biasa --}}
                                                        <div class="card-body text-center d-flex flex-column justify-content-center">
                                                            @php
                                                                $icon = 'fa-file'; // Ikon default
                                                                if ($extension == 'pdf') $icon = 'fa-file-pdf text-danger';
                                                                else if (in_array($extension, ['doc', 'docx'])) $icon = 'fa-file-word text-primary';
                                                                else if (in_array($extension, ['xls', 'xlsx'])) $icon = 'fa-file-excel text-success';
                                                                else if (in_array($extension, ['zip', 'rar', '7z'])) $icon = 'fa-file-archive text-warning';
                                                            @endphp
                                                            <i class="fas {{ $icon }} fa-3x mb-3"></i>
                                                            <p class="text-sm font-weight-bold mb-0 text-truncate" title="{{ $file->name }}">{{ $file->name }}</p>
                                                            <p class="text-xs text-secondary mt-1 mb-0">{{ \App\Http\Controllers\Driver\DriveController::formatBytes($file->size) }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            @if($folders->isEmpty() && $files->isEmpty())
                                <div class="text-center py-5">
                                    <p class="text-secondary">Folder ini kosong.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @include('layouts.footers.auth.footer')
        </div>
        
        {{-- MODALS --}}
        <div class="modal fade" id="newFolderModal" tabindex="-1" aria-labelledby="newFolderModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newFolderModalLabel">Create New Folder</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('drive.folder.create') }}" method="POST">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $currentFolder ? $currentFolder->id : '' }}">
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
                        <input type="hidden" name="folder_id" value="{{ $currentFolder ? $currentFolder->id : '' }}">
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

@push('dashboard')
<style>
    /* Wrapper untuk kartu di grid view */
    .drive-item-wrapper {
        position: relative; /* Wajib ada agar 'position: absolute' di bawahnya berfungsi */
        border: 1px solid transparent;
        transition: border .2s ease-in-out, box-shadow .2s ease-in-out;
    }
    
    .drive-item-wrapper:hover {
        border-color: #dee2e6;
        box-shadow: 0 .125rem .25rem rgba(0,0,0,.075);
    }

    /* Bar untuk tombol aksi (download, delete, etc) */
    .action-bar {
        position: absolute;
        top: 8px; /* Jarak dari atas */
        right: 8px; /* Jarak dari kanan */
        z-index: 10;
        display: flex;
        flex-wrap: wrap; /* Menambahkan wrap agar tombol tidak keluar jika layar kecil */
        justify-content: flex-end; /* Ratakan ke kanan */
        gap: 5px; /* Jarak antar tombol */
        opacity: 0; /* Sembunyikan secara default */
        transition: opacity 0.2s ease-in-out;
    }

    /* Munculkan action-bar saat wrapper di-hover */
    .drive-item-wrapper:hover .action-bar {
        opacity: 1; /* Tampilkan saat di-hover */
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btnListView = document.getElementById('btn-list-view');
        const btnGridView = document.getElementById('btn-grid-view');
        const listView = document.getElementById('list-view');
        const gridView = document.getElementById('grid-view');

        btnListView.addEventListener('click', function() {
            if (!btnListView.classList.contains('active')) {
                listView.style.display = 'block';
                gridView.style.display = 'none';
                btnListView.classList.add('active');
                btnGridView.classList.remove('active');
            }
        });

        btnGridView.addEventListener('click', function() {
            if (!btnGridView.classList.contains('active')) {
                listView.style.display = 'none';
                gridView.style.display = 'block';
                btnGridView.classList.add('active');
                btnListView.classList.remove('active');
            }
        });

        const container = document.getElementById('items-container');
        if (container) {
            new Sortable(container, {
                animation: 150,
                group: 'shared',
                draggable: '[data-type="file"]',

                onEnd: function (evt) {
                    const fileEl = evt.item;
                    const targetEl = evt.to.closest('[data-type="folder"]');

                    if (!targetEl) {
                        return;
                    }

                    const fileId = fileEl.dataset.id;
                    const targetFolderId = targetEl.dataset.id;
                    
                    moveFile(fileId, targetFolderId, fileEl);
                }
            });
        }

        function moveFile(fileId, targetFolderId, element) {
            fetch(`/drive/files/${fileId}/move`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    target_folder_id: targetFolderId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    element.remove();
                    alert('File berhasil dipindahkan!'); 
                } else {
                    alert('Gagal memindahkan file: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memindahkan file.');
            });
        }
    });
</script>
@endpush