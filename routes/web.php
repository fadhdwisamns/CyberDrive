<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\Driver\DriveController; 
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\FileBrowserController;
use App\Http\Controllers\Admin\SettingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', [HomeController::class, 'home'])->name('home');
    

    Route::get('dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
		return redirect()->route('drive.index');
	})->name('dashboard');

    Route::prefix('drive')->name('drive.')->group(function () {
        Route::get('/recents', [DriveController::class, 'recents'])->name('recents');
        Route::get('/{folder?}', [DriveController::class, 'index'])->where('folder', '.*')->name('index');
        Route::post('/upload', [DriveController::class, 'upload'])->name('upload');
        Route::get('/download/{filePath}', [DriveController::class, 'download'])->where('filePath', '.*')->name('download');
        Route::post('/folder/create', [DriveController::class, 'createFolder'])->name('folder.create');
        Route::delete('/delete/{filePath}', [DriveController::class, 'destroy'])->where('filePath', '.*')->name('destroy');
        Route::get('/trash', [DriveController::class, 'trash'])->name('trash'); 
        Route::post('/trash/{fileId}/restore', [DriveController::class, 'restore'])->name('trash.restore'); 
        Route::delete('/trash/{fileId}/force-delete', [DriveController::class, 'forceDelete'])->name('trash.force-delete');
    
        
    });

    Route::get('/logout', [SessionsController::class, 'destroy'])->name('logout');
});

Route::group(['middleware' => ['auth', 'is_admin'], 'prefix' => 'admin', 'as' => 'admin.'], function() {
    
    // UBAH RUTE INI AGAR MENGARAH KE CONTROLLER DASHBOARD
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

    // Rute untuk User Management
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::post('/users/{user}/update-quota', [UserController::class, 'updateQuota'])->name('users.updateQuota');
    Route::get('/user-page', [UserController::class, 'showUserPage'])->name('user.page');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
    Route::get('/file-browser', [FileBrowserController::class, 'index'])->name('file-browser.index');
    Route::get('/file-browser/{user}', [FileBrowserController::class, 'showUserFiles'])->name('file-browser.show');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});


Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [RegisterController::class, 'create']);
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [SessionsController::class, 'create'])->name('login');
    Route::post('/session', [SessionsController::class, 'store']);
	Route::get('/login/forgot-password', [ResetController::class, 'create']);
	Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
	Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
	Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');
});

