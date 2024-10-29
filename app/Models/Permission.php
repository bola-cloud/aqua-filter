<?php

namespace App\Models;

use Laratrust\Models\Permission as PermissionModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends PermissionModel
{
    public $guarded = [];

    /**
     * The roles that belong to the permission.
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Role::class, 'permission_role');
    }

    /**
     * The users that belong to the permission.
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(\App\Models\User::class, 'permission_user')
            ->withPivot('user_type'); // Optional if using polymorphic relationships
    }
}
