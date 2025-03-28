<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Pages\DashboardController;
use App\Http\Controllers\Pages\InvestorController;
use App\Http\Controllers\Pages\KategoriController;
use App\Http\Controllers\Pages\RoleController;
use App\Http\Controllers\Pages\TransferController;
use App\Http\Controllers\Pages\TypeController;
use App\Http\Controllers\Pages\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('post.login');

Route::middleware(['auth'])->group(function() {
    // logout
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    // dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // data role
    Route::prefix('role')->group(function() {
        Route::get('/', [RoleController::class, 'index'])->name('role.index');
        Route::post('/store', [RoleController::class, 'store'])->name('role.store');
        Route::get('/{id}/permission', [RoleController::class, 'permission'])->name('role.permission');
        Route::put('/{id}/savePermission', [RoleController::class, 'savePermission'])->name('role.savePermission');
        Route::get('/{id}/show', [RoleController::class, 'show'])->name('role.show');
        Route::put('/{id}/update', [RoleController::class, 'update'])->name('role.update');
        Route::delete('/{id}/destroy', [RoleController::class, 'destroy'])->name('role.destroy');
    });
    
    // data users
    Route::prefix('users')->group(function() {
        Route::get('/', [UserController::class, 'index'])->name('user.index')->can('lihat pengguna');
        Route::get('/create', [UserController::class, 'create'])->name('user.create')->can('buat pengguna');
        Route::post('/store', [UserController::class, 'store'])->name('user.store')->can('buat pengguna');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('user.edit')->can('ubah pengguna');
        Route::put('/{id}/update', [UserController::class, 'update'])->name('user.update')->can('ubah pengguna');
        Route::delete('/{id}/destroy', [UserController::class, 'destroy'])->name('user.destroy')->can('hapus pengguna');
    });
    
    Route::prefix('module-investor')->group(function() {
        // data type investasi
        Route::prefix('types')->group(function() {
            Route::get('/', [TypeController::class, 'index'])->name('type.index')->can('lihat type investasi');
            Route::post('/store', [TypeController::class, 'store'])->name('type.store')->can('buat type investasi');
            Route::get('/{id}/show', [TypeController::class, 'show'])->name('type.show')->can('lihat type investasi');
            Route::put('/{id}/update', [TypeController::class, 'update'])->name('type.update')->can('ubah type investasi');
            Route::delete('/{id}/destroy', [TypeController::class, 'destroy'])->name('type.destroy')->can('hapus type investasi');
        });

        // data kategori investasi
        Route::prefix('categories')->group(function() {
            Route::get('/', [KategoriController::class, 'index'])->name('kategori.index')->can('lihat kategori investasi');
            Route::post('/store', [KategoriController::class, 'store'])->name('kategori.store')->can('buat kategori investasi');
            Route::get('/{id}/show', [KategoriController::class, 'show'])->name('kategori.show')->can('lihat kategori investasi');
            Route::put('/{id}/update', [KategoriController::class, 'update'])->name('kategori.update')->can('ubah kategori investasi');
            Route::delete('/{id}/destroy', [KategoriController::class, 'destroy'])->name('kategori.destroy')->can('hapus kategori investasi');
        });

        // data investor
        Route::prefix('investor')->group(function() {
            Route::get('/', [InvestorController::class, 'index'])->name('investor.index')->can('lihat investor');
            Route::get('/create', [InvestorController::class, 'create'])->name('investor.create')->can('buat investor');
            Route::post('/store', [InvestorController::class, 'store'])->name('investor.store')->can('buat investor');
            Route::get('/{id}/edit', [InvestorController::class, 'edit'])->name('investor.edit')->can('ubah investor');
            Route::put('/{id}/update', [InvestorController::class, 'update'])->name('investor.update')->can('ubah investor');
            Route::delete('/{id}/destroy', [InvestorController::class, 'destroy'])->name('investor.destroy')->can('hapus investor');
        });
    });

    Route::prefix('transfer')->group(function() {
        Route::get('/', [TransferController::class, 'index'])->name('transfer.index')->can('lihat transfer');
        Route::get('/create', [TransferController::class, 'create'])->name('transfer.create')->can('buat transfer');
        Route::post('/store', [TransferController::class, 'store'])->name('transfer.store')->can('buat transfer');
        Route::get('/{id}/edit', [TransferController::class, 'edit'])->name('transfer.edit')->can('edit transfer');
        Route::put('/{id}/update', [TransferController::class, 'update'])->name('transfer.update')->can('edit transfer');
        Route::put('/{id}/confirmation', [TransferController::class, 'confirmation'])->name('transfer.confirmation')->can('edit transfer');
        Route::delete('/{id}/destroy', [TransferController::class, 'destroy'])->name('transfer.destroy')->can('hapus transfer');
    });
});

Route::get('/fetchAndStoreBanks', [TransferController::class, 'fetchAndStoreBanks']);