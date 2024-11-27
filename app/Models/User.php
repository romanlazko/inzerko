<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Messanger\Thread;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Romanlazko\Telegram\Traits\HasBots;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;


class User extends Authenticatable implements HasMedia, MustVerifyEmail
{
    use HasApiTokens; 
    use HasFactory; 
    use Notifiable; 
    use HasBots; 
    use HasRoles; 
    use InteractsWithMedia; 
    use HasSlug; 
    use SoftDeletes;
    use Prunable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'avatar',
        'email',
        'communication_settings',
        'password',
        'telegram_chat_id',
        'notification_settings',
        'locale',
        'telegram_token'
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'notification_settings' => 'object',
        'communication_settings' => 'object',
    ];

    protected static function booted(): void
    {
        static::deleted(function (User $user) {
            $user->announcements()->delete();
            $user->threads()->delete();
            $user->votes()->delete();
            $user->chat()->delete();
        });

        static::forceDeleted(function (User $user) {
            $user->announcements()->forceDelete();
            $user->threads()->forceDelete();
            $user->votes()->forceDelete();
            $user->chat()->forceDelete();
        });

        static::restored(function (User $user) {
            $user->announcements()->restore();
            $user->threads()->restore();
            $user->votes()->restore();
            $user->chat()->restore();
        });
    }

    public function prunable(): Builder
    {
        return static::where('deleted_at', '<=', now()->subMonth());
    }

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->useFallbackUrl('/images/no-photo.jpg')
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->format('webp')
                    ->keepOriginalImageFormat()
                    ->width(100)
                    ->height(100);
            });
    }

    //RELATIONS

    public function chat(): BelongsTo
    {
        return $this->belongsTo(TelegramChat::class, 'telegram_chat_id', 'id');
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    public function threads(): BelongsToMany
    {
        return $this->belongsToMany(Thread::class)->withCount(['messages' => function ($query) {
            $query->where('read_at', null)
                ->where('user_id', '!=', $this->id);
        }]);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function wishlist(): BelongsToMany
    {
        return $this->belongsToMany(Announcement::class, 'votes')->where('vote', true);
    }

    public function tokens()
    {
        return $this->morphMany(AccessToken::class, 'tokenable');
    }

    //ATTRIBUTES

    public function getUnreadMessagesCountAttribute(): int
    {
        $unreadMessagesCount = Cookie::get('unreadMessagesCount');

        if (!$unreadMessagesCount) {
            $unreadMessagesCount = $this->threads
                ->pluck('messages_count')
                ->sum();

            Cookie::queue('unreadMessagesCount', $unreadMessagesCount, 2);
        }

        return $unreadMessagesCount;
    }

    public function getLanguagesAttribute(): array
    {
        return $this->communication_settings?->languages ?? [];
    }

    public function isProfileFilled(): bool
    {
        return ! is_null($this->communication_settings) AND ! empty($this->languages) AND ! is_null($this->name); 
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-duper-admin');
    }

    //NOTIFICATIONS

    public function sendEmailVerificationNotification(): void
    {
        if ($this instanceof MustVerifyEmail && ! $this->hasVerifiedEmail()) {
            $this->notify(new VerifyEmail);
        }
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    //METHODS

    public function createAccessToken(string $name): AccessToken
    {
        return $this->tokens()->create([
            'name' => $name,
            'token' => str()->random(60),
            'expires_at' => now()->addMinutes(60),
        ]);
    }

    public static function findByToken(string $token): ?User
    {
        return static::whereHas('tokens', function (Builder $query) use ($token) {
            $query->where('token', $token)->where('expires_at', '>=', now());
        })->first();
    }
}
