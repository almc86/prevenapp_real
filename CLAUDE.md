# PrevenApp - Sistema de Validación de Documentos

## Información del Proyecto

**Framework:** Laravel 10
**PHP:** ^8.1
**Base de Datos:** MySQL
**Autenticación:** Laravel Breeze
**Permisos:** Spatie Laravel Permission

## Estructura del Proyecto

### Modelos Principales

1. **Empresa** (`app/Models/Empresa.php`)
   - Gestiona empresas principales, contratistas y subcontratistas
   - Relación many-to-many con usuarios via pivot table `empresa_user`
   - Campo `relacion` en pivot: 'principal', 'contratista', 'subcontratista'
   - Validación y formateo automático de RUT
   - Campos: rut_empresa, nombre_empresa, correo_empresa, telefono, representante, región, comuna, dirección, logo

2. **User** (`app/Models/User.php`)
   - Sistema de roles con Spatie Permission
   - Relación con empresas
   - Rol 'administrador' para acceso al panel admin

3. **Otros Modelos:**
   - `Cargo`: Maestro de cargos/puestos de trabajo
   - `Categoria`: Categorías de documentos (ej: "Mínimo Legal", "Trabajo en Altura")
   - `TipoDocumento`: Tipos de documentos del sistema
   - `Documento`: Documentos del repositorio
   - `Feriado`: Calendario de feriados
   - `Region`/`Comuna`: Geografía de Chile

### Controladores Admin

Ubicación: `app/Http/Controllers/Admin/`

- `ConfiguracionController`: Panel principal de configuración
- `EmpresaController`: CRUD de empresas
- `ConfigEmpresaController`: Configuración específica por empresa
- `TipoDocumentoController`: Gestión de tipos de documento
- `CategoriaController`: Gestión de categorías
- `DocumentoController`: Gestión de documentos
- `CargoController`: Gestión de cargos
- `FeriadoController`: Gestión de feriados
- `TipoCobroController`: **EN DESARROLLO** - Gestión de tipos de cobro

### Rutas

**Archivo:** `routes/web.php`

**Middleware:** Solo usuarios con rol 'administrador' pueden acceder a `/admin/*`

**Estructura de rutas admin:**
```php
Route::middleware(['auth','role:administrador'])
    ->prefix('admin')->name('admin.')
    ->group(function () {
        // Rutas de recursos CRUD
        Route::resource('empresas', EmpresaController::class);
        Route::resource('cargos', CargoController::class);
        Route::resource('tipos-documento', TipoDocumentoController::class);
        Route::resource('categorias', CategoriaController::class);
        // etc...
    });
```

### Vistas

**Layout principal:** `resources/views/layouts/app.blade.php`
**Panel de configuración:** `resources/views/admin/config/index.blade.php`

**Estructura de vistas admin:**
```
resources/views/admin/
├── config/
│   └── index.blade.php (Panel principal)
├── empresas/
├── cargos/
├── categorias/
├── tipos_documento/
├── documentos/
├── feriados/
└── config_empresas/
```

### Base de Datos

**Archivo de referencia:** `basededatos/scriptbdd.sql`

**Tablas principales:**
- `empresas`: Datos de empresas
- `empresa_user`: Relación empresas-usuarios con tipo de relación
- `users`: Usuarios del sistema
- `cargos`: Maestro de cargos (283 registros)
- `categorias`: Categorías de documentos
- `tipos_documento`: Tipos de documentos
- `documentos`: Repositorio de documentos
- `feriados`: Calendario de feriados
- `regiones`/`comunas`: Geografía chilena completa

## Módulo Tipo de Cobro (EN DESARROLLO)

### Lógica de Negocio

**Objetivo:** Configurar tipos de cobro por empresa según:

1. **Relación empresa:** Principal → Contratista/Subcontratista
2. **Tipo de cobro:** UF, Pesos
3. **Tipo de pago:** WebPay, Factura
4. **Rangos por cantidad de trabajadores:**
   - Ejemplo: 1-10 trabajadores → X monto
   - Ejemplo: 11-21 trabajadores → Y monto

### Estado Actual

- **Controlador:** `TipoCobroController` (básico)
- **Ruta:** `admin/tipos-cobro` (solo index, create)
- **Vistas:** Pendientes
- **Modelo:** Pendiente
- **Migración:** Pendiente

## Convenciones del Proyecto

### Nombres de Archivos y Rutas
- Controladores: `PascalCase` + `Controller.php`
- Modelos: `PascalCase.php`
- Vistas: `snake_case` en carpetas
- Rutas: `kebab-case`

### Estructura de Vistas
- Extienden `layouts.app`
- Sección `@section('title')` para títulos
- Sección `@section('content')` para contenido
- Bootstrap 5 para estilos
- Iconos Boxicons (`bx bx-*`)

### Patrones de Controladores CRUD
```php
public function index() // Lista
public function create() // Formulario crear
public function store(Request $request) // Guardar
public function edit($id) // Formulario editar
public function update(Request $request, $id) // Actualizar
public function destroy($id) // Eliminar
```

### Base de Datos
- Migraciones en `database/migrations/`
- Seeders en `database/seeders/`
- Nombres de tabla en plural, snake_case
- Primary keys: `id` bigint unsigned auto_increment
- Timestamps: `created_at`, `updated_at`

## Comandos Útiles

```bash
# Crear migración
php artisan make:migration create_table_name

# Crear modelo con migración
php artisan make:model ModelName -m

# Crear controlador resource
php artisan make:controller Admin/ControllerName --resource

# Ejecutar migraciones
php artisan migrate

# Limpiar caché
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

## Próximos Pasos para Tipo de Cobro

1. Crear migración para tabla `tipos_cobro` y `tipo_cobro_rangos`
2. Crear modelo `TipoCobro`
3. Completar controlador `TipoCobroController`
4. Crear vistas `admin/tipos_cobro/`
5. Actualizar rutas en `web.php`
6. Agregar validaciones y relaciones
