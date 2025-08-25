<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the roles that belong to the user.
     */
    public function roles()
    {
        // Pastikan relasi menggunakan tabel pivot yang benar
        return $this->belongsToMany(Role::class, 'user_has_roles');
    }

    /**
     * Get the permissions that belong to the user.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Check if the user has a specific role.
     */
    public function hasRole($roleName)
    {
        // Ambil koleksi roles dari relasi
        $roles = $this->roles;
        if (is_string($roleName)) {
            return $roles->contains('name', $roleName);
        }
        if (is_array($roleName)) {
            return $roles->whereIn('name', $roleName)->isNotEmpty();
        }
        return false;
    }

    /**
     * Check if the user has permission through any of their roles.
     */
    public function hasPermission($permissionName)
    {
        // Cek permission langsung
        if ($this->permissions->contains('name', $permissionName)) {
            return true;
        }

        // Cek permission melalui roles
        foreach ($this->roles as $role) {
            if ($role->permissions->contains('name', $permissionName)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Cek apakah user memiliki salah satu dari beberapa role.
     * @param array|string $roles
     * @return bool
     */
    public function hasAnyRole($roles)
    {
        if (is_string($roles)) {
            $roles = func_get_args();
        }
        return $this->roles()->whereIn('name', $roles)->exists();
    }
}
