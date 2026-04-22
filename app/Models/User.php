<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'nip',
        'password',
        'role',
        'is_admin',
        'profile_photo',
        'division',
        'phone',
        'address',
        'birth_date',
        'gender',
        'google_id',
        'avatar',
        'provider',
        'theme',
        'default_page',
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
            'is_admin' => 'boolean',
            'task_completed' => 'boolean',
            'birth_date' => 'date',
        ];
    }

    public function journals(): HasMany
    {
        return $this->hasMany(Journal::class);
    }

    public function isAdmin(): bool
    {
        return $this->is_admin || $this->role === 'admin';
    }

    public function pushSubscriptions(): HasMany
    {
        return $this->hasMany(PushSubscription::class);
    }

    public function routeNotificationForWebPush($notification = null)
    {
        // Return subscriptions in the format expected by the webpush channel.
        // When the package is installed, it will work with this relation.
        return $this->pushSubscriptions;
    }

    public function updatePushSubscription(string $endpoint, ?string $key, ?string $token, ?string $encoding = 'aesgcm'): void
    {
        $this->pushSubscriptions()->updateOrCreate(
            ['endpoint' => $endpoint],
            ['public_key' => $key, 'auth_token' => $token, 'content_encoding' => $encoding]
        );
    }

    public function deletePushSubscription(string $endpoint): void
    {
        $this->pushSubscriptions()->where('endpoint', $endpoint)->delete();
    }
}
