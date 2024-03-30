<?php

use App\Http\Controllers\Admin\UsersController;
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

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::get('dashboard', \App\Livewire\Home::class)->middleware(['auth'])->name('dashboard');
Route::view('profile', 'profile')->middleware(['auth'])->name('profile');
Route::get('activity-logs', [\App\Http\Controllers\Admin\ActivityLogsController::class, 'index'])->name('admin.activity-logs.index');
Route::get('activity-logs/{id}', [\App\Http\Controllers\Admin\ActivityLogsController::class, 'download'])->name('admin.activity-logs.download');

Route::middleware(['auth','is_admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::resource('users', \App\Http\Controllers\Admin\UsersController::class);
    Route::post('/import-users', [UsersController::class, 'importExcel'])->name('import.users');
    Route::get('/download-sample', [UsersController::class, 'downloadSample'])->name('download.users-sample');
    Route::get('/export-users', [UsersController::class, 'exportUsers'])->name('export.users');
    Route::get('documents', [\App\Http\Controllers\Admin\DocumentsController::class, 'index'])->name('documents.index');
    Route::delete('documents/{id}', [\App\Http\Controllers\Admin\DocumentsController::class, 'delete'])->name('documents.destroy');
    Route::delete('delete-activity-logs/{id}', [\App\Http\Controllers\Admin\ActivityLogsController::class, 'delete'])->name('activity-logs.destroy');
});

require __DIR__.'/auth.php';
