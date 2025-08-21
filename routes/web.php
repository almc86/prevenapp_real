<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\Admin\EmpresaController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middlewares\RoleMiddleware;


Route::get('/_check', fn() => 'ok')
  ->middleware(['auth','role:administrador']);

// Página de bienvenida
Route::get('/', function () {
    return view('welcome');
});

// Dashboard general
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Perfil de usuario autenticado
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth','role:administrador'])
    ->prefix('admin')->name('admin.')
    ->group(function () {
        Route::resource('usuarios', App\Http\Controllers\Admin\UserController::class);
        Route::resource('empresas', App\Http\Controllers\Admin\EmpresaController::class);
        Route::get('regiones/{region}/comunas', [EmpresaController::class,'comunasPorRegion'])
        ->name('regiones.comunas');
    });


require __DIR__.'/auth.php';
