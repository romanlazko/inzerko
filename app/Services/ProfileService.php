<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravolt\Avatar\Facade as Avatar;

class ProfileService 
{
    public static function create(
        string $name = null,
        string $email = null,
        string $password = null,
        int $telegram_chat_id = null,
        string $telegram_token = null,
        string $locale = null,
        array $communication_settings = null,
        array $notification_settings = null,
        string|\Illuminate\Http\UploadedFile $avatar = null
    )
    {
        $user = User::create(array_filter([
            'name' => $name,
            'email' => $email,
            'password' => $password ? Hash::make($password) : null,
            'telegram_chat_id' => $telegram_chat_id,
            'telegram_token' => $telegram_token,
            'locale' => $locale,
            'communication_settings' => $communication_settings,
            'notification_settings' => $notification_settings
        ], function ($value) {
            return !is_null($value) && $value !== '';
        }));

        self::addMedia($user, $avatar);

        return $user;
    }

    public static function addMedia(User $user, $avatar)
    {
        if (filter_var($avatar, FILTER_VALIDATE_URL)) {
            return tap($user, function (User $user) use ($avatar) {
                $user->addMediaFromUrl($avatar)->toMediaCollection('avatar');
            });
        }

        if ($avatar instanceof \Illuminate\Http\UploadedFile) {
            return tap($user, function (User $user) use ($avatar) {
                $user->addMedia($avatar)->toMediaCollection('avatar');
            });
        }

        return tap($user, function (User $user) {
            $user->addMediaFromBase64(Avatar::create($user->name))->toMediaCollection('avatar');
        });
    }

    public static function update(
        User $user,
        string $name = null,
        string $email = null,
        string $password = null,
        int $telegram_chat_id = null,
        string $telegram_token = null,
        string $locale = null,
        array $communication_settings = null,
        array $notification_settings = null
    ) {
        $user->fill(array_filter([
            'name' => $name,
            'email' => $email,
            'password' => $password ? Hash::make($password) : null,
            'telegram_chat_id' => $telegram_chat_id,
            'telegram_token' => $telegram_token,
            'locale' => $locale,
            'communication_settings' => $communication_settings ? self::updateData($communication_settings, (array) $user->communication_settings) : null,
            'notification_settings' => $notification_settings
        ], function ($value) {
            return !is_null($value) && $value !== '';
        }));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if (is_null($notification_settings)) {
            $user->notification_settings = null;
        }
        
        $user->save();

        return $user;
    }

    private static function updateData(array $newData, array $oldData): ?object
    {
        if (is_null($newData)) {
            return null;
        }

        foreach ($newData as $key => $value) {
            $oldData[$key] = $value;
        }

        return (object) $oldData;
    }
}