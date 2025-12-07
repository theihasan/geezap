<?php

namespace App\Models;

use App\Enums\Role;
use App\Observers\UserObserver;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use MeShaon\RequestAnalytics\Contracts\CanAccessAnalyticsDashboard;

#[ObservedBy([UserObserver::class])]
class User extends Authenticatable implements FilamentUser, CanAccessAnalyticsDashboard
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name','email','address','dob','state','country','occupation','postcode','phone','website','password',
        'bio','profile_image','facebook','twitter','linkedin','github','skills','locale','timezone','experience','role','facebook_id',
        'facebook_token','google_id','google_token','github_id','github_token','last_login_at',
        'onboarding_completed_at','profile_completion_score',
    ];

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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'dob' => 'datetime',
            'experience' => 'array',
            'skills' => 'array',
            'role' => Role::class,
            'last_login_at' => 'datetime',
            'onboarding_completed_at' => 'datetime',
        ];
    }

    public function jobs(): BelongsToMany
    {

        return $this->belongsToMany(JobListing::class, 'job_user', 'user_id', 'job_id')
            ->withTimestamps();
    }


    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === Role::ADMIN;
    }

    /**
     * Get the user's preferences.
     */
    public function preferences(): HasOne
    {
        return $this->hasOne(UserPreference::class);
    }

    /**
     * Get the user's preferences.
     */
    public function userPreference(): HasOne
    {
        return $this->hasOne(UserPreference::class);
    }

    /**
     * Get user preferences with defaults if none exist.
     */
    public function getPreferencesWithDefaults()
    {
        return $this->preferences ?: new UserPreference([
            'show_recommendations' => true,
            'email_notifications_enabled' => true,
            'remote_only' => false,
        ]);
    }
    
    public function canAccessAnalyticsDashboard(): bool
      {
         return true;
      }

    /**
     * Get the user's profile image URL
     *
     * @return string|null
     */
    public function getProfileImageUrlAttribute(): ?string
    {
        if (!$this->profile_image) {
            return null;
        }

        return \Storage::disk('public')->url($this->profile_image);
    }

    /**
     * Get profile image or default placeholder
     *
     * @return string
     */
    public function getProfileImageOrDefaultAttribute(): string
    {
        return $this->profile_image_url ?? asset('assets/images/profile.jpg');
    }
}
