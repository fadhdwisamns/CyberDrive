<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\File;
use App\Models\Setting;

class DriveController extends Controller
{
    private $userBasePath;

    /**
     * Constructor untuk menentukan base path setiap user.
     * Ini memastikan file setiap user terisolasi.
     */
    public function __construct()
    {

        $this->middleware(function ($request, $next) {
            $this->userBasePath = 'user_files/' . auth()->id();
            return $next($request);
        });
    }

    /**
     * Menampilkan file dan folder.
     */
    public function index($folder = null) // Untuk saat ini, parameter folder diabaikan
    {

        $files = File::where('user_id', auth()->id())->latest()->get();

        $breadcrumbs = [
            ['name' => 'My Drive', 'path' => route('drive.index')]
        ];
        
        return view('drive.index', compact('files', 'breadcrumbs'));
    }

    /**
     * Menangani proses upload file.
     */
    public function upload(Request $request)
    {
        $settings = Setting::all()->keyBy('key');
        $maxSizeMB = $settings['max_upload_size_mb']->value ?? 100;
        $allowedTypes = $settings['allowed_file_types']->value ?? 'jpg,jpeg,png,pdf,docx,xlsx,zip';

        $request->validate([
            'file' => ['required', 'file', 'max:' . ($maxSizeMB * 1024), 'mimes:' . $allowedTypes],
            'current_path' => 'required|string' 
        ]);

        $uploadedFile = $request->file('file');
        $fileSize = $uploadedFile->getSize();
        $user = auth()->user();

        if (($user->storage_used + $fileSize) > $user->storage_quota) {
            return back()->with('error', 'Upload gagal! Kuota penyimpanan Anda tidak mencukupi.');
        }

        $path = $uploadedFile->store($this->userBasePath, 'public');

        File::create([
            'user_id' => $user->id,
            'name' => $uploadedFile->getClientOriginalName(),
            'path' => $path,
            'size' => $fileSize,
            'mime_type' => $uploadedFile->getMimeType(),
        ]);

        $user->increment('storage_used', $fileSize);

        return back()->with('success', 'File berhasil diupload.');
    }

    /**
     * Membuat folder baru.
     */
     public function createFolder(Request $request)
    {
        return back()->with('error', 'Fitur folder sedang dalam pengembangan.');
    }
    
    /**
     * Menangani download file.
     */
   public function download($fileId) 
    {
        $file = File::where('user_id', auth()->id())->findOrFail($fileId);
        
        DB::table('user_file_views')->updateOrInsert(
            ['user_id' => auth()->id(), 'file_path' => $file->path],
            ['last_viewed_at' => now(), 'created_at' => now(), 'updated_at' => now()]
        );

        return Storage::disk('public')->download($file->path, $file->name);
    }
    
    /**
     * Menghapus file atau folder.
     */
    public function destroy($fileId)
    {

        $file = File::where('user_id', auth()->id())->findOrFail($fileId);

        auth()->user()->decrement('storage_used', $file->size);

        $file->delete();

        return back()->with('success', "'{$file->name}' telah dipindahkan ke tong sampah.");
    }
    

    private function generateBreadcrumbs($folder)
    {
        $breadcrumbs = [];
        $breadcrumbs[] = ['name' => 'My Drive', 'path' => route('drive.index')];
        
        if ($folder) {
            $pathSegments = explode('/', $folder);
            $currentPath = '';
            foreach ($pathSegments as $segment) {
                $currentPath .= $segment;
                $breadcrumbs[] = ['name' => $segment, 'path' => route('drive.index', ['folder' => $currentPath])];
                $currentPath .= '/';
            }
        }
        return $breadcrumbs;
    }

    private function getDirectorySize($path)
    {
        $totalSize = 0;
        $files = Storage::disk('public')->allFiles($path);
        foreach ($files as $file) {
            $totalSize += Storage::disk('public')->size($file);
        }
        return $totalSize;
    }

    public static function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
    public function recents()
    {
        $recentFileViews = DB::table('user_file_views')
            ->where('user_id', auth()->id())
            ->orderBy('last_viewed_at', 'desc')
            ->take(50)->get();

        $recentFiles = $recentFileViews->filter(function ($view) {
            return Storage::disk('public')->exists($view->file_path);
        });

        return view('drive.recents', compact('recentFiles'));
    }
    public function trash()
    {
        
        $trashedFiles = File::where('user_id', auth()->id())->onlyTrashed()->latest('deleted_at')->get();

        return view('drive.trash', compact('trashedFiles'));
    }

    /**
     * Mengembalikan file dari tong sampah.
     */
    public function restore($fileId)
    {
        $file = File::where('user_id', auth()->id())->onlyTrashed()->findOrFail($fileId);

        auth()->user()->increment('storage_used', $file->size);

        $file->restore();

        return redirect()->route('drive.trash')->with('success', "'{$file->name}' telah berhasil dikembalikan.");
    }

    /**
     * Menghapus file secara permanen.
     */
    public function forceDelete($fileId)
    {
        $file = File::where('user_id', auth()->id())->onlyTrashed()->findOrFail($fileId);

        Storage::disk('public')->delete($file->path);

        $file->forceDelete();

        return redirect()->route('drive.trash')->with('success', "'{$file->name}' telah dihapus secara permanen.");
    }
}