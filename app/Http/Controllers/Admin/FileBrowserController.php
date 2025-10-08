<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileBrowserController extends Controller
{
    /**
     * Menampilkan daftar semua user karyawan.
     */
    public function index()
    {
        $users = User::where('role', '!=', 'admin')->paginate(15);
        return view('admin.file-browser.index', compact('users'));
    }

    /**
     * Menampilkan file-file milik user tertentu.
     */
    public function showUserFiles(User $user)
    {
  
        $userBasePath = 'user_files/' . $user->id;

        if (!Storage::disk('public')->exists($userBasePath)) {
            Storage::disk('public')->makeDirectory($userBasePath);
        }

        $directories = Storage::disk('public')->directories($userBasePath);
        $files = Storage::disk('public')->files($userBasePath);

        return view('admin.file-browser.show', compact('user', 'directories', 'files', 'userBasePath'));
    }
}