<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Marca recorridos guiados (onboarding) como vistos por el usuario, para no
 * volver a mostrarlos automáticamente. El front lo llama vía fetch al cerrar
 * o terminar el tour.
 */
class OnboardingController extends Controller
{
    public function completarDashboard(Request $request)
    {
        $user = $request->user();

        if ($user && is_null($user->tour_dashboard_visto_at)) {
            $user->forceFill(['tour_dashboard_visto_at' => now()])->save();
        }

        return response()->noContent(); // 204
    }
}
