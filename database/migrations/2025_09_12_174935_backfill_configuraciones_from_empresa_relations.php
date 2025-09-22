<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
  public function up(): void {
    DB::transaction(function () {
      // 1) una configuración inicial por empresa que tenga registros
      $empresas = DB::table('empresa_categoria')->distinct()->pluck('empresa_id');
      foreach ($empresas as $empresaId) {
        $cfgId = DB::table('configuraciones')->insertGetId([
          'empresa_id' => $empresaId,
          'nombre'     => 'Configuración inicial',
          'descripcion'=> 'Migrada automáticamente',
          'estado'     => 1,
          'created_by' => null,
          'created_at' => now(),
          'updated_at' => now(),
        ]);

        // 2) categorías
        $cats = DB::table('empresa_categoria')->where('empresa_id',$empresaId)->get();
        foreach ($cats as $c) {
          DB::table('configuracion_categoria')->updateOrInsert(
            ['configuracion_id'=>$cfgId,'categoria_id'=>$c->categoria_id],
            ['estado'=>$c->estado ?? 1,'updated_at'=>now(),'created_at'=>now()]
          );
        }

        // 3) documentos asociados a esas categorías
        $docs = DB::table('empresa_categoria_documento')->where('empresa_id',$empresaId)->get();
        foreach ($docs as $d) {
          $newId = DB::table('configuracion_categoria_documento')->insertGetId([
            'configuracion_id'  => $cfgId,
            'categoria_id'      => $d->categoria_id,
            'documento_id'      => $d->documento_id,
            'obligatorio'       => (int)($d->obligatorio ?? 0),
            'vencimiento_modo'  => $d->vencimiento_modo ?? 'por_documento',
            'meses_vencimiento' => $d->meses_vencimiento,
            'plantilla_path'    => $d->plantilla_path,
            'estado'            => (int)($d->estado ?? 1),
            'created_at'        => now(),
            'updated_at'        => now(),
          ]);

          // Si tenías items en empresa_cat_doc_items, podrías copiarlos aquí.
          // DB::table('empresa_cat_doc_items')->where('empresa_categoria_documento_id', $d->id)->get();
          // ... e insertarlos en configuracion_cat_doc_items usando $newId
        }
      }
    });
  }
  public function down(): void {
    // si necesitas revertir, podrías borrar todo lo creado
    DB::table('configuracion_cat_doc_items')->delete();
    DB::table('configuracion_categoria_documento')->delete();
    DB::table('configuracion_categoria')->delete();
    DB::table('configuraciones')->where('descripcion','Migrada automáticamente')->delete();
  }
};
