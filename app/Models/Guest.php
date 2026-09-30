<?php

namespace App\Models;

use App\Notifications\GuestResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Auththenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Guest extends Auththenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'avatar',
        'phone',
        'email',
        'address',
        'identification_number',
        'nation_id',
        'email_verified_at',
        'remember_token',
        'password',
        'investor_type',
        'interested_sectors',
        'organization_name',
        'tax_code',
        'status',
        'failed_login_attempts',
        'locked_until',
        'last_login_ip',
        'verification_token',
        'dashboard_layout',
    ];

    protected $casts = [
        'interested_sectors' => 'array',
        'locked_until' => 'datetime',
        'email_verified_at' => 'datetime',
        'dashboard_layout' => 'array',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'remember_token',
        'password',
        'email_verified_at',
    ];

    public function nation()
    {
        return $this->belongsTo(Nation::class, 'nation_id', 'id');
    }

    public function viewHistories()
    {
        return $this->hasMany(GuestViewHistory::class);
    }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            if (str_starts_with($this->avatar, '/') || str_starts_with($this->avatar, 'http')) {
                return $this->avatar;
            }
            return asset('storage/' . $this->avatar);
        }
        return null;
    }

    public function interests()
    {
        return $this->hasMany(Interest::class, 'guest_id', 'id');
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new GuestResetPasswordNotification($token));
    }
}
