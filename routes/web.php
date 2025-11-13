<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TamuController;
use App\Http\Controllers\ResepsionisController;
use App\Http\Controllers\HostController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Rute Publik (Guest / Tamu)
|--------------------------------------------------------------------------
| - Landing page: bisa diakses semua orang (guest).
| - Pengajuan tamu: publik (kalau ingin dibatasi, pindahkan ke middleware tertentu).
*/
Route::get('/', [TamuController::class, 'landing'])->name('welcome');
Route::post('/pengajuan', [TamuController::class, 'store'])->name('tamu.pengajuan.store');

/*
|--------------------------------------------------------------------------
| Auth Routes (Guest Only)
|--------------------------------------------------------------------------
| - /login GET: tampilkan form login (view.auth.login)
| - /login POST: proses login
| Guest = user yang belum login. Jika sudah login, diarahkan ke dashboard sesuai role.
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

/*
|--------------------------------------------------------------------------
| Logout (Auth Only)
|--------------------------------------------------------------------------
*/
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Resepsionis (Auth + Role: resepsionis)
|--------------------------------------------------------------------------
| Semua rute resepsionis dibungkus middleware 'auth' dan 'role:resepsionis'
| agar tidak bisa diakses selain resepsionis.
*/
Route::middleware(['auth', 'role:resepsionis'])->group(function () {
    Route::get('/resepsionis', [ResepsionisController::class, 'index'])->name('resepsionis.index');
    Route::get('/resepsionis/stats', [ResepsionisController::class, 'stats'])->name('resepsionis.stats');

    // route '/tambah' kamu tetap dipertahankan, hanya diproteksi role resepsionis
    Route::post('/tambah', [ResepsionisController::class, 'store'])->name('resepsionis.tambah.store');

    Route::get('/resepsionis/data-tamu', [ResepsionisController::class, 'datatamu'])->name('resepsionis.datatamu');
    Route::get('/resepsionis/data-tamu/export/xlsx', [ResepsionisController::class, 'exportDatatamuXlsx'])
        ->name('resepsionis.datatamu.export.xlsx');

    Route::post('/resepsionis/kunjungan/{kunjungan}/selesai', [ResepsionisController::class, 'markSelesai'])
        ->name('resepsionis.kunjungan.selesai');
});

/*
|--------------------------------------------------------------------------
| Host (Auth + Role: host)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:host'])->group(function () {
    Route::get('/host', [HostController::class, 'index'])->name('host.index');

    Route::post('/host/kunjungan/{kunjungan}/terima', [HostController::class, 'accept'])
        ->name('host.kunjungan.terima');

    Route::post('/host/kunjungan/{kunjungan}/tolak', [HostController::class, 'reject'])
        ->name('host.kunjungan.tolak');
});

/*
|--------------------------------------------------------------------------
| Admin (Auth + Role: admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    // API sederhana untuk Blade Alpine
    Route::prefix('admin/api')->group(function () {
        // Users
        Route::get('/users', [AdminController::class, 'usersIndex']);
        Route::post('/users', [AdminController::class, 'usersStore']);
        Route::put('/users/{user}', [AdminController::class, 'usersUpdate']);
        Route::delete('/users/{user}', [AdminController::class, 'usersDestroy']);

        // Kunjungan (flatten untuk UI "Kelola Tamu")
        Route::get('/kunjungan', [AdminController::class, 'kunjunganIndex']);
        Route::post('/kunjungan', [AdminController::class, 'kunjunganStore']);
        Route::post('/kunjungan/{kunjungan}', [AdminController::class, 'kunjunganUpdate']); // jika pakai method spoofing
        Route::put('/kunjungan/{kunjungan}', [AdminController::class, 'kunjunganUpdate']);
        Route::delete('/kunjungan/{kunjungan}', [AdminController::class, 'kunjunganDestroy']);
    });
});
