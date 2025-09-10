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
use App\Http\Controllers\Admin\ConfigEmpresaController;


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
        Route::resource('empresas', EmpresaController::class)->only(['index','create','store','edit','update','destroy']);
        Route::get('regiones/{region}/comunas', [EmpresaController::class,'comunasPorRegion'])
        ->name('regiones.comunas');

        Route::get('configuracion', [ConfiguracionController::class, 'index'])
            ->name('config.index');

        Route::resource('cargos', App\Http\Controllers\Admin\CargoController::class)
        ->only(['index','create','store']);

<<<<<<< HEAD
        Route::resource('tipos-documento', TipoDocumentoController::class)->only(['index','create','store','edit','update','destroy']);
        Route::resource('documentos', DocumentoController::class)->only(['index','create','store','edit','update','destroy']);
        Route::resource('categorias',        CategoriaController::class)->only(['index','create','store','edit','update','destroy']);
        Route::resource('marcas-flota',      MarcaFlotaController::class)->only(['index','create','store','edit','update','destroy']);
        Route::resource('feriados',          FeriadoController::class)->only(['index','create','store']);
        Route::resource('tipos-cobro', App\Http\Controllers\Admin\TipoCobroController::class)->only(['index','create']);

        Route::get('config-empresas', [ConfigEmpresaController::class,'index'])
            ->name('config-empresas.index');

        Route::get('config-empresas/{empresa}', [ConfigEmpresaController::class,'show'])
            ->name('config-empresas.show');

        Route::post('config-empresas/{empresa}/categoria', [ConfigEmpresaController::class,'storeCategoria'])
            ->name('config-empresas.categoria.store');

        Route::delete('config-empresas/{empresa}/categoria/{categoria}', [ConfigEmpresaController::class,'destroyCategoria'])
            ->name('config-empresas.categoria.destroy');

        Route::post('config-empresas/{empresa}/categoria/{categoria}/documento', [ConfigEmpresaController::class,'storeDocumento'])
            ->name('config-empresas.documento.store');

        Route::put('config-empresas/{empresa}/categoria/{categoria}/documento/{config}', [ConfigEmpresaController::class,'updateDocumento'])
            ->name('config-empresas.documento.update');

        Route::delete('config-empresas/{empresa}/categoria/{categoria}/documento/{config}', [ConfigEmpresaController::class,'destroyDocumento'])
            ->name('config-empresas.documento.destroy');

        Route::post('config-empresas/doc-config/{config}/items', [ConfigEmpresaController::class,'storeItem'])
            ->name('config-empresas.items.store');

        Route::put('config-empresas/doc-config/{config}/items/{item}', [ConfigEmpresaController::class,'updateItem'])
            ->name('config-empresas.items.update');

        Route::delete('config-empresas/doc-config/{config}/items/{item}', [ConfigEmpresaController::class,'destroyItem'])
            ->name('config-empresas.items.destroy');

=======
        Route::resource('tipos-documento', TipoDocumentoController::class)->only(['index','create','store','edit','update']);
        Route::resource('documentos',        DocumentoController::class)->only(['index','create','store']);
        Route::resource('categorias',        CategoriaController::class)->only(['index','create','store']);
        Route::resource('marcas-flota',      MarcaFlotaController::class)->only(['index','create','store']);
        Route::resource('feriados',          FeriadoController::class)->only(['index', 'create', 'store']);
>>>>>>> vicente_valdes
    });



require __DIR__.'/auth.php';
