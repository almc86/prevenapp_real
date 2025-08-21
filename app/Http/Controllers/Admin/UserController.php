<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Empresa; // <-- importa Empresa
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role; // <-- usa Spatie
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    public function index(Request $request)
    {
        $q        = trim((string) $request->input('q'));
        $roleId   = $request->input('role_id');   // id de roles (Spatie)
        $estado   = $request->input('estado');    // '1' | '0' | null
        $perPage  = (int) $request->input('per_page', 15);
        if (!in_array($perPage, [10,15,25,50,100], true)) $perPage = 15;

        $usuarios = User::query()
            ->with('roles')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->when($roleId, function ($query) use ($roleId) {
                $query->whereHas('roles', function ($r) use ($roleId) {
                    $r->where('id', $roleId);
                });
            })
            ->when($estado !== null && $estado !== '', function ($query) use ($estado) {
                $query->where('activo', (int) $estado);
            })
            ->orderBy('name')
            ->paginate($perPage)
            ->appends($request->query());

        $roles = Role::orderBy('name')->get(['id','name']);

        return view('admin.usuarios.index', compact('usuarios','roles','q','roleId','estado','perPage'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get(['id','name']);
        $empresas = Empresa::orderBy('nombre_empresa')->get(['id','nombre_empresa']);

        return view('admin.usuarios.create', compact('roles', 'empresas'));
    }

    public function store(Request $request)
    {
        // Validación base
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'role_id'  => 'required|exists:roles,id',
        ]);

        // Determinar rol (Spatie)
        $role      = Role::findById($request->role_id, 'web');
        $roleName  = $role->name; // 'principal', 'contratista', 'sub contratista', 'prevencionista', 'visualizador'

        // Normalizamos nombres de rol para comparar (por si hay espacios o mayúsculas)
        $rol = strtolower(trim($roleName));

        // Validaciones condicionales
        if (in_array($rol, ['principal','visualizador'])) {
            $request->validate([
                'empresas_principales'   => 'required|array|min:1',
                'empresas_principales.*' => 'exists:empresas,id',
            ]);
        }

        if (in_array($rol, ['contratista','sub contratista','subcontratista','prevencionista'])) {
            $request->validate([
                'empresas_principales'      => 'nullable|array',
                'empresas_principales.*'    => 'exists:empresas,id',
                'empresas_contratistas'     => 'nullable|array',
                'empresas_contratistas.*'   => 'exists:empresas,id',
                'empresas_subcontratistas'  => 'nullable|array',
                'empresas_subcontratistas.*'=> 'exists:empresas,id',
            ]);
        }

        // Campos extra para prevencionista
        $firmaPath = null;
        if ($rol === 'prevencionista') {
            $request->validate([
                'seremi_registro' => 'required|string|max:255',
                'firma'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);
            if ($request->hasFile('firma')) {
                $firmaPath = $request->file('firma')->store('firmas', 'public'); // storage/app/public/firmas
            }
        }

        // Crear usuario
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => $request->role_id, // opcional si mantienes esta columna
            'activo'   => true,
        ]);

        // Si quieres evitar fillable, asigna puntualmente (por si no agregaste a $fillable)
        if ($rol === 'prevencionista') {
            $user->seremi_registro = $request->input('seremi_registro'); // asegúrate de tener esta columna
            $user->firma_path      = $firmaPath;                          // asegúrate de tener esta columna
            $user->save();
        }

        // Rol de Spatie
        $user->syncRoles([$roleName]);

        // Asociaciones a empresas (pivot con 'relacion')
        $attach = [];

        // Principales (para todos los roles que lo permiten)
        foreach ((array) $request->input('empresas_principales', []) as $id) {
            $attach[$id] = ['relacion' => 'principal'];
        }
        // Contratistas
        foreach ((array) $request->input('empresas_contratistas', []) as $id) {
            $attach[$id] = ['relacion' => 'contratista'];
        }
        // Subcontratistas
        foreach ((array) $request->input('empresas_subcontratistas', []) as $id) {
            $attach[$id] = ['relacion' => 'subcontratista'];
        }

        // Si el rol es 'principal' o 'visualizador', solo usaremos el grupo de 'principales' (ya cargado arriba)

        // Persistir relaciones (reemplaza por completo)
        $user->empresas()->sync($attach);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario creado correctamente');
    }

    public function edit($id)
    {
        $usuario   = User::with(['roles','empresas'])->findOrFail($id);
        $roles     = Role::orderBy('name')->get(['id','name']);
        $empresas  = Empresa::orderBy('nombre_empresa')->get(['id','nombre_empresa']);

        // Primer rol del usuario (si usas un solo rol por usuario)
        $roleSelectedId = optional($usuario->roles->first())->id;

        // IDs de empresas ya asociadas, separados por tipo de relación en el pivot
        $principalesSel = $usuario->empresas()
            ->wherePivot('relacion','principal')
            ->pluck('empresas.id')->toArray();

        $contratistasSel = $usuario->empresas()
            ->wherePivot('relacion','contratista')
            ->pluck('empresas.id')->toArray();

        $subcontratistasSel = $usuario->empresas()
            ->wherePivot('relacion','subcontratista')
            ->pluck('empresas.id')->toArray();

        return view('admin.usuarios.edit', compact(
            'usuario','roles','empresas','roleSelectedId',
            'principalesSel','contratistasSel','subcontratistasSel'
        ));
    }

    public function update(Request $request, $id)
    {
        $usuario = User::with('roles','empresas')->findOrFail($id);

        // Validación base
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email,'.$usuario->id,
            'role_id'  => 'required|exists:roles,id',
            'password' => 'nullable|confirmed|min:6',
        ]);

        // Determinar rol
        $role     = Role::findById($request->role_id, 'web');
        $roleName = $role->name;
        $rol      = strtolower(trim($roleName));

        // Validaciones condicionales (mismas reglas que en create)
        if (in_array($rol, ['principal','visualizador'])) {
            $request->validate([
                'empresas_principales'   => 'required|array|min:1',
                'empresas_principales.*' => 'exists:empresas,id',
            ]);
        }

        if (in_array($rol, ['contratista','sub contratista','subcontratista','prevencionista'])) {
            $request->validate([
                'empresas_principales'       => 'nullable|array',
                'empresas_principales.*'     => 'exists:empresas,id',
                'empresas_contratistas'      => 'nullable|array',
                'empresas_contratistas.*'    => 'exists:empresas,id',
                'empresas_subcontratistas'   => 'nullable|array',
                'empresas_subcontratistas.*' => 'exists:empresas,id',
            ]);
        }

        // Campos extra para prevencionista
        $firmaPath = null;
        if ($rol === 'prevencionista') {
            $request->validate([
                'seremi_registro' => 'required|string|max:255',
                'firma'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'remove_firma'    => 'nullable|boolean',
            ]);

            // Reemplazo/borra firma
            if ($request->hasFile('firma')) {
                if ($usuario->firma_path) {
                    Storage::disk('public')->delete($usuario->firma_path);
                }
                $firmaPath = $request->file('firma')->store('firmas', 'public');
                $usuario->firma_path = $firmaPath;
            } elseif ($request->boolean('remove_firma')) {
                if ($usuario->firma_path) {
                    Storage::disk('public')->delete($usuario->firma_path);
                }
                $usuario->firma_path = null;
            }

            $usuario->seremi_registro = $request->input('seremi_registro');
        } else {
            // Si dejó de ser prevencionista, opcionalmente limpia estos campos:
            // $usuario->seremi_registro = null;
            // if ($usuario->firma_path) { Storage::disk('public')->delete($usuario->firma_path); }
            // $usuario->firma_path = null;
        }

        // Datos base
        $usuario->name  = $request->name;
        $usuario->email = $request->email;

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->role_id = $request->role_id; // si mantienes esta columna
        $usuario->save();

        // Actualizar rol de Spatie
        $usuario->syncRoles([$roleName]);

        // Armar asociaciones de empresas por tipo
        $attach = [];

        // Principales (requerido en principal/visualizador)
        foreach ((array) $request->input('empresas_principales', []) as $idEmp) {
            $attach[$idEmp] = ['relacion' => 'principal'];
        }

        // Contratistas / Subcontratistas (solo para roles que corresponden)
        if (in_array($rol, ['contratista','sub contratista','subcontratista','prevencionista'])) {
            foreach ((array) $request->input('empresas_contratistas', []) as $idEmp) {
                $attach[$idEmp] = ['relacion' => 'contratista'];
            }
            foreach ((array) $request->input('empresas_subcontratistas', []) as $idEmp) {
                $attach[$idEmp] = ['relacion' => 'subcontratista'];
            }
        }

        // Si es principal/visualizador, sólo se consideran 'principales' (ya cargado arriba)
        $usuario->empresas()->sync($attach);

        return redirect()->route('admin.usuarios.index')
            ->with('success', 'Usuario actualizado correctamente');
    }


    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->activo = ! $usuario->activo;
        $usuario->save();

        return redirect()->route('admin.usuarios.index')->with('success', 'Estado del usuario actualizado');
    }
}
