<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\PerteneceACuenta;

class Cargo extends Model
{
    use PerteneceACuenta;

    protected $table = 'cargos';
    protected $fillable = ['nombre'];

    protected static function tenantCompartido(): bool
    {
        return true;
    }
}
