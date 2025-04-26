<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;
    protected $fillable = [
        'firstName',
        'lastName',
        'email',
        'phone',
        'password',
        'role',
        'email_verified_at',
        'status',
    ];

    public function billers()
    {
        return $this->hasMany(Biller::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    public function requestCategories()
    {
        return $this->hasMany(RequestCategory::class);
    }
    // Other model properties and methods

    // Implement the required methods from Authenticatable
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }
}

