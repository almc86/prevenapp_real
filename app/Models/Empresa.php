<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable = [
        'rut_empresa',
        'nombre_empresa',
        'correo_empresa',
        'telefono',
        'rut_representante',
        'nombre_representante',
        'correo_representante',
        'region_id',
        'comuna_id',
        'direccion',
        'logo_path',
    ];

    public function usuarios()
    {
        return $this->belongsToMany(User::class)
            ->withPivot('relacion') // principal, contratista, subcontratista
            ->withTimestamps();
    }

    public function region()
    {
        return $this->belongsTo(\App\Models\Region::class, 'region_id');
    }

    public function comuna()
    {
        return $this->belongsTo(\App\Models\Comuna::class, 'comuna_id');
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path ? Storage::url($this->logo_path) : null;
    }
    public function setRutEmpresaAttribute($value)
    {
        $this->attributes['rut_empresa'] = self::formatRut($value);
    }
    public function setRutRepresentanteAttribute($value)
    {
        $this->attributes['rut_representante'] = self::formatRut($value);
    }
    public static function cleanRut(?string $rut): string
    {
        return strtoupper(preg_replace('/[^0-9kK]/', '', (string)$rut));
    }
    public static function dvFor(string $num): string
    {
        $suma = 0; $mul = 2;
        for ($i = strlen($num) - 1; $i >= 0; $i--) {
            $suma += intval($num[$i]) * $mul;
            $mul = $mul === 7 ? 2 : $mul + 1;
        }
        $res = 11 - ($suma % 11);
        return $res === 11 ? '0' : ($res === 10 ? 'K' : (string)$res);
    }

    public static function validRut(?string $rut): bool
    {
        $rut = self::cleanRut($rut);
        if (strlen($rut) < 2) return false;
        $cuerpo = substr($rut, 0, -1);
        $dv = substr($rut, -1);
        if (!ctype_digit($cuerpo)) return false;
        return self::dvFor($cuerpo) === $dv;
    }

    public static function formatRut(?string $rut): ?string
    {
        if (!$rut) return null;
        $rut = self::cleanRut($rut);
        if (strlen($rut) < 2) return $rut;

        $cuerpo = substr($rut, 0, -1);
        $dv     = substr($rut, -1);

        // agregar puntos cada 3 desde la derecha
        $out = '';
        $cnt = 0;
        for ($i = strlen($cuerpo) - 1; $i >= 0; $i--) {
            $out = $cuerpo[$i] . $out;
            $cnt++;
            if ($cnt === 3 && $i > 0) { $out = '.' . $out; $cnt = 0; }
        }
        return $out . '-' . $dv;
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'empresa_user')
            ->withPivot('relacion')
            ->withTimestamps();
    }

}
