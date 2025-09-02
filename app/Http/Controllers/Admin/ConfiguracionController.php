<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ConfiguracionController extends Controller
{
    public function index()
    {
        return view('admin.config.index');
    }
}
