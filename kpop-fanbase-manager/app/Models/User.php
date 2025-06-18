<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'birth_date',
        'profile_picture',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'birth_date' => 'date',
    ];

    public function extendedProfile()
    {
        return $this->hasOne(ExtendedProfile::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions');
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function participatingEvents()
    {
        return $this->belongsToMany(Event::class, 'event_participations')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function isAdmin()
    {
        return $this->type === 'admin';
    }

    public function isManager()
    {
        return $this->type === 'manager';
    }

    public function hasPermission($permissionSlug)
    {
        return $this->permissions()->where('slug', $permissionSlug)->exists() || 
               $this->isAdmin();
    }
}