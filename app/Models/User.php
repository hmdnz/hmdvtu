<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstName',
        'lastName',
        'username',
        'verifiedName',
        'dob',
        'gender',
        'email',
        'phone',
        'phone2',
        'nin',
        'bvn',
        'bvn_verified',
        'accountName',
        'bankCode',
        'state',
        'lga',
        'address',
        'pin',
        'password',
        'role',
        'picture',
        'isVerified',
        'email_verified_at',
        'token',
        'generated_at',
        'referralID',
        'status',
    ];

    public function energyCustomers()
    {
        return $this->hasMany(EnergyCustomer::class)
        ->whereNotNull('status');
    }

    public function cableCustomers()
    {
        return $this->hasMany(CableCustomer::class)
        ->whereNotNull('status');
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class,"userID")
        ->whereNotNull('status');
    }

    public function virtualAccounts()
    {
        return $this->hasMany(VirtualAccount::class)
        ->whereNotNull('status');
    }

    public function referrals()
    {
        return $this->hasMany(Referrals::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'userID')
        ->whereNotNull('status');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'userID')
        ->whereNotNull('status');
    }

    public function supportRequests()
    {
        return $this->hasMany(SupportRequest::class);
    }

    public function userLogs()
    {
        return $this->hasMany(UserLogs::class, 'userID')
        ->whereNotNull('status');
    }

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
    ];
}
