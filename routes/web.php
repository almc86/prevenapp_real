<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\Admin\EmpresaController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middlewares\RoleMiddleware;
use App\Http\Controllers\Admin\ConfiguracionController;
use App\Http\Controllers\Admin\TipoDocumentoController;
use App\Http\Controllers\Admin\DocumentoController;
use App\Http\Controllers\Admin\CategoriaController;
use App\Http\Controllers\Admin\CargoController;
use App\Http\Controllers\Admin\MarcaFlotaController;
use App\Http\Controllers\Admin\FeriadoController;


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

        Route::get('configuracion', [ConfiguracionController::class, 'index'])
            ->name('config.index');

        Route::resource('cargos', App\Http\Controllers\Admin\CargoController::class)
        ->only(['index','create','store']);

        Route::resource('tipos-documento', TipoDocumentoController::class)->only(['index','create','store','edit','update']);
        Route::resource('documentos',        DocumentoController::class)->only(['index','create','store']);
        Route::resource('categorias',        CategoriaController::class)->only(['index','create','store']);
        Route::resource('marcas-flota',      MarcaFlotaController::class)->only(['index','create','store']);
        Route::resource('feriados',          FeriadoController::class)->only(['index', 'create', 'store']);
    });


require __DIR__.'/auth.php';
