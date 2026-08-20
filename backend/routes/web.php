<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\OrganizacionController;
use App\Http\Controllers\SalasController;
use App\Http\Controllers\CultivosController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CatalogoVariedadController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

Route::get('/prueba-formulario', function () {
    return Inertia::render('Cultivos/Create');
});


Route::resource('catalogo-variedades', CatalogoVariedadController::class);
Route::resource('dashboard1', DashboardController::class);


    //organizacion
    Route::get('/organizaciones/create', [OrganizacionController::class, 'create'])
        ->name('organizaciones.create');

    Route::post('/organizaciones', [OrganizacionController::class, 'store'])
        ->name('organizaciones.store');
    //salas

    Route::get('/salas/create', [SalasController::class, 'create'])
        ->name('salas.create');
        
    Route::get('/salas/creates', [SalasController::class, 'creates'])
        ->name('salas.creates');

        Route::get('/salas/{sala}', [SalaController::class, 'show'])
    ->name('salas.show');

    
    Route::get('/salas/creates', [SalasController::class, 'creates'])
        ->name('cultivos.show');    

    Route::post('/salas', [SalasController::class, 'store'])
        ->name('organizaciones.store');

    //Cultivos

    Route::get('/cultivos/create', [CultivosController::class, 'create'])
        ->name('cultivos.create');

    Route::post('/cultivos', [SalasController::class, 'store'])
        ->name('cultivos.store');

    //Eventos

    //Registros

    //cultivoVariedad

    //CatalogoVariedad

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
