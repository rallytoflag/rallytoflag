<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminController;
use App\Models\Event;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome');
});

Route::get('/help', function () {
    return Inertia::render('Help');
});

Route::get('/contact', function () {
    return Inertia::render('Contact');
});

Route::get('/about', function () {
    return Inertia::render('About');
});

Route::get('/terms', function () {
    return Inertia::render('TermsOfService');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
      'events' => Event::query()
        ->where('user_id', auth()->user()->id)
        ->with('media')
        ->orderBy('created_at', 'desc')
        ->paginate(10)
        ->withQueryString()
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('admin')->group(function () {
  Route::get('/admin', [AdminController::class, 'index'])->name('admin');
  Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');
  Route::get('/admin/events', [AdminController::class, 'events'])->name('admin.events');
});

Route::middleware('auth', 'verified')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

  Route::patch('/events/{event}', [EventController::class, 'update'])->name('event.update');
  Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('event.edit');
  Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('event.destroy');
  Route::post('/events', [EventController::class, 'store'])->name('event.store');
  Route::get('/events/new', [EventController::class, 'create'])->name('event.create');
});

Route::get('/events', [EventController::class, 'index'])->name('event.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('event.show');

require __DIR__.'/auth.php';
