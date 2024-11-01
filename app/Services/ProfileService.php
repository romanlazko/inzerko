<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ProfileService 
{
    public static function create(string $name = null, string $email = null, string $password = null, string $phone = null, string $locale = null, int $telegram_chat_id = null, string $telegram_token = null)
    {
        return User::create(array_filter([
            'name' => $name,
            'email' => $email,
            'password' => $password ? Hash::make($password) : null,
            'phone' => $phone,
            'locale' => $locale,
            'telegram_chat_id' => $telegram_chat_id,
            'telegram_token' => $telegram_token,
        ], function ($value) {
            return !is_null($value) && $value !== '';
        }));
    }

    public static function addMediaFromBase64(User $user, string $base64)
    {
        return $user->addMediaFromBase64($base64)->toMediaCollection('avatar');
    }

    public static function addMediaFromUrl(User $user, string $url)
    {
        return $user->addMediaFromUrl($url)->toMediaCollection('avatar');
    }

    public static function update(User $user, string $name = null, string $email = null, string $phone = null, string $locale = null, int $telegram_chat_id = null, string $telegram_token = null)
    {
        $user->fill(array_filter([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'locale' => $locale,
            'telegram_chat_id' => $telegram_chat_id,
            'telegram_token' => $telegram_token,
        ], function ($value) {
            return !is_null($value) && $value !== '';
        }));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        
        $user->save();

        return $user;
    }
}