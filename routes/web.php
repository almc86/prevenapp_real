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

        Route::resource('tipos-documento', TipoDocumentoController::class)->only(['index','create','store','edit','update','destroy']);
        Route::resource('documentos', DocumentoController::class)->only(['index','create','store','edit','update','destroy']);
        Route::resource('categorias',        CategoriaController::class)->only(['index','create','store','edit','update','destroy']);
        Route::resource('marcas-flota',      MarcaFlotaController::class)->only(['index','create','store','edit','update','destroy']);
        Route::resource('feriados',          FeriadoController::class)->only(['index','create','store']);
        Route::resource('tipos-cobro', App\Http\Controllers\Admin\TipoCobroController::class);

        // Módulo de configuración de empresas
        Route::prefix('config-empresas')->name('config-empresas.')->group(function () {
            // 1) elegir empresa
            Route::get('/', [ConfigEmpresaController::class,'index'])->name('index');

            // 2) listar/crear configuraciones de una empresa
            Route::get('{empresa}/configuraciones', [ConfigEmpresaController::class,'configsIndex'])->name('configs.index');
            Route::post('{empresa}/configuraciones', [ConfigEmpresaController::class,'configsStore'])->name('configs.store');

            // 3) gestionar una configuración específica
            Route::get('{empresa}/configuraciones/{config}', [ConfigEmpresaController::class,'show'])->name('show');

            // categorías en config
            Route::post('{empresa}/configuraciones/{config}/categoria', [ConfigEmpresaController::class,'storeCategoria'])->name('categoria.store');
            Route::delete('{empresa}/configuraciones/{config}/categoria/{categoria}', [ConfigEmpresaController::class,'destroyCategoria'])->name('categoria.destroy');

            // documentos en categoría dentro de la config
            Route::post('{empresa}/configuraciones/{config}/categoria/{categoria}/documento', [ConfigEmpresaController::class,'storeDocumento'])->name('documento.store');
            Route::put('{empresa}/configuraciones/{config}/categoria/{categoria}/documento/{cfgdoc}', [ConfigEmpresaController::class,'updateDocumento'])->name('documento.update');
            Route::delete('{empresa}/configuraciones/{config}/categoria/{categoria}/documento/{cfgdoc}', [ConfigEmpresaController::class,'destroyDocumento'])->name('documento.destroy');

            // items de revisión
            Route::post('doc-config/{cfgdoc}/items', [ConfigEmpresaController::class,'storeItem'])->name('items.store');
            Route::put('doc-config/{cfgdoc}/items/{item}', [ConfigEmpresaController::class,'updateItem'])->name('items.update');
            Route::delete('doc-config/{cfgdoc}/items/{item}', [ConfigEmpresaController::class,'destroyItem'])->name('items.destroy');
        });


    });



require __DIR__.'/auth.php';
