<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Models\Empresa;
use App\Models\Region;
use App\Models\Comuna;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    /** GET /admin/empresas */
    public function index()
    {
        // Si ya migraste a region_id/comuna_id:
        $empresas = Empresa::with(['region','comuna'])
            ->orderBy('nombre_empresa')
            ->paginate(15);

        return view('admin.empresas.index', compact('empresas'));
    }

    /** GET /admin/empresas/create */
    public function create()
    {
        $regiones = Region::orderBy('nombre')->get(['id','nombre']);
        return view('admin.empresas.create', compact('regiones'));
    }

    /** Endpoint JSON: comunas por región */
    public function comunasPorRegion(Region $region)
    {
        return response()->json(
            $region->comunas()->orderBy('nombre')->get(['id','nombre'])
        );
    }

    /** POST /admin/empresas */
    public function store(Request $request)
    {
        $request->merge([
            'rut_empresa'        => $this->rutFormat($request->input('rut_empresa')),
            'rut_representante'  => $this->rutFormat($request->input('rut_representante')),
        ]);


        // 2) Validar (ya sobre el RUT normalizado)
        $request->validate([
            'rut_empresa' => [
                'required','string','max:255','unique:empresas,rut_empresa',
                function($attr, $value, $fail) {
                    if (! $this->rutValido($value)) {
                        $fail('El RUT de la empresa no es válido.');
                    }
                }
            ],
            'nombre_empresa'        => 'required|string|max:255',
            'rut_representante'     => [
                'required','string','max:255',
                function($attr, $value, $fail) {
                    if (! $this->rutValido($value)) {
                        $fail('El RUT del representante no es válido.');
                    }
                }
            ],
            'nombre_representante'  => 'required|string|max:255',
            'correo_representante'  => 'nullable|email|max:255',
            'correo_empresa'        => 'nullable|email|max:255',
            'telefono'              => 'nullable|string|max:255',
            'region_id'             => 'required|exists:regiones,id',
            'comuna_id'             => 'required|exists:comunas,id',
            'direccion'             => 'required|string|max:255',
            'logo'                  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // 3) Subida de logo (corrige nombre de variable)
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        // Tu código + logo_path
        // 4) Crear
        $data = $request->only([
            'rut_empresa','nombre_empresa','rut_representante','nombre_representante',
            'correo_representante','correo_empresa','telefono',
            'region_id','comuna_id','direccion',
        ]);
        $data['logo_path'] = $logoPath;

        Empresa::create($data);

        return redirect()->route('admin.empresas.index')->with('success','Empresa creada');
    }

    /** GET /admin/empresas/{empresa}/edit */
    public function edit(Empresa $empresa)
    {
        $regiones = Region::orderBy('nombre')->get(['id','nombre']);
        $comunas  = $empresa->region_id
            ? Comuna::where('region_id', $empresa->region_id)->orderBy('nombre')->get(['id','nombre'])
            : collect();

        return view('admin.empresas.edit', compact('empresa','regiones','comunas'));
    }

    /** PUT/PATCH /admin/empresas/{empresa} */
    public function update(Request $request, Empresa $empresa)
    {
        $request->merge([
            'rut_empresa'        => $this->rutFormat($request->input('rut_empresa')),
            'rut_representante'  => $this->rutFormat($request->input('rut_representante')),
        ]);


        // 2) Validación (unicidad ignorando el propio ID)
        $request->validate([
            'rut_empresa' => [
                'required','string','max:255','unique:empresas,rut_empresa,'.$empresa->id,
                function($attr, $value, $fail) {
                    if (! $this->rutValido($value)) {
                        $fail('El RUT de la empresa no es válido.');
                    }
                }
            ],
            'nombre_empresa'        => 'required|string|max:255',
            'rut_representante'     => [
                'required','string','max:255',
                function($attr, $value, $fail) {
                    if (! $this->rutValido($value)) {
                        $fail('El RUT del representante no es válido.');
                    }
                }
            ],
            'nombre_representante'  => 'required|string|max:255',
            'correo_representante'  => 'nullable|email|max:255',
            'correo_empresa'        => 'nullable|email|max:255',
            'telefono'              => 'nullable|string|max:255',
            'region_id'             => 'required|exists:regiones,id',
            'comuna_id'             => 'required|exists:comunas,id',
            'direccion'             => 'required|string|max:255',
            'logo'                  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // 3) Reemplazo de logo
        if ($request->hasFile('logo')) {
            if ($empresa->logo_path) {
                Storage::disk('public')->delete($empresa->logo_path);
            }
            $empresa->logo_path = $request->file('logo')->store('logos', 'public');
        }

        // 4) Actualizar resto
        $empresa->update($request->only([
            'rut_empresa','nombre_empresa','rut_representante','nombre_representante',
            'correo_representante','correo_empresa','telefono',
            'region_id','comuna_id','direccion'
        ]));

        return redirect()->route('admin.empresas.index')->with('success','Empresa actualizada');
    }

    /** DELETE /admin/empresas/{empresa} */
    public function destroy(Empresa $empresa)
    {
        $empresa->delete(); // si usas SoftDeletes, añadelo al modelo
        return redirect()->route('admin.empresas.index')->with('success','Empresa eliminada');
    }

    private function rutValido(?string $rut): bool
    {
        if (!$rut) return false;
        $rut = preg_replace('/[^0-9kK]/', '', $rut);
        if (strlen($rut) < 2) return false;
        $dv = strtoupper(substr($rut, -1));
        $num = substr($rut, 0, -1);
        if (!ctype_digit($num)) return false;

        $suma = 0; $mul = 2;
        for ($i = strlen($num) - 1; $i >= 0; $i--) {
            $suma += intval($num[$i]) * $mul;
            $mul = ($mul === 7) ? 2 : $mul + 1;
        }
        $resto = 11 - ($suma % 11);
        $dig = $resto === 11 ? '0' : ($resto === 10 ? 'K' : (string)$resto);
        return $dv === $dig;
    }

    private function rutFormat(?string $rut): ?string
    {
        if (!$rut) return null;
        $rut = preg_replace('/[^0-9kK]/', '', $rut); // limpia
        if (strlen($rut) < 2) return $rut;

        $cuerpo = substr($rut, 0, -1);
        $dv     = strtoupper(substr($rut, -1));

        // puntos cada 3 desde la derecha
        $out = ''; $cnt = 0;
        for ($i = strlen($cuerpo) - 1; $i >= 0; $i--) {
            $out = $cuerpo[$i] . $out;
            $cnt++;
            if ($cnt === 3 && $i > 0) { $out = '.' . $out; $cnt = 0; }
        }
        return $out . '-' . $dv;
    }
}
