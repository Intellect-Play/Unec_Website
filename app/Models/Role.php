<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AdminUser;
use App\Models\Permission;

class Role extends Model
{
    // use HasFactory;
    protected $fillable = ['name'];

    // İlişki: Role -> AdminUsers
    public function adminUsers()
    {
        return $this->hasMany(AdminUser::class);
    }

    // İlişki: Role -> Permissions (çoktan çoğa)
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }
}
