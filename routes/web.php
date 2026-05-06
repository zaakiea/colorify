<?php

use App\Http\Controllers\CollectionController;
use App\Http\Controllers\ColorPaletteController;
use App\Http\Controllers\PresetController;
use App\Http\Controllers\AdminPresetController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ColorGeneratorController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\TrashController;
use App\Http\Controllers\CollectionPaletteController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserManagementController;

Route::get('/', [ColorGeneratorController::class, 'index']);
Route::post('/generate-palette', [ColorGeneratorController::class, 'generatePalette']);
Route::get('/presets', [PresetController::class, 'index'])->name('presets.index');





Route::middleware(['auth'])->group(function () {
   Route::post('/collections', [CollectionController::class, 'store'])->name('collections.store');
   Route::get('/collections', [CollectionController::class, 'index'])->name('collections.index');
   Route::get('/collection/{slug}', [CollectionController::class, 'show'])->name('collections.show');
   Route::get('/trash', [TrashController::class, 'index'])->name('trash.index');
   Route::post('/trash/move/{id}', [TrashController::class, 'move'])->name('trash.move');
   Route::post('/trash/restore/{id}', [TrashController::class, 'restore'])->name('trash.restore');
   Route::delete('/trash/delete/{id}', [TrashController::class, 'delete'])->name('trash.delete');
   Route::get('/collections-palettes', [CollectionPaletteController::class, 'index'])->name('palette.index');

   Route::post('/collections/{collection}/palettes', [ColorPaletteController::class, 'store'])->name('collections.palettes.store');
   Route::put('/collections/{collection}/palettes/{palette}', [ColorPaletteController::class, 'update'])->name('collections.palettes.update');
   Route::delete('/collections/{collection}/palettes/{palette}', [ColorPaletteController::class, 'destroy'])->name('collections.palettes.destroy');
   Route::get('/collections/palettes/{palette}', [ColorPaletteController::class, 'show'])->name('collections.palettes.show');
});

// Authentication routes
Route::middleware('guest')->group(function () {
   Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
   Route::post('register', [RegisteredUserController::class, 'store']);
   Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
   Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
   Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
   Route::patch('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
   Route::patch('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');
   Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// Admin Routes
Route::middleware(['auth', 'admin'])
   ->prefix('admin')
   ->name('admin.')
   ->group(function () {

      // Users
      Route::resource('users', UserManagementController::class);

      // Presets
      Route::resource('presets', AdminPresetController::class);
   });
