<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventReminderController;

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

Route::get('/', [EventReminderController::class, 'index'])->name('events.index');
Route::get('/events/create', [EventReminderController::class, 'create'])->name('events.create');
Route::post('/events', [EventReminderController::class, 'store'])->name('events.store');
Route::get('/events/{event}/edit', [EventReminderController::class, 'edit'])->name('events.edit');
Route::put('/events/{event}', [EventReminderController::class, 'update'])->name('events.update');
Route::delete('/events/{event}', [EventReminderController::class, 'destroy'])->name('events.destroy');
Route::post('/events/import', [EventReminderController::class, 'import'])->name('events.import');

Route::get('/sync-events', [EventReminderController::class, 'syncEvents'])->name('events.sync');

