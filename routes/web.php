<?php

use App\Http\Controllers\Admin\ActivityLogsController;
use App\Http\Controllers\Admin\DocumentsController;
use App\Http\Controllers\Admin\UsersController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

Route::get('dashboard', \App\Livewire\Home::class)->middleware(['auth'])->name('dashboard');
Route::view('profile', 'profile')->middleware(['auth'])->name('profile');
Route::get('activity-logs', [ActivityLogsController::class, 'index'])->name('admin.activity-logs.index');
Route::get('activity-logs/{id}', [ActivityLogsController::class, 'download'])->name('admin.activity-logs.download');

Route::middleware(['auth','is_admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::resource('users', \App\Http\Controllers\Admin\UsersController::class);
    Route::post('/import-users', [UsersController::class, 'importExcel'])->name('import.users');
    Route::get('/download-sample', [UsersController::class, 'downloadSample'])->name('download.users-sample');
    Route::get('/export-users', [UsersController::class, 'exportUsers'])->name('export.users');
    Route::get('documents', [DocumentsController::class, 'index'])->name('documents.index');
    Route::delete('documents/{id}', [DocumentsController::class, 'delete'])->name('documents.destroy');
    Route::delete('delete-activity-logs/{id}', [ActivityLogsController::class, 'delete'])->name('activity-logs.destroy');
    Route::post('delete-all-logs', [ActivityLogsController::class, 'deleteAll'])->name('logs.delete-all');
    Route::post('delete-all-users', [UsersController::class, 'deleteAll'])->name('users.delete-all');
    Route::post('delete-all-documents', [DocumentsController::class, 'deleteAll'])->name('documents.delete-all');
    Route::get('/export-logs', [ActivityLogsController::class, 'exportLogs'])->name('logs.export');
});

require __DIR__.'/auth.php';
