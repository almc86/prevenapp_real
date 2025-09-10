<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class TipoCobroController extends Controller
{
    public function index()
    {
        // Lista (luego hacemos el CRUD completo)
        return view('admin.tipos_cobro.index');
    }

    public function create()
    {
        // Formulario de creación
        return view('admin.tipos_cobro.create');
    }
}
