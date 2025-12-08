<?php

namespace App\Models;

use App\Models\Role;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Atributos asignables en masa.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'failed_attempts',
        'locked_until',
        'last_activity_at',
    ];

    /**
     * Atributos ocultos al serializar (arrays/JSON).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts/transformaciones de atributos.
     * (Estilo Laravel 11 con método casts()).
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'privacy_accepted_at' => 'datetime',
            'locked_until'      => 'datetime',
            'last_activity_at'  => 'datetime',
        ];
    }

    /**
     * Relaciones
     * Un usuario pertenece a un Rol.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Helpers de autorización por rol.
     */
    public function isAdmin(): bool
    {
        return ($this->role?->name === 'ADMIN');
    }

    public function isNurse(): bool
    {
        return ($this->role?->name === 'ENFERMERIA');
    }

    /**
     * Accessor conveniente para leer el nombre del rol:
     * $user->role_name
     */
    public function getRoleNameAttribute(): ?string
    {
        return $this->role?->name;
    }

    /**
     * Mutator para normalizar emails (evita duplicados por mayúsculas/espacios).
     */
    public function setEmailAttribute(string $value): void
    {
        $this->attributes['email'] = mb_strtolower(trim($value));
    }
}
