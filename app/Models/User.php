<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Mail\RecuperarClave;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Concerns\PerteneceACuenta;


class User extends Authenticatable
{
     use HasRoles, Notifiable, HasFactory, PerteneceACuenta;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'cuenta_id',
        'activo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'es_super_admin' => 'boolean',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function isActive()
    {
        return $this->activo;
    }

    public function empresas()
    {
        return $this->belongsToMany(Empresa::class, 'empresa_user')
            ->withPivot('relacion')
            ->withTimestamps();
    }

    /**
     * Override del mail de recuperación de contraseña: usa el template propio
     * en español (RecuperarClave) en vez de la notificación default de Laravel.
     */
    public function sendPasswordResetNotification($token): void
    {
        $resetUrl = route('password.reset', [
            'token' => $token,
            'email' => $this->getEmailForPasswordReset(),
        ]);

        Mail::to($this->getEmailForPasswordReset())
            ->send(new RecuperarClave($this->name ?? '', $resetUrl));
    }

}
