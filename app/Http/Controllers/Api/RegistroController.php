<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\RegistroService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;

/**
 * Registro público consumido por el formulario del landing (Astro).
 * Crea la cuenta y responde JSON. NO loguea (el flujo es "registrate y luego
 * iniciá sesión"): el front redirige al login tras el OK.
 */
class RegistroController extends Controller
{
    public function store(Request $request, RegistroService $registro): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'empresa' => ['required', 'string', 'max:255'],
            'plan' => ['required', 'string', 'exists:planes,codigo'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $registro->crearCuenta($request->only(['name', 'empresa', 'plan', 'email', 'password']));

        return response()->json([
            'ok' => true,
            'message' => 'Cuenta creada con éxito',
        ]);
    }
}
