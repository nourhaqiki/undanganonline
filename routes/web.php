<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\InvitationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Generator Undangan Online
|--------------------------------------------------------------------------
*/

// --- ALUR PUBLIK & PEMBUAT UNDANGAN ---
Route::get('/', [InvitationController::class, 'create'])->name('home');
Route::post('/', [InvitationController::class, 'store'])->name('invitation.store');
Route::get('/sukses/{slug}', [InvitationController::class, 'success'])->name('invitation.success');
Route::get('/kelola/{slug}/{token}', [InvitationController::class, 'manage'])->name('invitation.manage');
Route::put('/kelola/{slug}/{token}', [InvitationController::class, 'update'])->name('invitation.update');
Route::get('/template-tamu', [InvitationController::class, 'downloadGuestTemplate'])->name('guest.template');

// --- HALAMAN PUBLIK UNDANGAN & BUKU TAMU LIVE ---
Route::get('/u/{slug}', [InvitationController::class, 'show'])->name('invitation.show');
Route::post('/u/{slug}/wish', [InvitationController::class, 'storeWish'])->name('invitation.wish');

// --- AUTENTIKASI ADMIN ---
Route::get('/login', fn () => redirect()->route('admin.login'))->name('login');
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');

// --- PANEL ADMINISTRATOR (PROTECTED) ---
Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/invitations', [AdminController::class, 'invitations'])->name('invitations');
    Route::delete('/invitations/{id}', [AdminController::class, 'destroyInvitation'])->name('invitations.destroy');
    Route::delete('/wishes/{id}', [AdminController::class, 'destroyWish'])->name('wishes.destroy');
    Route::get('/themes', [AdminController::class, 'themes'])->name('themes');
    Route::post('/themes', [AdminController::class, 'storeTheme'])->name('themes.store');
    Route::patch('/themes/{id}/toggle', [AdminController::class, 'toggleTheme'])->name('themes.toggle');
});
