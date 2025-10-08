<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'admin')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function updateQuota(Request $request, User $user)
    {
        $request->validate([
            'storage_quota_gb' => 'required|numeric|min:0',
        ]);
        $quotaInBytes = $request->input('storage_quota_gb') * 1073741824;
        $user->storage_quota = $quotaInBytes;
        $user->save();
        return redirect()->route('admin.users.index')
                         ->with('success', 'Kuota untuk ' . $user->name . ' berhasil diperbarui.');
    }

    public static function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public function dashboard()
    {
        $karyawan = User::where('role', '!=', 'admin')->get();

        $totalKapasitas = $karyawan->sum('storage_quota');
        $totalUser = $karyawan->count();
        $userAktif = $karyawan->where('storage_used', '>', 0)->count();

        $userPenuh = $karyawan->filter(function ($user) {
            if ($user->storage_quota == 0) return false;
            return ($user->storage_used / $user->storage_quota) >= 0.95;
        })->count();

        $chartData = $karyawan->map(function ($user) {
            return [
                'name' => $user->name,
                'storage_used' => $user->storage_used,
                'storage_quota' => $user->storage_quota
            ];
        });

        return view('admin.dashboard', compact(
            'totalKapasitas',
            'totalUser',
            'userAktif',
            'userPenuh',
            'chartData'
        ));
    }
    /**
     * Menampilkan form untuk membuat user baru.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Menyimpan user baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi semua input dari form
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,karyawan',
            'storage_quota_gb' => 'required|numeric|min:0',
        ]);
         $defaultQuotaGB = Setting::where('key', 'default_quota_gb')->first()->value ?? 1;

        $quotaGB = $request->filled('storage_quota_gb') ? $request->storage_quota_gb : $defaultQuotaGB;

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'storage_quota' => $request->storage_quota_gb * 1073741824,
        ]);

        return redirect()->route('admin.users.index')
                        ->with('success', 'User baru berhasil dibuat.');
    }
    public function showUserPage()
    {
        $users = User::where('role', '!=', 'admin')->paginate(10);

        return view('admin.users.user', compact('users'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'storage_quota_gb' => 'required|numeric|min:0',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->storage_quota = $request->storage_quota_gb * 1073741824; // Konversi ke Bytes
        $user->save();

        return redirect()->route('admin.users.index')
                         ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')
                         ->with('success', 'User berhasil dihapus.');
    }
}