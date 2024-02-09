<?php

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

Route::middleware(['auth','is_admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::resource('users', \App\Http\Controllers\Admin\UsersController::class);
    Route::get('documents', [\App\Http\Controllers\Admin\DocumentsController::class, 'index'])->name('documents.index');
    Route::delete('documents/{id}', [\App\Http\Controllers\Admin\DocumentsController::class, 'delete'])->name('documents.destroy');
    Route::get('activity-logs', [\App\Http\Controllers\Admin\ActivityLogsController::class, 'index'])->name('activity-logs.index');
    Route::delete('delete-activity-logs/{id}', [\App\Http\Controllers\Admin\ActivityLogsController::class, 'delete'])->name('activity-logs.destroy');
});

require __DIR__.'/auth.php';
