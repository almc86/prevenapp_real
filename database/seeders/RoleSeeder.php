<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = ['administrador', 'empresa_principal', 'prevencionista', 'contratista', 'subcontratista'];

        foreach ($roles as $rol) {
            Role::firstOrCreate(['nombre' => $rol]);
        }
    }
}
