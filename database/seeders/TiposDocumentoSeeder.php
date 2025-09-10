<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoDocumento;

class TiposDocumentoSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['trabajador','empresa','flota'] as $nombre) {
            TipoDocumento::updateOrCreate(
                ['nombre' => $nombre],
                ['descripcion' => null, 'estado' => true]
            );
        }
    }
}
