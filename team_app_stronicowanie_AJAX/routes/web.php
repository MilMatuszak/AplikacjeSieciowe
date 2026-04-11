<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\CoachEventController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\CoachPlayerController;


// ── GOŚĆ ──────────────────────────────────────
Route::get('/', fn() => view('welcome'));
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// ── ZALOGOWANI ────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', function() {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return redirect('/admin/users');
        }

        if ($user->hasRole('coach')) {
            return redirect('/coach/events');
        }

        return redirect('/events');
    })->name('dashboard');
});

// ── ZAWODNIK ──────────────────────────────────
Route::middleware(['auth', 'role:player,coach,admin'])->group(function () {
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
});

// ── TRENER ────────────────────────────────────
Route::middleware(['auth', 'role:coach,admin'])->prefix('coach')->group(function () {
    Route::get('/events', [CoachEventController::class, 'index'])->name('coach.events.index');
    Route::get('/events/create', [CoachEventController::class, 'create'])->name('coach.events.create');
    Route::post('/events', [CoachEventController::class, 'store'])->name('coach.events.store');
    Route::get('/events/{id}/edit', [CoachEventController::class, 'edit'])->name('coach.events.edit');
    Route::put('/events/{id}', [CoachEventController::class, 'update'])->name('coach.events.update');
    Route::delete('/events/{id}', [CoachEventController::class, 'destroy'])->name('coach.events.destroy');
    Route::get('/players', [CoachPlayerController::class, 'index'])->name('coach.players.index');
    Route::get('/players/{id}/stats', [CoachPlayerController::class, 'stats'])->name('coach.players.stats');
    Route::get('/events/{id}/attendance', [CoachEventController::class, 'attendance'])->name('coach.events.attendance');
    Route::put('/events/{eventId}/attendance/{userId}', [CoachEventController::class, 'updateAttendance'])->name('coach.attendance.update');
});

// ── ADMINISTRATOR ─────────────────────────────
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{id}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}/role', [AdminUserController::class, 'updateRole'])->name('admin.users.role');
    Route::put('/users/{id}/password', [AdminUserController::class, 'resetPassword'])->name('admin.users.password');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    Route::put('/users/{id}/data', [AdminUserController::class, 'updateData'])->name('admin.users.data');
});

use App\Http\Controllers\RsvpController;

// ── OBECNOŚĆ ──────────────────────────────────
Route::middleware(['auth', 'role:player,coach,admin'])->group(function () {
    Route::post('/events/{id}/rsvp', [RsvpController::class, 'store'])->name('rsvp.store');
    Route::put('/events/{id}/rsvp', [RsvpController::class, 'update'])->name('rsvp.update');
});

use App\Http\Controllers\StatsController;

// ── STATYSTYKI ────────────────────────────────
Route::middleware(['auth', 'role:player,coach,admin'])->group(function () {
    Route::get('/my-stats', [StatsController::class, 'myStats'])->name('stats.my');
});
