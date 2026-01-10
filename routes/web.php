<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PortfolioController;
use App\Models\Section;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [PortfolioController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| TEMP DEBUG ROUTE (REMOVE AFTER CHECK)
|--------------------------------------------------------------------------
| Akses: /_debug/sections
*/

Route::get('/_debug/sections', function () {
    return response()->json([
        'app_env' => config('app.env'),
        'db_default' => config('database.default'),
        'db_host' => config('database.connections.' . config('database.default') . '.host'),
        'db_database' => config('database.connections.' . config('database.default') . '.database'),
        'sections_total' => Section::count(),
        'sections_enabled' => Section::where('enabled', true)->count(),
        'sample_sections' => Section::select('id', 'title', 'enabled', 'sort_order')
            ->orderBy('sort_order')
            ->limit(5)
            ->get(),
    ]);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (Laravel Breeze / Fortify)
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
