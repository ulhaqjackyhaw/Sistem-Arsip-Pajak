<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\VendorLoginController;

use App\Http\Controllers\Admin\VendorController as AdminVendor;
use App\Http\Controllers\Officer\DocumentController as OfficerDoc;
use App\Http\Controllers\Officer\BulkUploadController as Bulk;
use App\Http\Controllers\Vendor\DocumentController as VendorDoc;

use App\Http\Middleware\RoleMiddleware;

// ===================== LANDING =====================
Route::get('/', function () {
    $user = auth()->user();

    if ($user) {
        return match ($user->role ?? null) {
            'vendor'  => redirect()->route('vendor.documents.index'),
            'admin'   => redirect()->route('admin.vendors.index'),
            'officer' => redirect()->route('officer.vendors.index'),
            default   => redirect()->route('dashboard'),
        };
    }

    return view('landing');
})->name('home');

// ===================== ADMIN =====================
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class . ':admin'])
    ->prefix('admin')->name('admin.')->group(function () {
        // CRUD Vendor
        Route::get('vendors', [\App\Http\Controllers\Admin\VendorController::class, 'index'])->name('vendors.index');
        Route::get('vendors/create', [\App\Http\Controllers\Admin\VendorController::class, 'create'])->name('vendors.create');
        Route::post('vendors', [\App\Http\Controllers\Admin\VendorController::class, 'store'])->name('vendors.store');
        Route::get('vendors/{vendor}/edit', [\App\Http\Controllers\Admin\VendorController::class, 'edit'])->name('vendors.edit');
        Route::put('vendors/{vendor}', [\App\Http\Controllers\Admin\VendorController::class, 'update'])->name('vendors.update');
        Route::delete('vendors/{vendor}', [\App\Http\Controllers\Admin\VendorController::class, 'destroy'])->name('vendors.destroy');

        // Akun vendor
        Route::post('vendors/{vendor}/account', [\App\Http\Controllers\Admin\VendorController::class, 'createAccount'])->name('vendors.account.create');
        Route::post('vendors/{vendor}/reset-password', [\App\Http\Controllers\Admin\VendorController::class, 'resetPassword'])->name('vendors.account.reset');

        Route::put('vendors/{vendor}/password', [\App\Http\Controllers\Admin\VendorController::class, 'updatePassword'])
        ->name('vendors.password.update');

        // Import / Export
        Route::post('vendors/import', [\App\Http\Controllers\Admin\VendorController::class, 'import'])->name('vendors.import');
        Route::get('vendors/export', [\App\Http\Controllers\Admin\VendorController::class, 'export'])->name('vendors.export');
    });


// ===================== OFFICER =====================
Route::middleware(['auth', RoleMiddleware::class . ':officer,admin'])
    ->prefix('officer')->name('officer.')->group(function () {
        Route::get('vendors', [OfficerDoc::class, 'index'])->name('vendors.index');
        Route::get('vendors/{vendor}', [OfficerDoc::class, 'showVendor'])->name('vendors.show');

        // Upload dokumen
        Route::post('documents', [OfficerDoc::class, 'store'])->name('documents.store');

        // Unduh & hapus dokumen
        Route::get('documents/{document}/download', [OfficerDoc::class, 'download'])->name('documents.download');
        Route::delete('documents/{document}', [OfficerDoc::class, 'destroy'])->name('documents.destroy');

        // Bulk upload
        Route::get('bulk', [Bulk::class, 'form'])->name('bulk.form');
        Route::post('bulk', [Bulk::class, 'upload'])->name('bulk.upload');
    });

// ===================== VENDOR =====================
Route::middleware(['auth', RoleMiddleware::class . ':vendor'])
    ->prefix('vendor')->name('vendor.')->group(function () {
        Route::get('documents', [VendorDoc::class, 'index'])->name('documents.index');

        // Vendor boleh unduh dokumennya sendiri (re-use OfficerDoc@download)
        Route::get('documents/{document}/download', [OfficerDoc::class, 'download'])->name('documents.download');
    });

// ===================== DASHBOARD (post-login) =====================
Route::get('/dashboard', function () {
    $user = auth()->user();

    return match ($user->role ?? null) {
        'vendor'  => redirect()->route('vendor.documents.index'),
        'admin'   => redirect()->route('admin.vendors.index'),
        'officer' => redirect()->route('officer.vendors.index'),
        default   => redirect()->route('home'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// ===================== PROFILE =====================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ===================== VENDOR AUTH =====================
Route::get('/vendor/login', [VendorLoginController::class, 'showLoginForm'])->name('vendor.login.form');
Route::post('/vendor/login', [VendorLoginController::class, 'login'])->name('vendor.login');
Route::post('/vendor/logout', [VendorLoginController::class, 'logout'])->name('vendor.logout');

// Breeze auth
require __DIR__ . '/auth.php';
