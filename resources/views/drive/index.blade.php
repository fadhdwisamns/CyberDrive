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
                                                        <form action="{{ route('drive.files.toggle-star', $file->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-link text-warning p-0 m-0 me-3" title="Toggle Star">
                                                                @if($file->is_starred)<i class="fas fa-star"></i>@else<i class="far fa-star"></i>@endif
                                                            </button>
                                                        </form>
                                                        <a href="{{ route('drive.download', $file->id) }}" class="btn btn-link text-dark p-0 m-0 me-3" title="Download"><i class="fas fa-download"></i></a>
                                                        <form action="{{ route('drive.destroy', $file->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Pindahkan file ini ke tong sampah?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-link text-danger p-0 m-0" title="Trash"><i class="fas fa-trash"></i></button>
                                                        </form>
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
                                            <div class="card h-100">
                                                <div class="card-body text-center d-flex flex-column justify-content-center">
                                                    @php
                                                        $extension = pathinfo($file->name, PATHINFO_EXTENSION);
                                                        $icon = 'fa-file';
                                                        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) $icon = 'fa-file-image';
                                                        else if ($extension == 'pdf') $icon = 'fa-file-pdf';
                                                        else if (in_array($extension, ['doc', 'docx'])) $icon = 'fa-file-word';
                                                    @endphp
                                                    <i class="fas {{ $icon }} fa-3x mb-3"></i>
                                                    <p class="text-sm font-weight-bold mb-0 text-truncate" title="{{ $file->name }}">{{ $file->name }}</p>
                                                    <p class="text-xs text-secondary mt-1 mb-0">{{ \App\Http\Controllers\Driver\DriveController::formatBytes($file->size) }}</p>
                                                </div>
                                                <div class="card-footer text-center pt-0">
                                                    <form action="{{ route('drive.files.toggle-star', $file->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-link text-warning px-2" title="Toggle Star">
                                                            @if($file->is_starred)<i class="fas fa-star"></i>@else<i class="far fa-star"></i>@endif
                                                        </button>
                                                    </form>
                                                    <a href="{{ route('drive.download', $file->id) }}" class="btn btn-link text-dark px-2" title="Download"><i class="fas fa-download"></i></a>
                                                    <form action="{{ route('drive.destroy', $file->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Pindahkan file ini ke tong sampah?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-link text-danger px-2" title="Trash"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                </div>
                                            </div>
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
    /* 🌈 GOOGLE DRIVE THEME STYLE */

    /* --- GRID VIEW --- */
    #grid-view {
        display: none;
    }

    #grid-view .drive-item {
        position: relative;
        border-radius: 10px;
        background-color: #fff;
        transition: all 0.2s ease-in-out;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    #grid-view .drive-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.12);
    }

    /* Icon besar di tengah */
    #grid-view .drive-item .icon-wrapper {
        text-align: center;
        padding: 25px 0 10px;
        color: #5f6368;
    }

    #grid-view .drive-item .icon-wrapper i {
        font-size: 48px;
    }

    #grid-view .drive-item .item-name {
        font-size: 14px;
        font-weight: 500;
        text-align: center;
        margin: 0 10px 5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Info size dan date kecil */
    #grid-view .drive-item .item-meta {
        font-size: 12px;
        color: #80868b;
        text-align: center;
        margin-bottom: 8px;
    }

    /* Tombol aksi muncul saat hover */
    #grid-view .drive-item .action-bar {
        position: absolute;
        top: 6px;
        right: 6px;
        display: flex;
        gap: 4px;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    #grid-view .drive-item:hover .action-bar {
        opacity: 1;
    }

    #grid-view .action-bar button {
        background: rgba(255,255,255,0.8);
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        color: #5f6368;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s ease;
    }

    #grid-view .action-bar button:hover {
        background: #e8f0fe;
        color: #1a73e8;
    }

    /* --- LIST VIEW --- */
    .table thead th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #5f6368;
        border-bottom: 1px solid #e0e0e0;
    }

    .table tbody tr:hover {
        background-color: #f1f3f4;
        transition: background-color 0.2s;
    }

    /* Star warna gold */
    .text-warning i {
        color: #f4b400 !important;
    }

    /* --- Tombol toggle view --- */
    #btn-list-view.active,
    #btn-grid-view.active {
        background-color: #1a73e8 !important;
        color: white !important;
    }

    /* --- Empty Folder State --- */
    .empty-drive {
        padding: 60px 0;
        color: #9aa0a6;
        font-size: 15px;
        text-align: center;
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
                    // Anda bisa mengganti alert ini dengan notifikasi yang lebih bagus
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