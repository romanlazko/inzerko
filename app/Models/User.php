<?php

namespace App\Models;

use App\Enums\ContactTypeEnum;
use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Messanger\Thread;
use App\Models\Traits\Tokenable;
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
use Laravel\Sanctum\HasApiTokens;
use Romanlazko\Telegram\Traits\HasBots;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Cog\Contracts\Ban\Bannable as BannableInterface;
use Cog\Laravel\Ban\Traits\Bannable;
use Cog\Laravel\Ban\Models\Ban;


class User extends Authenticatable implements HasMedia, MustVerifyEmail, BannableInterface
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
    use Tokenable;
    use Bannable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'avatar',
        'email',
        'password',
        'telegram_chat_id',
        'notification_settings',
        'communication_settings',
        'locale',
        'languages',
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
        'notification_settings' => 'array',
        'communication_settings' => 'object',
        'languages' => 'array',
    ];

    protected static function booted(): void
    {
        static::updating(function (User $user) {
            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }
        });

        static::deleted(function (User $user) {
            $user->announcements()->delete();
            $user->threads()->delete();
            $user->votes()->delete();
        });

        static::forceDeleted(function (User $user) {
            $user->announcements()->forceDelete();
            $user->threads()->forceDelete();
            $user->votes()->forceDelete();
            $user->contacts()->forceDelete();
        });

        static::restored(function (User $user) {
            $user->announcements()->restore();
            $user->threads()->restore();
            $user->votes()->restore();
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

    public function latestBan()
    {
        return $this->morphOne(Ban::class, 'bannable')->latestOfMany();
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
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
        $languages = json_decode($this->attributes['languages'], true);

        if (is_array($languages) && ! empty($languages)) {
            return $languages;
        }

        return [];
    }

    public function isProfileFilled(): bool
    {
        return ! is_null($this->languages) AND is_array($this->languages) AND ! empty($this->languages) AND ! is_null($this->name); 
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

    public function disableAnnouncements()
    {
        return $this->announcements()->update(['is_active' => false]);
    }
}
