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
        // 1) Validación base
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'role_id'  => 'required|exists:roles,id',
        ]);

        // 2) Determinar rol (Spatie) y normalizar clave de comparación
        $role     = Role::findById($request->role_id, 'web');
        $roleName = $role->name;                  // p.ej.: 'principal', 'contratista', 'sub contratista', 'prevencionista', 'visualizador'
        $rolKey   = strtolower(str_replace(' ', '', trim($roleName))); // 'sub contratista' -> 'subcontratista'

        // 3) Validaciones condicionales por rol
        if (in_array($rolKey, ['principal','visualizador'], true)) {
            $request->validate([
                'empresas_principales'   => 'required|array|min:1',
                'empresas_principales.*' => 'exists:empresas,id',
            ]);
        }

        if (in_array($rolKey, ['contratista','subcontratista','prevencionista'], true)) {
            $request->validate([
                'empresas_principales'       => 'nullable|array',
                'empresas_principales.*'     => 'exists:empresas,id',
                'empresas_contratistas'      => 'nullable|array',
                'empresas_contratistas.*'    => 'exists:empresas,id',
                'empresas_subcontratistas'   => 'nullable|array',
                'empresas_subcontratistas.*' => 'exists:empresas,id',
            ]);
        }

        // 4) Campos extra para prevencionista
        $firmaPath = null;
        if ($rolKey === 'prevencionista') {
            $request->validate([
                'seremi_registro' => 'required|string|max:255',
                'firma'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);
            if ($request->hasFile('firma')) {
                $firmaPath = $request->file('firma')->store('firmas', 'public');
            }
        }

        // 5) Crear usuario
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => $request->role_id, // si mantienes esta columna
            'activo'   => true,
        ]);

        if ($rolKey === 'prevencionista') {
            $user->seremi_registro = $request->input('seremi_registro');
            $user->firma_path      = $firmaPath;
            $user->save();
        }

        // 6) Rol de Spatie
        $user->syncRoles([$roleName]);

        // 7) Armar asociaciones a empresas (pivot 'relacion')
        $attach = [];

        foreach ((array) $request->input('empresas_principales', []) as $id) {
            $attach[$id] = ['relacion' => 'principal'];
        }
        foreach ((array) $request->input('empresas_contratistas', []) as $id) {
            $attach[$id] = ['relacion' => 'contratista'];
        }
        foreach ((array) $request->input('empresas_subcontratistas', []) as $id) {
            $attach[$id] = ['relacion' => 'subcontratista'];
        }

        // 8) Sincronizar SOLO si el rol usa asociaciones
        $rolesConEmpresas = ['principal','visualizador','contratista','subcontratista','prevencionista'];
        if (in_array($rolKey, $rolesConEmpresas, true)) {
            $user->empresas()->sync($attach);
        }

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

        // 1) Validación base
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email,'.$usuario->id,
            'role_id'  => 'required|exists:roles,id',
            'password' => 'nullable|confirmed|min:6',
        ]);

        // 2) Determinar rol (Spatie) y normalizar clave
        $role     = Role::findById($request->role_id, 'web');
        $roleName = $role->name;
        $rolKey   = strtolower(str_replace(' ', '', trim($roleName)));

        // 3) Validaciones condicionales por rol
        if (in_array($rolKey, ['principal','visualizador'], true)) {
            $request->validate([
                'empresas_principales'   => 'required|array|min:1',
                'empresas_principales.*' => 'exists:empresas,id',
            ]);
        }

        if (in_array($rolKey, ['contratista','subcontratista','prevencionista'], true)) {
            $request->validate([
                'empresas_principales'       => 'nullable|array',
                'empresas_principales.*'     => 'exists:empresas,id',
                'empresas_contratistas'      => 'nullable|array',
                'empresas_contratistas.*'    => 'exists:empresas,id',
                'empresas_subcontratistas'   => 'nullable|array',
                'empresas_subcontratistas.*' => 'exists:empresas,id',
            ]);
        }

        // 4) Prevencionista: firma + SEREMI
        if ($rolKey === 'prevencionista') {
            $request->validate([
                'seremi_registro' => 'required|string|max:255',
                'firma'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'remove_firma'    => 'nullable|boolean',
            ]);

            if ($request->hasFile('firma')) {
                if ($usuario->firma_path) {
                    Storage::disk('public')->delete($usuario->firma_path);
                }
                $usuario->firma_path = $request->file('firma')->store('firmas', 'public');
            } elseif ($request->boolean('remove_firma')) {
                if ($usuario->firma_path) {
                    Storage::disk('public')->delete($usuario->firma_path);
                }
                $usuario->firma_path = null;
            }

            $usuario->seremi_registro = $request->input('seremi_registro');
        } else {
            // Si quieres limpiar al cambiar de rol, descomenta:
            // if ($usuario->firma_path) Storage::disk('public')->delete($usuario->firma_path);
            // $usuario->firma_path = null;
            // $usuario->seremi_registro = null;
        }

        // 5) Datos base
        $usuario->name  = $request->name;
        $usuario->email = $request->email;

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->role_id = $request->role_id; // si mantienes esta columna
        $usuario->save();

        // 6) Rol de Spatie (¡ojo con la variable!)
        $usuario->syncRoles([$roleName]);

        // 7) Armar asociaciones a empresas (pivot 'relacion')
        $attach = [];

        foreach ((array) $request->input('empresas_principales', []) as $id) {
            $attach[$id] = ['relacion' => 'principal'];
        }
        if (in_array($rolKey, ['contratista','subcontratista','prevencionista'], true)) {
            foreach ((array) $request->input('empresas_contratistas', []) as $id) {
                $attach[$id] = ['relacion' => 'contratista'];
            }
            foreach ((array) $request->input('empresas_subcontratistas', []) as $id) {
                $attach[$id] = ['relacion' => 'subcontratista'];
            }
        }

        // 8) Sincronizar SOLO si el rol usa asociaciones
        $rolesConEmpresas = ['principal','visualizador','contratista','subcontratista','prevencionista'];
        if (in_array($rolKey, $rolesConEmpresas, true)) {
            $usuario->empresas()->sync($attach);
        } else {
            // Si cambió a un rol sin empresas y quieres limpiar, descomenta:
            // $usuario->empresas()->sync([]);
        }

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
