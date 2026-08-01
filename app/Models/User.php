<?php

namespace App\Models;

use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'is_active' => 'boolean',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === UserRole::SuperAdmin;
    }

    public function isOrgHead(): bool
    {
        return $this->role === UserRole::OrgHead || $this->isSuperAdmin();
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, [UserRole::SuperAdmin, UserRole::OrgHead, UserRole::Admin]);
    }

    public function isStaff(): bool
    {
        return in_array($this->role, [UserRole::SuperAdmin, UserRole::OrgHead, UserRole::Admin, UserRole::Staff]);
    }

    public function isViewer(): bool
    {
        return $this->role === UserRole::Viewer;
    }
}
