<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use App\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasProfilePhoto, Notifiable, TwoFactorAuthenticatable, LaratrustUserTrait;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id')
            ->withPivot('user_type'); // Optional: 'user_type' if you are using polymorphic relationships
    }

    /**
     * Get the permissions that belong to the user.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_user', 'user_id', 'permission_id')
            ->withPivot('user_type'); // Optional if you use polymorphic relationships
    }

    /**
     * Check if the user has a specific role.
     */
    public function hasRole($role)
    {
        return $this->roles->contains('name', $role);
    }

    /**
     * Check if the user has a specific permission.
     */
    public function hasPermission($permission)
    {
        return $this->permissions->contains('name', $permission) ||
            $this->roles->pluck('permissions')->flatten()->contains('name', $permission);
    }

    /**
     * Assign a role to the user.
     */
    public function assignRole($role)
    {
        return $this->roles()->syncWithoutDetaching([$role->id]);
    }

    /**
     * Remove a role from the user.
     */
    public function removeRole($role)
    {
        return $this->roles()->detach($role->id);
    }

    /**
     * Attach a permission directly to the user.
     */
    public function attachPermission($permission)
    {
        return $this->permissions()->syncWithoutDetaching([
            $permission->id => ['user_type' => 'App\\Models\\User']
        ]);
    }
    

    /**
     * Detach a permission directly from the user.
     */
    public function detachPermission($permission)
    {
        return $this->permissions()->detach($permission->id);
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];
}
