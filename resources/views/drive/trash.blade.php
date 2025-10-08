@extends('layouts.app')

@section('auth')
    <div class="min-height-300 bg-primary position-absolute w-100"></div>
    @include('layouts.navbars.auth.sidebar')
    
    <main class="main-content position-relative border-radius-lg">
        @include('layouts.navbars.auth.nav', ['title' => 'Sampah'])
        
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>File di dalam Tong Sampah</h6>
                            <p class="text-sm">File akan dihapus permanen setelah 30 hari (fitur akan datang).</p>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2">
                            @if (session('success'))
                                <div class="alert alert-success text-white mx-4" role="alert">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Size</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date Trashed</th>
                                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($trashedFiles as $file)
                                            <tr>
                                                <td>
                                                    <div class="d-flex px-2 py-1 align-items-center">
                                                        <i class="fas fa-file me-3 text-lg text-secondary"></i>
                                                        <span class="mb-0 text-sm">{{ $file->name }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <p class="text-xs font-weight-bold mb-0">{{ \App\Http\Controllers\Driver\DriveController::formatBytes($file->size) }}</p>
                                                </td>
                                                <td>
                                                    <p class="text-xs text-secondary mb-0">{{ $file->deleted_at->format('d M Y, H:i') }}</p>
                                                </td>
                                                <td class="align-middle text-center text-sm">
                                                    {{-- Tombol Restore --}}
                                                    <form action="{{ route('drive.trash.restore', $file->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-link text-success p-0 m-0 me-3" title="Restore"><i class="fas fa-undo"></i> Restore</button>
                                                    </form>
                                                    
                                                    {{-- Tombol Hapus Permanen --}}
                                                    <form action="{{ route('drive.trash.force-delete', $file->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda YAKIN ingin menghapus file ini secara permanen? Aksi ini tidak bisa dibatalkan.');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-link text-danger p-0 m-0" title="Delete Permanently"><i class="fas fa-times-circle"></i> Delete Forever</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-5">
                                                    <p class="text-secondary">Tong sampah kosong.</p>
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