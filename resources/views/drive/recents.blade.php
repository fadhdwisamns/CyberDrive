@extends('layouts.app')

@section('auth')
    <div class="min-height-300 bg-primary position-absolute w-100"></div>
    @include('layouts.navbars.auth.sidebar')

    <main class="main-content position-relative border-radius-lg">
        @include('layouts.navbars.auth.nav', ['title' => 'Baru Dilihat'])

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>File yang Baru Saja Diakses</h6>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Size</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Last Viewed</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($recentFiles as $file)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1 align-items-center">
                                                        @php
                                                            $extension = pathinfo($file->file_path, PATHINFO_EXTENSION);
                                                            $icon = 'fa-file'; // Ikon default
                                                            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) $icon = 'fa-file-image';
                                                            else if ($extension == 'pdf') $icon = 'fa-file-pdf';
                                                            else if (in_array($extension, ['doc', 'docx'])) $icon = 'fa-file-word';
                                                            else if (in_array($extension, ['xls', 'xlsx'])) $icon = 'fa-file-excel';
                                                            else if (in_array($extension, ['zip', 'rar'])) $icon = 'fa-file-archive';
                                                        @endphp
                                                        <i class="fas {{ $icon }} me-3 text-lg"></i>
                                                        <span class="mb-0 text-sm">{{ basename($file->file_path) }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">
                                                        {{ \App\Http\Controllers\Driver\DriveController::formatBytes(Storage::disk('public')->size($file->file_path)) }}
                                                    </p>
                                                </td>
                                                <td>
                                                    <p class="text-xs text-secondary mb-0">{{ \Carbon\Carbon::parse($file->last_viewed_at)->diffForHumans() }}</p>
                                                </td>
                                                <td class="align-middle text-center text-sm">
                                                    <a href="{{ route('drive.download', ['filePath' => urlencode($file->file_path)]) }}" class="btn btn-link text-dark p-0 m-0 me-3"><i class="fas fa-download"></i> Download</a>
                                                    <form action="{{ route('drive.destroy', ['filePath' => urlencode($file->file_path)]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this file?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-link text-danger p-0 m-0"><i class="fas fa-trash"></i> Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-5">
                                                    <p class="text-secondary">Belum ada file yang baru dilihat. Coba unduh beberapa file terlebih dahulu.</p>
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
    </main>
@endsection