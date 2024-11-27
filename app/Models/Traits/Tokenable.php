<?php 

namespace App\Models\Traits;

use App\Models\AccessToken;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;

trait Tokenable
{
    public function accessTokens()
    {
        return $this->morphMany(AccessToken::class, 'tokenable');
    }

    public function createAccessToken(string $name, ?CarbonInterface $expires_at = null, int $max_length = 60): AccessToken
    {
        return $this->accessTokens()->updateOrCreate([
            'name' => $name,
        ],
        [
            'token' => str()->random($max_length),
            'expires_at' => $expires_at ?? now()->addMinutes(60),
        ]);
    }

    public static function findByToken(string $token): ?User
    {
        return static::whereHas('accessTokens', function (Builder $query) use ($token) {
            $query->where('token', $token)->where('expires_at', '>=', now());
        })->first();
    }

    public static function findByTokenOrFail(string $token): User
    {
        return static::findByToken($token) ?? abort(403, 'Invalid token.');
    }

    public function tokenByName(string $name): ?AccessToken
    {
        return $this->accessTokens()->where('name', $name)->first();
    }
}